<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


// Include the pagination class
include 'pagination.class.php';

/// Function ADD Reviews to the page
function cbeds_review_add_shortcode($cbh) {
    $cbh = shortcode_atts( array(
        'limit' => esc_attr($cbh['limit']),
		'key' => esc_attr($cbh['key']),
    ), $atts );

    if(empty($cbh['limit'])){
        $cbh['limit'] = 200;
    }
    
    $Context = stream_context_create(array('http' => array('timeout' => '2',)));
	
	if(empty($cbh['key'])){
		$thekey = htmlspecialchars_decode (get_option("charts_key"));
		$json = file_get_contents('http://dashboard.chartspms.com/REVIEWS.json.php?apiKey='.$thekey.''.'&limit='.esc_attr($cbh['limit']).'', true, $Context);
	}else{
		$ekey = 'http://dashboard.chartspms.com/REVIEWS.json.php?apiKey='.$cbh['key'].'&limit='.esc_attr($cbh['limit']).'';
		$json = file_get_contents($ekey, true, $Context);
	}

    //$json = file_get_contents('http://dashboard.chartspms.com/REVIEWS.json.php?apiKey='.get_option("charts_key").'&limit='.esc_attr($cbh['limit']).'');
    $obj = json_decode($json, true);

    echo '<script>';
    echo 'jQuery(document).ready(function() {';
    echo 'jQuery(".charts-widg-p").shorten({ "showChars" : 100, "moreText": " +", "lessText": " -",});';
    echo 'jQuery(".cb-rev-clients").shorten({"showChars" : 100, "moreText"	: " +", "lessText"	: " -",});';
    echo 'jQuery(".morecontent a").addClass("btn btn-default btn-xs");';
    echo 'jQuery(".morelink").click(function(){if (jQuery(this).closest( ".rcustomers" ).hasClass( "col-md-10" )){jQuery(this).closest( ".rcustomers" ).removeClass( "col-md-10" )}else{jQuery(this).closest( ".rcustomers" ).addClass( "col-md-10" )};});';
    echo '});';
    echo '</script>';

    echo '<div class="row tinliner" >';

    echo '<div class="cb-thanks"><a href="http://www.chartsbeds.com/" target="_blank"><img src="' . plugins_url() . '/chartsbeds/img/chartsbeds-web-logo.png" width="100px" /></a></div>';
    

    $all_reviews = $obj['reviews'];

    // If we have an array with items
    if (count($all_reviews)) {

        if(!get_option('rev_per_page')){
            $per_page = get_option('rev_per_page');
        }else{
            $per_page = 10;
        }

        // Create the pagination object
        $pagination = new pagination($all_reviews, (!empty(get_query_var('page')) ? get_query_var('page') : 1), $per_page );
        // Decide if the first and last links should show
        $pagination->setShowFirstAndLast(false);
        // You can overwrite the default seperator
        $pagination->setMainSeperator('');
        // Parse through the pagination class
        $reviewsPages = $pagination->getResults();
        // If we have items
        if (count($reviewsPages) != 0) {

            // Loop through all the items in the array
            $counter = 1;
            foreach ($reviewsPages as $reviewsArray) {
                $g_rates = $reviewsArray['guest_rating']*0.58;


                echo '<div class="col-md-6  rcustomers">';
                echo '<div class="testimonials">';
                echo '<div class="active item">';
                echo '<blockquote><p class="cb-rev-clients">'.$reviewsArray['review'].'';
                if($reviewsArray['answer']){
                    echo "<br><i class='fa fa-comments revanswer' aria-hidden='true'></i>".$obj['property']." answered: ".$reviewsArray['answer'];
                }
                echo '</p></blockquote>';
                echo '<div class="testimonials-rate col-md-4">'.__( 'Rating' , 'cbrevpage' ). ': '.$reviewsArray['guest_rating'].'';
                echo '<div class="star-ratings">';
                echo '<div class="star-ratings-top" style="width:'.$g_rates.'px"><span>★</span><span>★</span><span>★</span><span>★</span><span>★</span></div>';
                echo '<div class="star-ratings-bottom"><span>☆</span><span>☆</span><span>☆</span><span>☆</span><span>☆</span></div></div></div>';
                echo '<div class="carousel-info">';

                echo '<img alt="" src="'.$reviewsArray['gravatar'].'" class="pull-left">';

                echo '<div class="pull-left">';
                echo '<span class="testimonials-name">'.$reviewsArray['name'].'</span>';
                echo '<span class="testimonials-time">'.$reviewsArray['country'].'</span>';
                echo '<span class="testimonials-post">'.$reviewsArray['timestamp'].'</span>';
				
				if($reviewsArray['recommends']){
					echo '<span class="testimonials-post"><i class="fa fa-heart recommends" aria-hidden="true"></i> '.$reviewsArray['name'].'&nbsp;'.__( 'recommends this hotel' , 'cbrevpage' );
					echo '</span>';
				}
				
                echo "</div></div></div> \n </div> \n </div> \n ";
                $counter++;
            }
            // print out the page numbers beneath the results
            echo $pageNumbers = '<ul class="charts-pagination">'.$pagination->getLinks($_GET).'</ul>';
        }
    }
}

add_shortcode('chartsbeds-review-page', 'cbeds_review_add_shortcode');