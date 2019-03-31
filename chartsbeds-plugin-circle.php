<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function cbeds_circles_func($atts){
	$cbc = shortcode_atts( array(
        'key' => esc_attr($cbc['key']),
    ), $atts );
    
    $Context = stream_context_create(array('http' => array('timeout' => '2',)));
	
	if(empty($cbc['key'])){
		$thekey = htmlspecialchars_decode (get_option("charts_key"));
		$json = file_get_contents('http://dashboard.chartspms.com/REVIEWS.json.php?apiKey='.$thekey.'', true, $Context);
	}else{
		$ekey = 'http://dashboard.chartspms.com/REVIEWS.json.php?apiKey='.$cbc['key'].'';
		$json = file_get_contents($ekey, true, $Context);
	}
	
  $obj = json_decode($json, true);
  
  if(!empty($obj)){
	$arrPercent ='';
	$cssafter = '';
 echo "<script type='text/javascript' src='".plugins_url( 'scripts/circles.js', __FILE__ )."'></script>";
        echo '<div id="canvas">';	
	
	for ($i=1; $i<=5; $i++){

    
	$qname = $obj['questions']['question'.$i.''];
	$qval = $obj['reviews_average']['question'.$i.''];
	
	if(empty($qval)){
		$qval = '5.0000';	
	}
	
	$cssafter .= " #circles-".$i.":after {content: '".trim(__( $qname , 'cbcircles' ))."';} ";

    $arrPercent[''.$qname.''] = $qval;
	echo '<div class="wrap_circle" style="float:left;"><div class="circle" id="circles-'.$i.'">'.__( $qname , 'cbcircles' ).'</div></div>';
	}
 echo '</div>';

	echo "<style type=\"text/css\" media=\"screen\">".$cssafter."</style>";

    echo '<script type="application/javascript">';
        echo "var colors = [['#D3B6C6', '#4B253A'], ['#FCE6A4', '#EFB917'], ['#BEE3F7', '#45AEEA'], ['#F8F9B6', '#D2D558'], ['#F4BCBF', '#D43A43']], circles = []; \n";

        $i = 1;
        foreach($arrPercent as $k=>$v){
            $c_value = intval($v*20);
        echo "var child = document.getElementById('circles-".$i."'), percentage = '".$c_value."',";
                $h_color = $i-1;
            echo "circle = Circles.create({ id:child.id,  value:percentage, radius:getWidth(), width:10, colors:colors['".$h_color."'],  duration:900,}); \n";

        $i++; }

    echo "circles.push(circle); \n";
    echo "window.onresize = function(e) {for (var i = 0; i < circles.length; i++) {circles[i].updateRadius(getWidth());}}; \n";
    echo "function getWidth() {return window.innerWidth /28;} \n";
    echo "</script>";

}else{
    echo "Service temporary unavailable.";
}
}

add_shortcode('chartsbeds-review-circle', 'cbeds_circles_func');