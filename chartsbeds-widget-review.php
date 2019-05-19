<?php
if (!defined( 'ABSPATH' )) exit; // Exit if accessed directly

function cbeds_review_widget_shortcode($atts) {

    $cba = shortcode_atts(array(
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
    } 
    
    $revWidget = '<script>
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
    <div class="panel panel-default cb-widget-box">';

    if(get_option("thanks_on")) { 
        $revWidget .= '<div class="cb-thanks"><a href="http://www.chartsbeds.com/" target="_blank"><img src="'.plugin_dir_url( __FILE__ ).'/img/chartsbeds-web-logo.png" width="100px" /></a></div>';
    }
    $revWidget .= '<div class="panel-body"><ul class="media-list">';

    $obj = json_decode($json, true);
    foreach ($obj['reviews'] as $res){
        if(is_array($res)){
            $revWidget .= '<li class="media">';
            if(!get_option("gravataroff")){
                $revWidget .= '<div class="media-left"><img src="'.$res['gravatar'].'" class="img-circle" width="60px"></div>
                <div class="media-body">
                    <span class="revdate">'.$res['timestamp'].'</span>
                    <div class="media-heading">
                        <small><b>'.ucfirst($res['name']).'</b>
                            <br />'.$res['country'].'</small><br>
                        <small><span class="fa fa-thumbs-up" style="color:#337ab7"></span>
                        '.$res['guest_rating'].'% Satisfied <br></small>
                    </div>
                    <p class="charts-widg-p">'.$res['review'].'</p>';
                    if($res['recommends']){
                        $revWidget .= '<p class="charts-widg"><small><span class="fas fa-heart" style="color:red"></span>
                        '.ucfirst($res['name']).' '.__( ' recommends this hotel' , 'cbrevpage' ).'</small></p>';
                    }
                $revWidget .= '</div></li><hr>';
            }
        }
        if(get_option('rev_url') !== 0){ 
            $revWidget .= '<a href="'.get_option('rev_url').'" class="btn btn-primary">'.__( 'Go to reviews page' , 'cbrevpage' ).'</a></ul></div></div>';
        }
    }
   return $revWidget;
}
add_shortcode('chartsbeds-review-recent', 'cbeds_review_widget_shortcode');