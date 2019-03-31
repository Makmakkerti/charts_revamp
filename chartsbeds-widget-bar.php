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

    $obj = json_decode($json, true);
	$arrPercent ='';
	
	for ($i=1; $i<=5; $i++){

    $qval = $obj['reviews_average']['question'.$i.''];
	$qname = $obj['questions']['question'.$i.''];
	
	if(empty($qval)){
		$qval = '5.0000';	
	}

    $arrPercent[$qname]= $qval;
	
	}

        echo '<script>';
        echo 'jQuery(document).ready(function() {';
        echo 'jQuery(".progress .progress-bar").css("width",function() {return jQuery(this).attr("aria-valuenow") + "%";});';
        echo 'jQuery(".charts-widg-p").shorten({ "showChars" : 100, "moreText": " +", "lessText": " -",});';
        echo 'jQuery(".cb-rev-clients").shorten({"showChars" : 100, "moreText"	: " +", "lessText"	: " -",});';
        echo 'jQuery(".morecontent a").addClass("btn btn-default btn-xs");});';
        echo '</script>';

	
    $pl = 1;
    foreach($arrPercent as $k=>$v){
        $the_value = intval($v*20);
        echo '<div class="progress skill-bar ">';
        //Use the same translation domain as circles - cbcircles!!!
        echo '<div class="progress-bar progress-'.$pl.' progress-bar-striped active" role="progressbar" aria-valuenow="'.$the_value.'" aria-valuemin="0" aria-valuemax="100">';
        echo '<span class="skill">'.__( $k , 'cbcircles' ).'<i class="val">'.$the_value.'%</i></span>';
        echo '</div></div>';
        $pl++; }
    }

add_shortcode('chartsbeds-review-bar', 'cbeds_widget_bar_creation');