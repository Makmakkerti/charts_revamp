<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function cbeds_widget_bar_creation($atts){
	
	$cbb = shortcode_atts( array(
        'key' => esc_attr($cbb['key']),
    ), $atts );
    
    $Context = stream_context_create(array('http' => array('timeout' => '2',)));
	
	if(empty($cbb['key'])){
		$thekey = htmlspecialchars_decode (get_option("charts_key"));
		$json = file_get_contents('http://dashboard.chartspms.com/REVIEWS.json.php?apiKey='.$thekey.'', true, $Context);
	}else{
		$ekey = 'http://dashboard.chartspms.com/REVIEWS.json.php?apiKey='.$cbb['key'].'';
		$json = file_get_contents($ekey, true, $Context);
	}

    wp_enqueue_style( 'progress-css', plugins_url( 'styles/progress.css', __FILE__ ) );
    wp_register_style( 'progress-css', plugins_url( 'styles/progress.css', __FILE__ ) );
    
    $obj = json_decode($json, true);
	$arrPercent =[];
	
	for ($i=1; $i<=5; $i++){
        $question = "question".$i;
        $qval = $obj['reviews_average'][$question];
        $qname = $obj['questions'][$question];
        
        if(empty($qval)){
            $qval = '5.0000';	
        }

        $arrPercent[$qname]= $qval;
	}
?>
<script>
    jQuery(document).ready(function() {
        jQuery(".progress .progress-bar").css("width", function() {
            return jQuery(this).attr("aria-valuenow") + "%";
        });
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

<div class="reviews-progressbar">
    <?php
    $pl = 1;
        foreach($arrPercent as $k=>$v){
            $the_value = intval($v*20); ?>
            <div class="progress skill-bar ">
            <!-- Use the same translation domain as circles - cbcircles!!! -->
            <div class="progress-bar progress-<?php echo $pl ?> progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo  $the_value ?>" aria-valuemin="0" aria-valuemax="100">
            <span class="skill"><?php echo __( $k , 'cbcircles' ) ?><i class="val"><?php echo $the_value ?>%</i></span>
            </div></div>
            <?php
            $pl++; 
            if($pl>5){
                echo "</div>";
            }
        }
    } ?>
</div>

<?php add_shortcode('chartsbeds-review-bar', 'cbeds_widget_bar_creation');