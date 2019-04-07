<?php
if (!defined( 'ABSPATH' )) exit; // Exit if accessed directly

function cbeds_review_widget_shortcode($atts) {
    $cba = shortcode_atts( array(
        'limit' => esc_attr($cba['limit']),
		'key' => esc_attr($cba['key']),
    ), $atts );

    if(empty($cba['limit'])){
        if(!get_option('rec_amt')) {
            $cba['limit'] = 4;
        }else{
            $cba['limit'] = get_option('rec_amt');
        }
    }
    
    $Context = stream_context_create(array('http' => array('timeout' => '2',)));
	if(!$cba['key']){
		$thekey = htmlspecialchars_decode (get_option("charts_key"));
		$json = file_get_contents('http://dashboard.chartspms.com/REVIEWS.json.php?apiKey='.$thekey.'&limit='.esc_attr($cba['limit']).'', true, $Context);
	}else{
		$ekey = 'http://dashboard.chartspms.com/REVIEWS.json.php?apiKey='.$cba['key'].'&limit='.esc_attr($cba['limit']).'';
		$json = file_get_contents($ekey, true, $Context);
	} ?>

<script>
    jQuery(document).ready(function() {
        jQuery(".charts-widg-p").shorten({
            "showChars": 100,
            "moreText": " +",
            "lessText": " -",
        });
        jQuery(".cb-rev-clients").shorten({
            "showChars": 100,
            "moreText": " +",
            "lessText": " -",
        });
        jQuery(".morecontent a").addClass("btn btn-default btn-xs");
    });
</script>

<?php  //Start comments Widget ?>
<div class="panel panel-default cb-widget-box">
    <?php if(get_option("thanks_on")) { ?>
    <div class="cb-thanks"><a href="http://www.chartsbeds.com/" target="_blank"><img
                src="<?php echo plugins_url() ?>/chartsbeds/img/chartsbeds-web-logo.png" width="100px" /></a></div>
    <?php } ?>
    <div class="panel-body">
        <ul class="media-list">
            <?php
                $obj = json_decode($json, true);
                foreach ($obj['reviews'] as $res){
                    $counter = 1;
                    if(is_array($res)){ ?>
                        <li class="media">
                            <?php if(!get_option("gravataroff")) ?>
                            <div class="media-left"><img src="<?php echo $res['gravatar'] ?>" class="img-circle" width="60px"></div>
                            <div class="media-body">
                                <span class="revdate"><?php echo $res['timestamp'] ?></span>
                                <h4 class="media-heading">
                                    <small><b><?php echo ucfirst($res['name']) ?></b>
                                        <br /><?php echo $res['country'] ?></small><br>
                                    <small><span class="fa fa-thumbs-up" style="color:#337ab7"></span>
                                    <?php echo $res['guest_rating']?>% Satisfied <br></small>
                                </h4>
                                <p class="charts-widg-p"><?php $res['review']; ?></p>

                                <?php if($res['recommends']){ ?>
                                    <p class="charts-widg"><small><span class="fa fa-heart" style="color:red"> </span>
                                    <?php echo ucfirst($res['name']); ?>
                                    <?php _e( ' recommends this hotel' , 'cbrevpage' ); ?></small></p>
                                <?php } ?>

                            </div>
                        </li>
                        <hr>
            <?php   }
                }
            if(get_option('rev_url') !== 0) ?>
            <a href="<?php echo get_option('rev_url') ?>" class="btn btn-primary"><?php echo __( 'Go to reviews page' , 'cbrevpage' ) ?></a>
        </ul>
    </div>
</div>
<?php
   //End comments Widget
}
add_shortcode('chartsbeds-review-recent', 'cbeds_review_widget_shortcode');