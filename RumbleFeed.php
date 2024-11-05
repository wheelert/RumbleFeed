<?php
/*
Plugin Name:  Rumble Feed Plugin 
Plugin URI:   https://www.wheelerwire.com/RumbleFeed
Description:  This plugin pulls your rumble feed for display on your website
Version:      1.0
Author:       WheelerWire
Author URI:   https://www.wheelerwire.com
License:      GPL2
License URI:  https://www.gnu.org/licenses/gpl-2.0.html
Text Domain:  ww-rumblefeed
Domain Path:  /languages
*/

require_once(plugin_dir_path(__FILE__) . 'includes/scripts.php');
add_action( 'rf_pull_feed_hook', 'rf_pull_feed' );

function ww_rumblefeed($content){

    // Return the content
    return $content; 

}

function ww_rumblefeed_on_activation(){
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    check_admin_referer( "activate-plugin_{$plugin}" );



    if (! wp_next_scheduled ( 'rf_pull_feed_hook')) {
    	wp_schedule_event( time(), 'hourly', 'rf_pull_feed_hook');
    }

}

function ww_rumblefeed_on_deactivation(){
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    check_admin_referer( "deactivate-plugin_{$plugin}" );

        wp_clear_scheduled_hook( 'rf_pull_feed_hook' );
}

function ww_rumblefeed_on_uninstall(){
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
    check_admin_referer( 'bulk-plugins' );

    // Important: Check if the file is the one
    // that was registered during the uninstall hook.
    if ( __FILE__ != WP_UNINSTALL_PLUGIN )
        return;

    $timestamp = wp_next_scheduled( 'rf_pull_feed_hook' );
    wp_unschedule_event( $timestamp, 'rf_pull_feed_hook' );
}

register_activation_hook(   __FILE__, 'ww_rumblefeed_on_activation' );
register_deactivation_hook( __FILE__, 'ww_rumblefeed_on_deactivation' );
register_uninstall_hook(    __FILE__, 'ww_rumblefeed_on_uninstall' );






?>