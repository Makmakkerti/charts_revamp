<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

add_action( 'widgets_init', 'cbeds_bar_widget' );

function cbeds_bar_widget() {
    register_widget( 'CB_bar_Widget' );
}

class CB_bar_Widget extends WP_Widget {

    function __construct(){
        $widget_ops = array( 'classname' => 'wreviews', 'description' => __('A widget that displays hotels review', 'wreviews') );

        $control_ops = array( 'width' => 300, 'height' => 350, 'id_base' => 'wreviews-widget' );

        parent::__construct( 'wreviews-widget', __('Chartsbeds review bar', 'wreviews'), $widget_ops, $control_ops );
		
    }

    function widget( $args, $instance ) {
        extract( $args );

        //Our variables from the widget settings.
        $title = apply_filters('widget_title', $instance['title'] );
        $show_info = isset( $instance['show_info'] ) ? $instance['show_info'] : false;
		$key = isset( $instance['key'] ) ? $instance['key'] : '';

        echo $before_widget."<div class='cb-widget'>";

        // Display the widget title
        if ( $title ){
            echo $before_title . $title . $after_title;
        }

        // Use shortcode in a PHP file (outside the post editor).
        echo do_shortcode( '[chartsbeds-review-bar key="'.$key.'"]' );
        echo $after_widget."</div>";
    }

    //Update the widget
    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;

        //Strip tags from title and name to remove HTML
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['show_info'] = $new_instance['show_info'];
		$instance['key'] = $new_instance['key'];

        return $instance;
    }
    function form( $instance ) {

        //Set up some default widget settings.
        $defaults = array( 'title' => __('Hotel name', 'wreviews'), 'name' => __('ChartsBeds', 'wreviews'), 'show_info' => true );
        $instance = wp_parse_args( (array) $instance, $defaults );

        /* Widget Title: Text Input.*/
        echo "<p>";
        echo '<label for="'.$this->get_field_id( 'title' ).'">';
        _e('Title:', 'wreviews');
        echo '</label>';
        echo '<input id="'.$this->get_field_id( 'title' ).'" name="'.$this->get_field_name( 'title' ).'" value="'.$instance['title'].'" style="width:100%;" />';
        echo '</p>';
		
		echo "<p>";
        echo '<label for="'.$this->get_field_id( 'key' ).'">';
        _e('Key:', 'wreviews');
        echo '</label>';
        echo '<input id="'.$this->get_field_id( 'key' ).'" name="'.$this->get_field_name( 'key' ).'" value="'.$instance['key'].'" style="width:100%;" />';
        echo '</p>';
    }
}