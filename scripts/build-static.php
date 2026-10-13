<?php
/** Render the WordPress template as static HTML using a build-time polyfill. */

if ( PHP_SAPI !== 'cli' ) {
	exit( 'Run this script from the command line.' );
}

$root = dirname( __DIR__ );
$output = $root . '/build/static';
require __DIR__ . '/wordpress-polyfill.php';

ob_start();
require $root . '/templates/index.php';
$html = ob_get_clean();

if ( ! is_dir( $output . '/assets' ) && ! mkdir( $output . '/assets', 0777, true ) ) {
	throw new RuntimeException( 'Could not create the static output directory.' );
}

foreach ( [ 'playground-builder.css', 'playground-builder.js' ] as $asset ) {
	if ( ! copy( $root . '/assets/' . $asset, $output . '/assets/' . $asset ) ) {
		throw new RuntimeException( 'Could not copy ' . $asset );
	}
}
if ( false === file_put_contents( $output . '/index.html', $html ) || false === file_put_contents( $output . '/.nojekyll', '' ) ) {
	throw new RuntimeException( 'Could not write the static site.' );
}
echo "Built static site in {$output}\n";
