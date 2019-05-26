<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'wp_head', function () { 

    $thekey = htmlspecialchars_decode (get_option("charts_key"));
    $json = file_get_contents('http://dashboard.chartspms.com/REVIEWS.json.php?apiKey='.$thekey.'', true, $Context);
    $property = json_decode($json, true);

    $pName = get_option("cbsnippet_propname");
    $pUrl = get_option("cbsnippet_pageurl");
    $pCountry = get_option("cbsnippet_country");
    $pCity = get_option("cbsnippet_city");
    $pAddr = get_option("cbsnippet_street");
    $pPostal = get_option("cbsnippet_postal");
    $pPhone = get_option("cbsnippet_phone");
    $pPrice = get_option("cbsnippet_price");

    $snipOut = '
    <script type="application/ld+json">
        {
            "@context": "http://schema.org",
            "@type": "LodgingBusiness", 
            "name": "'.$pName.'",
            "url": "'.$pUrl.'",
            "image": "'.the_custom_logo().'",
            "address": {
            "@type": "PostalAddress",
            "addressCountry": "'.$pCountry.'",
            "addressLocality": "'.$pCity.'",
            "postalCode": "'.$pPostal.'",
            "streetAddress": "'.$pAddr.'"
            },
            "telephone": "'.$pPhone.'",
            "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "'.round($property["reviews_average"]["question1"],1).'",
            "reviewCount": "'.$property["reviews_count"].'",
            },
            "priceRange": "'.$pPrice.'"
        }
    </script>';
    
    echo $snipOut;
 });