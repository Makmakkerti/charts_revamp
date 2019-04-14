<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

function cbeds_circles_func($atts){
	$cbc = shortcode_atts( array(
        'key' => esc_attr($cbc['key']),
    ), $atts );

	if(empty($cbc['key'])){
		$thekey = htmlspecialchars_decode (get_option("charts_key"));
		$json = file_get_contents('http://dashboard.chartspms.com/REVIEWS.json.php?apiKey='.$thekey.'', true, $Context);
	}else{
		$thekey = 'http://dashboard.chartspms.com/REVIEWS.json.php?apiKey='.$cbc['key'].'';
		$json = file_get_contents($thekey, true, $Context);
    }

    echo "<script type='text/javascript' src='".plugins_url( 'scripts/circles.js', __FILE__ )."'></script>";
?>

<style>
	span.circleTitle {
		display: block;
		width: 100%;
		font-size: 20px;
		padding: 0;
		margin: 0px;
		line-height: 40px;
	}
</style>

<div id="chartsbedsCircles" style="min-height: 280px;">
    <?php 
        for($i = 1; $i <= 5; $i++){ ?>
    <div class="wrap_circle" style="float:left;">
        <div class="circle" id="circles-<?php echo $i ?>">
            <div class="circles-wrp">
                <div class="circles-text">
                    <span class="circles-integer"></span>
                </div>
            </div>
        </div>
    </div>
    <?php } ?>
</div>

<script type="text/javascript">
        <?php echo "let data = $json;" ?>
        let questions = data.questions;
        let answers = data.reviews_average;

        for (let i = 1; i <= 5; i++) {
            let circleArea = document.querySelector("#chartsbedsCircles");
            let index = "question" + i;
            //Color settings for circles
            let colors = [
                ["#D3B6C6", "#4B253A"],
                ["#FCE6A4", "#EFB917"],
                ["#BEE3F7", "#45AEEA"],
                ["#F8F9B6", "#D2D558"],
                ["#F4BCBF", "#D43A43"]
            ];
            let circles = [];
            let circleId = "#circles-" + i;
            let circleData = document.querySelector(circleId);
            let percentage = answers[index] * 20;
            let h_color = colors[i - 1];
            let circle = Circles.create({
                id: circleData.id,
                value: percentage,
                radius: getWidth(),
                width: 10,
                colors: h_color,
                duration: 900,
                text: function(currentValue) {
                    return currentValue + "% " + "<span class='circleTitle'>" + questions[index] +
                        "</span>";
                }
            });
            circles.push(circle);
        }

window.onresize = function(e) {
    for (var i = 0; i < circles.length; i++) {
        circles[i].updateRadius(getWidth());
    }
};

function getWidth() {
    //Size of circles can be chenged here
    return window.innerWidth / 28;
}
</script>

<?php }
add_shortcode('chartsbeds-review-circle', 'cbeds_circles_func');