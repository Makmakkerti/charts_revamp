<?php
/*
* Plugin Name: Chartsbeds
* Description: Chartsbeds reviews plugin.
* Version: 1.4
* Author: ChartsBeds
* Author URI: https://chartsbeds.com
*/

//Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

$pluginPath = plugin_dir_path(__FILE__);
include ( $pluginPath . 'chartsbeds-services.php' );
include ( $pluginPath . 'chartsbeds-widget-bar.php' );
include ( $pluginPath . 'chartsbeds-plugin-circle.php' );

include ( $pluginPath . 'chartsbeds-plugin-page.php' );
include ( $pluginPath . 'chartsbeds-widget-review.php' );

include ( $pluginPath . 'admin_widget_review.php' );
include ( $pluginPath . 'admin_widget_bar.php' );

/*ADDING AND REGISTERING STYLES AND SCRIPTS*/
add_action('wp_head', 'cbeds_add_header_mc');

function cbeds_add_header_mc() {
    wp_enqueue_style( 'rvmain-css', plugins_url( 'styles/style.css', __FILE__ ) );
    wp_register_style( 'rvmain-css', plugins_url( 'styles/style.css', __FILE__ ) );
    
    // if(get_option("dark_on")){
    //     wp_enqueue_style( 'rvdark-css', plugins_url( 'styles/dark.css', __FILE__ ) );
    //     wp_register_style( 'rvdark-css', plugins_url( 'styles/dark.css', __FILE__ ) );
    // }
    // wp_enqueue_style( 'font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
    
    if(get_option("bootstrap_on")){
        wp_enqueue_style( 'bootstrapcdn', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css' );
    }
    
    wp_enqueue_script( 'circles', plugins_url( 'scripts/circles.js', __FILE__ ) );
    wp_enqueue_script( 'shorten', plugins_url( 'scripts/shorten.js', __FILE__ ) );
}

function wpb_adding_scripts() {
wp_register_script( 'iframe-resizer', 'https://cdnjs.cloudflare.com/ajax/libs/iframe-resizer/3.5.14/iframeResizer.min.js', '', '', false);
wp_enqueue_script( 'iframe-resizer');}
add_action( 'wp_enqueue_scripts', 'wpb_adding_scripts' );
add_action( 'wp_footer', function () { ?>

    <script language="javascript" type="text/javascript">
        iFrameResize({log:false, checkOrigin: false, heightCalculationMethod:'min',
            initCallback: function(){
                window.scrollTo(0, 0);
            }
        });
    </script>

<?php } );

/*Adding settings page to Admin Panel*/
function charts_admin() {
    include('chartsbeds_admin.php');
}

function charts_admin_actions() {
    add_menu_page("Chartsbeds", "Chartsbeds", 1, "Chartsbeds", "charts_admin", plugins_url()."/chartsbeds/chartsbeds_ico.png", 7);
    //Description of recieved data:( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
}

add_action('admin_menu', 'charts_admin_actions');