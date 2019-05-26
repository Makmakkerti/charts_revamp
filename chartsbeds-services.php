<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/**Services new shortcode Multilang!!!*/
function chartsbeds_services_func( $atts ) {
    $lang = ICL_LANGUAGE_CODE; //Checking language of site page
    
    //Check if $lang equal to languages that we use en (English), fr (French), de (Deutch), es (Spanish), it (Italian), pt-pt (Portugal), ru (Russian), zh-hant (Chinese)
    if($lang == 'en' || $lang == 'fr' || $lang == 'de' || $lang == 'es' || $lang == 'it' || $lang == 'pt-pt' || $lang == 'ru' || $lang == 'zh-hant'){
        $var = file_get_contents('http://facebook.chartsbeds.com/editor/services_'.$lang.'.txt');
    }else{
        $var = file_get_contents('http://facebook.chartsbeds.com/editor/services_en.txt');
    }
    
    //Split content of the file to lines by | separator
        $arr = explode("|",$var);
        $att = '';
        $att['show'] = '';
     
    // Putting lines into array   
    foreach ($arr as $k=>$v){
        $att[$k+1] = trim($v); //to make translatable strings use __( trim($v), 'cbservices' );
    }
    $atts = shortcode_atts( $att, $atts, 'chartsbeds-services' );
    
    //Check, what lines we should show (using shortcode attribute)    
    $myArray = explode(',', $atts['show']);
        
    echo "<div class=\"col-md-12\">";
        foreach ($atts as $att=>$s){
            if(in_array($att, $myArray)){
                if (!empty($s) && $att < 200 ){echo '<li class="services-list">'.$s.'</li>';}
                if (!empty($s) && $att > 199 ){echo '<h4>'.$s.'</h4>';}
            }
        }
    echo "</div>";
}
add_shortcode( 'chartsbeds-services', 'chartsbeds_services_func' );


/**Room deskription new shortcode Multilang!!!*/
function chartsbeds_roomdesc_func( $atts ) {
    
    $lang = ICL_LANGUAGE_CODE; //Checking language of site page
    //Check if $lang equal to languages that we use en (English), fr (French), de (Deutch), es (Spanish), it (Italian), pt-pt (Portugal), ru (Russian), zh-hant (Chinese)
    if($lang == 'en' || $lang == 'fr' || $lang == 'de' || $lang == 'es' || $lang == 'it' || $lang == 'pt-pt' || $lang == 'ru' || $lang == 'zh-hant'){
        $var = file_get_contents('http://facebook.chartsbeds.com/editor/roomdesc_'.$lang.'.txt');
    }else{
        $var = file_get_contents('http://facebook.chartsbeds.com/editor/roomdesc_en.txt');
    }
    
    //Split content of the file to lines by | separator
    $arr = explode("|",$var);
    $att = '';
    $att['show'] = '';
     
    // Putting lines into array   
    foreach ($arr as $k=>$v){
        $att[$k+1] = trim($v); //to make translatable strings use __( trim($v), 'cbroomdesk' );
    }
    $atts = shortcode_atts( $att, $atts, 'chartsbeds-roomdesc' );
    
    //Check, what deskription we should show (using shortcode attribute)    
    $myArray = explode(',', $atts['show']);

    foreach ($atts as $att=>$s){
       if(in_array($att, $myArray)){
           if (!empty($s) && $att > 50 && $att < 100 ){
               $output .= $s;
               $s = $output;
               return $s;
           }else{
               $output .= '<p style="text-align:left">';
               $output .= $s;
               $output .= '</p>';
               $s = $output;
               return $s;
           }
       }
    }
}

add_shortcode( 'chartsbeds-roomdesc', 'chartsbeds_roomdesc_func' );