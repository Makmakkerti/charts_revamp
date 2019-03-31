<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

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
	}

    echo '<script>';
    echo 'jQuery(document).ready(function() {';
    echo 'jQuery(".charts-widg-p").shorten({ "showChars" : 100, "moreText": " +", "lessText": " -",});';
    echo 'jQuery(".cb-rev-clients").shorten({"showChars" : 100, "moreText"	: " +", "lessText"	: " -",});';
    echo 'jQuery(".morecontent a").addClass("btn btn-default btn-xs");';
     echo '});';
    echo '</script>';

    //Start comments Widget
    echo '<div class="panel panel-default cb-widget-box">';
    if(get_option("thanks_on")) {
        echo '<div class="cb-thanks"><a href="http://www.chartsbeds.com/" target="_blank"><img src="' . plugins_url() . '/chartsbeds/img/chartsbeds-web-logo.png" width="100px" /></a></div>';
    }
        echo '<div class="panel-body">';
            echo '<ul class="media-list">';

                  $obj = json_decode($json, true);

                foreach ($obj as $title => $data){
                    $counter = 1;
                    foreach($data as $q=>$res) {
                        if(is_array($res)){
                            echo '<li class="media">';
                            if(!get_option("gravataroff")) echo '<div class="media-left"><img src="'.$res['gravatar'].'" class="img-circle" width="60px"></div>';
                                echo '<div class="media-body">';
                                    echo '<span class="revdate">'.$res['timestamp'].'</span>';
                                    echo '<h4 class="media-heading">';
                                    echo '<small><b>'.ucfirst($res['name']).'</b> <br />'.$res['country'].'</small><br><small><span class="fa fa-thumbs-up" style="color:#337ab7"></span>&nbsp;';
                                    echo $res['guest_rating'].'% Satisfied <br></small>';
                                    echo '</h4>';
                                    echo '<p class="charts-widg-p">';
                                    echo $res['review'];
                                    echo '</p>';
									
									if($res['recommends']){
										echo '<p class="charts-widg" ><small><span class="fa fa-heart" style="color:red">&nbsp;</span>';
										echo ucfirst($res['name']);
										_e( ' recommends this hotel' , 'cbrevpage' );
										echo '</small></p>';
									}
									
                                echo '</div></li><hr>';
                        }
                    }
                }

                if(get_option('rev_url') !== 0)echo '<a href="'.get_option('rev_url').'" class="btn btn-primary">'.__( 'Go to reviews page' , 'cbrevpage' ).'</a>';
                echo '</ul></div></div>';

   //End comments Widget
}

add_shortcode('chartsbeds-review-recent', 'cbeds_review_widget_shortcode');