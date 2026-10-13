<?php
namespace PlaygroundBuilder\View;
/** Render the shared app as a standalone static site (no WordPress required). */

if ( PHP_SAPI !== 'cli' ) {
	exit( 'Run this script from the command line.' );
}

$root = dirname( __DIR__ );
$output = $root . '/build/static';
/** Build-time translation adapter; shared gettext calls remain extractable. */
function __( string $message, string $domain ): string {
	return $message;
}
$escape_html = static function( string $value ): string {
	return htmlspecialchars( $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8' );
};
$escape_attr = $escape_html;
$current_language = 'en_US';
require $root . '/includes/config.php';

ob_start();
?>
<!DOCTYPE html>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Playground Builder</title>
	<link rel="stylesheet" href="assets/playground-builder.css">
</head>
<body>
	<?php require $root . '/includes/builder.php'; ?>
	<script>window.playgroundBuilderConfig = <?php echo json_encode( $config, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT | JSON_THROW_ON_ERROR ); ?>;</script>
	<script src="assets/playground-builder.js"></script>
</body>
</html>
<?php
$html = ob_get_clean();

if ( ! is_dir( $output . '/assets' ) && ! mkdir( $output . '/assets', 0777, true ) ) {
	throw new \RuntimeException( 'Could not create the static output directory.' );
}

foreach ( [ 'playground-builder.css', 'playground-builder.js' ] as $asset ) {
	if ( ! copy( $root . '/assets/' . $asset, $output . '/assets/' . $asset ) ) {
		throw new \RuntimeException( 'Could not copy ' . $asset );
	}
}
if ( false === file_put_contents( $output . '/index.html', $html ) || false === file_put_contents( $output . '/.nojekyll', '' ) ) {
	throw new \RuntimeException( 'Could not write the static site.' );
}
echo "Built static site in {$output}\n";
