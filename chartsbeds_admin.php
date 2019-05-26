<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if( !current_user_can('edit_others_pages') ) {echo "You have no permission to edit this page"; exit;}  // Exit if user have no permissions to edit site

$submitted_value = $_REQUEST['_wpnonce'];

if($GET['action']= 'update' && wp_verify_nonce($submitted_value, 'cbeds-update')) {
    //Form data sent
	$apiKey = esc_html($_POST['charts_key']);
	update_option('charts_key', $apiKey);

    $urlRev = esc_url_raw(esc_html($_POST['rev_url']));
    update_option('rev_url', $urlRev);

    $recAmt = intval($_POST['rec_amt']);
    update_option('rec_amt', $recAmt);

    $rev_per_page = intval($_POST['rev_per_page']);
    if($rev_per_page>50){$rev_per_page = 50;}
    update_option('rev_per_page', $rev_per_page);
		 
    if(!empty($_POST['gravataroff'])){
        $gravoff = "checked";
    }else{
        $gravoff = "";
    }
    update_option('gravataroff', $gravoff);

    if(!empty($_POST['answers_off'])){
        $answeroff = "checked";
    }else{
        $answeroff = "";
        }
    update_option('answers_off', $answeroff);

    if(!empty($_POST['thanks_on'])){
        $thanks_on = "checked";
    }else{
        $thanks_on = "";
    }
    update_option('thanks_on', $thanks_on);
			
	if(!empty($_POST['bootstrap_on'])){
        $bootstrap_on = "checked";
    }else{
        $bootstrap_on = "";
    }
    update_option('bootstrap_on', $bootstrap_on);
			
	if(!empty($_POST['dark_on'])){
        $dark_on = "checked";
    }else{
        $dark_on = "";
    }
    update_option('dark_on', $dark_on); 
    
    /** Snippets settings */
    
    $snPropname = $_POST['cbsnippet_propname'];
    update_option('cbsnippet_propname', $snPropname);
    $snPageurl = $_POST['cbsnippet_pageurl'];
    update_option('cbsnippet_pageurl', $snPageurl);
    $snCountry = $_POST['cbsnippet_country'];
    update_option('cbsnippet_country', $snCountry);
    $snCity = $_POST['cbsnippet_city'];
    update_option('cbsnippet_city', $snCity);
    $snStreet = $_POST['cbsnippet_street'];
    update_option('cbsnippet_street', $snStreet);
    $snPostal = $_POST['cbsnippet_postal'];
    update_option('cbsnippet_postal', $snPostal);
    $snPhone = $_POST['cbsnippet_phone'];
    update_option('cbsnippet_phone', $snPhone);
    $snPrice = $_POST['cbsnippet_price'];
    update_option('cbsnippet_price', $snPrice);
?>

<div class="updated">
    <p><strong><?php echo _e('Options saved'); ?></strong></p>
</div>

<?php 
}else{
	$apiKey = get_option('charts_key');
    $urlRev = get_option('rev_url');
    $recAmt = get_option('rec_amt');
    $rev_per_page = get_option('rev_per_page');

    $snPropname = get_option('cbsnippet_propname');
    $snPageurl = get_option('cbsnippet_pageurl');
    $snCountry = get_option('cbsnippet_country');
    $snCity = get_option('cbsnippet_city');
    $snStreet = get_option('cbsnippet_street');
    $snPostal = get_option('cbsnippet_postal');
    $snPhone = get_option('cbsnippet_phone');
    $snPrice = get_option('cbsnippet_price');
}

?>

<div class="wrap">
    <h2><?php echo __( 'ChartsBeds Options', 'charts_updates' ) ?></h2>
    <form name="charts_form" method="post"
        action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']) ?>&action=update">

        <h4><?php __( 'Chartsbeds API KEY', 'charts_updates' ) ?></h4>
        <p><?php _e("Insert API KEY: " ); ?> 
            <input type="text" name="charts_key" id="charts_key" value="<?php echo $apiKey ?>" size="110">
            <label for="charts_key"> <?php _e(" to recieve KEY, please contact Chartsbeds support" ); ?></label></p>

        <p><?php _e("Insert Reviews page url (optional):" ); ?>
            <input type="text" name="rev_url" id="rev_url" value="<?php echo $urlRev ?>" size="110"></p>

        <p><?php _e("Recent reviews widget, choose amount to show:" ); ?>
            <input type="number" name="rec_amt" id="rec_amt" name="quantity" value="<?php echo $recAmt ?>" min="1"
                max="8"></p>

        <p><?php _e("Reviews to show per page (by default = 10):"); ?>
            <input type="number" name="rev_per_page" id="rev_per_page" name="quantity"
                value="<?php echo $rev_per_page ?>" min="4" max="150"></p>

        <div>
            <input type="checkbox" id="dark_on" name="dark_on" value="checking" <?php echo get_option("dark_on") ?>>
            <label for="dark_on">Check to use dark theme</label></div>

        <div>
            <input type="checkbox" id="bootstrap_on" name="bootstrap_on" value="checking"
                <?php echo get_option("bootstrap_on") ?>>
            <label for="bootstrap_on">Check to activate Bootstrap (for themes without bootstrap)</label></div>

        <div>
            <input type="checkbox" id="gravataroff" name="gravataroff" value="checking"
                <?php echo get_option("gravataroff") ?>>
            <label for="gravataroff">Check to disable gravatars for reviews widget</label></div>

        <div>
            <input type="checkbox" id="answers_off" name="answers_off" value="check"
                <?php echo get_option("answers_off") ?>>
            <label for="answers_off">Check to disable hotel's answer for reviews</label></div>

        <div>
            <input type="checkbox" id="thanks_on" name="thanks_on" value="check" <?php echo get_option("thanks_on") ?>>
            <label for="thanks_on">Check to enable Chartsbeds link in reviews</label></div>

        <hr>
        <h3>Settings for Google Snippets</h3>

        <?php _e("Property name:" ); ?>
        <p><input type="text" name="cbsnippet_propname" id="cbsnippet_propname" value="<?php echo $snPropname ?>" size="110"></p>

        <?php _e("Reviews page url:" ); ?>
        <p><input type="text" name="cbsnippet_pageurl" id="cbsnippet_pageurl" value="<?php echo $snPageurl ?>" size="110"></p>

        <?php _e("Country:" ); ?>
        <p><input type="text" name="cbsnippet_country" id="cbsnippet_country" value="<?php echo $snCountry ?>" size="110"></p>

        <?php _e("City:" ); ?>
        <p><input type="text" name="cbsnippet_city" id="cbsnippet_city" value="<?php echo $snCity ?>" size="110"></p>

        <?php _e("Street address:" ); ?>
        <p><input type="text" name="cbsnippet_street" id="cbsnippet_street" value="<?php echo $snStreet ?>" size="110"></p>

        <?php _e("Postal code:" ); ?>
        <p><input type="text" name="cbsnippet_postal" id="cbsnippet_postal" value="<?php echo $snPostal ?>" size="110"></p>

        <?php _e("Phone number(s):" ); ?>
        <p><input type="text" name="cbsnippet_phone" id="cbsnippet_phone" value="<?php echo $snPhone ?>" size="110"></p>

        <?php _e("Price range:" ); ?>
        <p><input type="text" name="cbsnippet_price" id="cbsnippet_price" value="<?php echo $snPrice ?>" size="110"></p>

        <p class="submit"><input type="submit" name="Save" value="<?php _e('Update Options', 'charts_updates' ); ?>" /></p>
        <?php echo wp_nonce_field('cbeds-update'); ?>
    </form>
</div>

<a href="http://www.chartsbeds.com/" target="_blank"><img
        src="<?php echo plugins_url() ?>/chartsbeds/img/chartsbeds-web-logo.png" width="150px"></a>
<a href="http://dashboard.chartspms.com/" target="_blank"><img
        src="<?php echo plugins_url() ?>/chartsbeds/img/review-logo.png" width="200px"></a>

<h2>How to use plugin?</h2>
<b>Shortcodes:</b><br>
[chartsbeds-review-circle] – to activate circle review statistics<br>
[chartsbeds-review-bar] – to activate bar review statistics<br>
[chartsbeds-review-recent] – to activate recent comments (also in widgets). Has limit settings.<br>
[chartsbeds-review-page] – to activate reviews on page. Has limit settings.<br><br>
<b>Limits:</b><br>

With [chartsbeds-review-recent] and [chartsbeds-review-page] you can use limits. <br>
Example [chartsbeds-review-page limit="8"]