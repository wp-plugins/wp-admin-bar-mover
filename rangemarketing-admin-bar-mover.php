<?php
/**
 * Plugin Name: WP Admin Bar Mover by Range Marketing
 * Plugin URI: http://rangemarketing.com/wp-admin-bar-mover/
 * Description: Fix your headaches with the admin bar overlapping your fixed top nav or distorting your layout while logged in by moving your admin bar to different locations on your screen or making the admin bar transparent. Choose from multiple positioning features such as right side, left side and bottom positioning for your admin bar. You can also set the Wordpress admin bar to be transparent until hovered, giving you a better vision of the site overall. Get the benefits of a logged out session while logged in and making changes! Donate @ http://rangemarketing.com/donate
 * Version: 1.0
 * Author: Range Marketing, LLC
 * Author URI: http://www.rangemarketing.com
 * License: Restricted
 */
 
 
/* ADMIN FUNCTIONS */

// kill those unwanted pests
if ( !defined('ABSPATH') ) die('-1');

// create top level admin menu and initialize rangesettings
add_action( 'admin_menu', 'range_admin_bar_create_menu' );
add_action( 'admin_init', 'register_range_admin_bar_settings' );

function range_admin_bar_create_menu() {
    add_menu_page( 'WP Admin Bar Mover by Range Marketing', 'WP Admin Bar Mover', 'manage_options', 'range-marketing-admin-bar-mover', 'range_bar_page', plugins_url( '/assets/transparent-range-logo.png', __FILE__ ), 1001 ); 
}

function register_range_admin_bar_settings() {
  register_setting( 'range-admin-bar-settings', 'admin_bar_position', 'sanitize_text_field');
  register_setting( 'range-admin-bar-settings', 'admin_bar_hidden', 'sanitize_text_field');
}

/* IMPLEMENTATION FUNCTIONS */

function range_admin_bar_css() {
	if ( is_user_logged_in() ) {
		$position = esc_attr( get_option('admin_bar_position') );
		if ($position == 'bottom') {
			wp_enqueue_style( 'range-admin-bar', plugins_url( 'css/admin-bar-bottom.css', __FILE__ ) );
		} elseif ($position == 'right') {
			wp_enqueue_style( 'range-admin-bar', plugins_url( 'css/admin-bar-right.css', __FILE__ ) );
		} elseif ($position == 'left') {
			wp_enqueue_style( 'range-admin-bar', plugins_url( 'css/admin-bar-left.css', __FILE__ ) );
		} else {
			echo "";
		}
		
		$hidden = esc_attr( get_option('admin_bar_hidden') );
		if ($hidden == 'hidden') {
			wp_enqueue_style( 'range-admin-bar-hidden', plugins_url( 'css/admin-bar-hidden.css', __FILE__ ) );
		} else {
			echo "";
		}
	}
} 

add_action('wp_head','range_admin_bar_css');

/* ADMIN PAGE */

function range_bar_page() {
    // block user without permissions from viewing page
	if ( !current_user_can( 'manage_options' ) )  {
			wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
		}
	?>
	
<form method="post" action="options.php">
	
	<div class="wrap">
	<h1>WP Admin Bar Mover by Range Marketing</h1>
	<p><em>Use the convenient WP Admin Bar Mover <a href="http://rangemarketing.com" target="_blank">by Range Marketing</a> to fix your headaches with the admin bar overlapping your fixed top nav or distorting your layout while logged in by moving your admin bar to different locations on your screen. Choose from multiple positioning features such as right side, left side and bottom positioning for your admin bar. Get the benefits of a logged out session while logged in and making changes!</em></p>
	 
	 <select type="text" name="admin_bar_position">
	  <option value="top" <?php if ( esc_attr( get_option('admin_bar_position') == 'top' ) ) { ?> selected="selected" <?php } ?> >Top</option>
	  <option value="bottom" <?php if ( esc_attr( get_option('admin_bar_position') == 'bottom' ) ) { ?> selected="selected" <?php } ?> >Bottom</option>
	  <option value="right" <?php if ( esc_attr( get_option('admin_bar_position') == 'right' ) ) { ?> selected="selected" <?php } ?> >Right</option>
	  <option value="left" <?php if ( esc_attr( get_option('admin_bar_position') == 'left' ) ) { ?> selected="selected" <?php } ?> >Left</option>
	 </select>

	<p><em>Use the admin bar hider to make the admin bar transparent until hovered. Only enabled for Desktop users.</em></p>
	
	 <select type="text" name="admin_bar_hidden">
	  <option value="not" <?php if ( esc_attr( get_option('admin_bar_hidden') == 'not' ) ) { ?> selected="selected" <?php } ?> >Not Hidden</option>
	  <option value="hidden" <?php if ( esc_attr( get_option('admin_bar_hidden') == 'hidden' ) ) { ?> selected="selected" <?php } ?> >Hidden</option>
	 </select>
	 
	<?php 
		settings_fields( 'range-admin-bar-settings' );
		do_settings_sections( 'range-admin-bar-settings' );
		submit_button();
	 ?>
	 </form>
	 <hr />
	 <h3>Brought to you by:</h3>
	 <?php
	 	echo '<img src="' . plugins_url( '/assets/range-logo.png', __FILE__ ) . '" > ';
	 ?>
	 <br />
	 <p><strong>Support <a href="http://rangemarketing.com" target="_blank">Range Marketing</a> @ <a href="http://rangemarketing.com/donate/" target="_blank">http://rangemarketing.com/donate</a> if you like this plugin - or donate below!</strong></p>
	 <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank">
		<input type="hidden" name="cmd" value="_s-xclick">
		<input type="hidden" name="hosted_button_id" value="M2NM3EBXF8FS2">
		<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
		<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
	 </form>

	 <?php } ?>