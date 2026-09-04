<?php
/**
 * Plugin Name: Playground Builder
 * Plugin URI: https://github.com/akirk/playground-builder
 * Description: Build shareable WordPress Playground links: pick plugins, themes, WordPress and PHP versions, a language and a start page, then copy the link.
 * Version: 1.0.0
 * Requires at least: 6.0
 * Tested up to: 7.1
 * Requires PHP: 7.4
 * Author: Alex Kirk
 * Author URI: https://alex.kirk.at/
 * License: GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: playground-builder
 *
 * @package Playground_Builder
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
