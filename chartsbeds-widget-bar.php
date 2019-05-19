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
        
        if(empty($qval)){$qval = '5.0000';}
        $arrPercent[$qname]= $qval;
	}

    $bars = '<script>
        window.onload = (function() {
            let allBars = document.querySelectorAll(".progress .progress-bar");
            for(bar of allBars){
                bar.style.width = bar.getAttribute("aria-valuenow") + "%";
            }
        });
    </script>';
    
    $pl = 1;
        foreach($arrPercent as $k=>$v){
			$the_value = intval($v*20); 
			$bars .= '<div class="progress skill-bar">
                        <div class="progress-bar progress-'.$pl.' progress-bar-striped active" role="progressbar" aria-valuenow="'.$the_value.'" aria-valuemin="0" aria-valuemax="100">
                            <span class="skill">'.__( $k , 'cbcircles' ).'<i class="val">'.$the_value.'%</i></span>
                        </div>
                     </div>';
            $pl++; 
        }
    return $bars;
} 
add_shortcode('chartsbeds-review-bar', 'cbeds_widget_bar_creation');