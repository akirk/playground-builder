<?php
/**
 * Plugin Name: Playground Builder
 * Description: A WordPress app powered by WpApp.
 * Version: 1.0.0
 * Author: Alex Kirk
 * Text Domain: playground-builder
 * Requires PHP: 7.4
 */

namespace PlaygroundBuilder;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

add_action( 'init', function() {
	load_plugin_textdomain( 'playground-builder', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
} );

add_action( 'plugins_loaded', function() {
    // See https://github.com/akirk/wp-app for documentation.
    $app = new \WpApp\WpApp( __DIR__ . '/templates', 'playground-builder', [
        // Access control
        // 'require_login'      => false,
        // 'require_capability' => 'read',

        // Masterbar
        // 'show_masterbar_for_anonymous' => false,
        // 'show_wp_logo'                 => true,
        // 'show_site_name'               => true,
        // 'show_dark_mode_toggle'        => false,
        // 'clear_admin_bar'              => false,
        // 'add_app_node'                 => false,

        // App identity
        // 'app_name'     => 'Playground Builder',
        // 'my_apps'      => true,
        'my_apps_icon' => plugins_url( 'assets/icon.png', __FILE__ ),
    ] );
    $app->init();
} );

register_activation_hook( __FILE__, function() {
    flush_rewrite_rules();
} );

register_deactivation_hook( __FILE__, function() {
    flush_rewrite_rules();
} );
