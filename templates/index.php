<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Cache-bust with the file modification time so app assets refresh on update.
wp_enqueue_style(
	'playground-builder',
	plugins_url( 'assets/playground-builder.css', dirname( __DIR__ ) . '/playground-builder.php' ),
	[],
	file_exists( dirname( __DIR__ ) . '/assets/playground-builder.css' ) ? (string) filemtime( dirname( __DIR__ ) . '/assets/playground-builder.css' ) : false
);

wp_enqueue_script(
	'playground-builder',
	plugins_url( 'assets/playground-builder.js', dirname( __DIR__ ) . '/playground-builder.php' ),
	[],
	file_exists( dirname( __DIR__ ) . '/assets/playground-builder.js' ) ? (string) filemtime( dirname( __DIR__ ) . '/assets/playground-builder.js' ) : false,
	true
);

add_filter(
	'pre_get_document_title',
	static function(): string {
		return __( 'Playground Builder', 'playground-builder' );
	}
);

$escape_html = 'esc_html';
$escape_attr = 'esc_attr';
$current_language = get_locale();
require dirname( __DIR__ ) . '/includes/config.php';

wp_localize_script( 'playground-builder', 'playgroundBuilderConfig', $config );
?>
<!DOCTYPE html>
<html <?php wp_app_language_attributes(); ?>>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<?php wp_app_head(); ?>
</head>
<body>
	<?php wp_app_body_open(); ?>
	<?php require dirname( __DIR__ ) . '/includes/builder.php'; ?>
	<?php wp_app_body_close(); ?>
</body>
</html>
