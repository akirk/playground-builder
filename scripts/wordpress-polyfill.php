<?php
/** Minimal WordPress/WpApp API used to render the app during a static build. */

if ( PHP_SAPI !== 'cli' ) {
	exit( 'This polyfill is only available to command-line builds.' );
}

// This file is loaded only by the static builder, never by the plugin.
define( 'ABSPATH', dirname( __DIR__ ) . '/' );

$GLOBALS['playground_builder_static'] = [
	'styles' => [],
	'scripts' => [],
	'config' => [],
	'title' => 'Playground Builder',
];

function __( $message, $domain ) {
	return $message;
}

function esc_html( $value ) {
	return htmlspecialchars( (string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' );
}

function esc_attr( $value ) {
	return esc_html( $value );
}

function esc_html__( $message, $domain ) {
	return esc_html( __( $message, $domain ) );
}

function esc_attr__( $message, $domain ) {
	return esc_attr( __( $message, $domain ) );
}

function get_locale() {
	return 'en_US';
}

function wp_list_pluck( $items, $field ) {
	return array_column( $items, $field );
}

function selected( $value, $current, $echo = true ) {
	$result = (string) $value === (string) $current ? 'selected="selected"' : '';
	if ( $echo ) {
		echo $result;
	}
	return $result;
}

function plugins_url( $path, $plugin ) {
	return $path;
}

function wp_enqueue_style( $handle, $url, $dependencies = [], $version = false ) {
	$GLOBALS['playground_builder_static']['styles'][ $handle ] = $url;
}

function wp_enqueue_script( $handle, $url, $dependencies = [], $version = false, $footer = false ) {
	$GLOBALS['playground_builder_static']['scripts'][ $handle ] = $url;
}

function wp_localize_script( $handle, $name, $data ) {
	$GLOBALS['playground_builder_static']['config'][ $handle ][ $name ] = $data;
}

function add_filter( $hook, $callback ) {
	if ( 'pre_get_document_title' !== $hook ) {
		throw new RuntimeException( 'Unsupported static build filter: ' . $hook );
	}
	$GLOBALS['playground_builder_static']['title'] = $callback();
}

function wp_app_language_attributes() {
	echo 'lang="' . esc_attr( str_replace( '_', '-', get_locale() ) ) . '"';
}

function wp_app_head() {
	echo '<title>' . esc_html( $GLOBALS['playground_builder_static']['title'] ) . "</title>\n";
	foreach ( $GLOBALS['playground_builder_static']['styles'] as $url ) {
		echo '<link rel="stylesheet" href="' . esc_attr( $url ) . "\">\n";
	}
}

function wp_app_body_open() {}

function wp_app_body_close() {
	foreach ( $GLOBALS['playground_builder_static']['scripts'] as $handle => $url ) {
		foreach ( $GLOBALS['playground_builder_static']['config'][ $handle ] ?? [] as $name => $data ) {
			if ( ! preg_match( '/^[a-zA-Z_$][a-zA-Z0-9_$]*$/', $name ) ) {
				throw new RuntimeException( 'Invalid script configuration name: ' . $name );
			}
			echo '<script>window.' . $name . ' = ' . json_encode( $data, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR ) . ";</script>\n";
		}
		echo '<script src="' . esc_attr( $url ) . "\"></script>\n";
	}
}
