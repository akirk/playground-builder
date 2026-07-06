<?php
$asset_url = static function( string $path ): string {
	$relative_path = 'assets/' . ltrim( $path, '/' );
	$file_path = dirname( __DIR__ ) . '/' . $relative_path;
	$url = plugins_url( $relative_path, dirname( __DIR__ ) . '/playground-builder.php' );

	if ( file_exists( $file_path ) ) {
		$url = add_query_arg( 'ver', filemtime( $file_path ), $url );
	}

	return $url;
};

add_filter(
	'pre_get_document_title',
	static function(): string {
		return __( 'Playground Builder', 'playground-builder' );
	}
);

$languages = [
	[ 'code' => 'en_US', 'label' => __( 'English (US)', 'playground-builder' ) ],
	[ 'code' => 'en_GB', 'label' => __( 'English (UK)', 'playground-builder' ) ],
	[ 'code' => 'de_DE', 'label' => __( 'German', 'playground-builder' ) ],
	[ 'code' => 'es_ES', 'label' => __( 'Spanish', 'playground-builder' ) ],
	[ 'code' => 'fr_FR', 'label' => __( 'French', 'playground-builder' ) ],
	[ 'code' => 'it_IT', 'label' => __( 'Italian', 'playground-builder' ) ],
	[ 'code' => 'nl_NL', 'label' => __( 'Dutch', 'playground-builder' ) ],
	[ 'code' => 'pt_BR', 'label' => __( 'Portuguese (Brazil)', 'playground-builder' ) ],
	[ 'code' => 'pt_PT', 'label' => __( 'Portuguese', 'playground-builder' ) ],
	[ 'code' => 'ru_RU', 'label' => __( 'Russian', 'playground-builder' ) ],
	[ 'code' => 'ja', 'label' => __( 'Japanese', 'playground-builder' ) ],
	[ 'code' => 'zh_CN', 'label' => __( 'Chinese (China)', 'playground-builder' ) ],
	[ 'code' => 'zh_TW', 'label' => __( 'Chinese (Taiwan)', 'playground-builder' ) ],
	[ 'code' => 'ko_KR', 'label' => __( 'Korean', 'playground-builder' ) ],
	[ 'code' => 'ar', 'label' => __( 'Arabic', 'playground-builder' ) ],
	[ 'code' => 'he_IL', 'label' => __( 'Hebrew', 'playground-builder' ) ],
	[ 'code' => 'hi_IN', 'label' => __( 'Hindi', 'playground-builder' ) ],
	[ 'code' => 'pl_PL', 'label' => __( 'Polish', 'playground-builder' ) ],
	[ 'code' => 'sv_SE', 'label' => __( 'Swedish', 'playground-builder' ) ],
	[ 'code' => 'da_DK', 'label' => __( 'Danish', 'playground-builder' ) ],
	[ 'code' => 'nb_NO', 'label' => __( 'Norwegian', 'playground-builder' ) ],
	[ 'code' => 'fi', 'label' => __( 'Finnish', 'playground-builder' ) ],
	[ 'code' => 'cs_CZ', 'label' => __( 'Czech', 'playground-builder' ) ],
	[ 'code' => 'el', 'label' => __( 'Greek', 'playground-builder' ) ],
	[ 'code' => 'tr_TR', 'label' => __( 'Turkish', 'playground-builder' ) ],
	[ 'code' => 'uk', 'label' => __( 'Ukrainian', 'playground-builder' ) ],
	[ 'code' => 'id_ID', 'label' => __( 'Indonesian', 'playground-builder' ) ],
];

$current_language = get_locale();

if ( ! in_array( $current_language, wp_list_pluck( $languages, 'code' ), true ) ) {
	array_unshift(
		$languages,
		[
			'code' => $current_language,
			'label' => $current_language,
		]
	);
}

$landings = [
	[ 'value' => '/wp-admin/', 'label' => __( 'Dashboard (wp-admin)', 'playground-builder' ) ],
	[ 'value' => '/wp-admin/site-editor.php', 'label' => __( 'Site Editor', 'playground-builder' ) ],
	[ 'value' => '/', 'label' => __( 'Homepage', 'playground-builder' ) ],
	[ 'value' => 'custom', 'label' => __( 'Custom URL', 'playground-builder' ) ],
];

$strings = [
	/* translators: %s: number of active installs. */
	'activeInstalls' => __( '%s active installs', 'playground-builder' ),
	/* translators: %s: number of active installs in thousands. */
	'activeInstallsThousands' => __( '%sK+ active installs', 'playground-builder' ),
	/* translators: %s: number of active installs in millions. */
	'activeInstallsMillions' => __( '%sM+ active installs', 'playground-builder' ),
	'add' => __( 'Add', 'playground-builder' ),
	'added' => __( 'Added', 'playground-builder' ),
	/* translators: %s: plugin or theme author name. */
	'byAuthor' => __( 'by %s', 'playground-builder' ),
	'connectionError' => __( 'Could not reach wordpress.org. Check your connection and try again.', 'playground-builder' ),
	'copied' => __( 'Copied to clipboard', 'playground-builder' ),
	'copyLink' => __( 'Copy link', 'playground-builder' ),
	'customLandingPlaceholder' => __( '/sample-page/', 'playground-builder' ),
	'customGitRepository' => __( 'Git repository', 'playground-builder' ),
	'customZipFile' => __( 'ZIP file URL', 'playground-builder' ),
	'empty' => __( 'empty', 'playground-builder' ),
	'featuredInfo' => __( 'Featured plugins highlight rotating hidden gems from WordPress.org and refresh every two weeks.', 'playground-builder' ),
	'featuredInfoLink' => __( 'Learn more', 'playground-builder' ),
	'hideJson' => __( 'Hide blueprint JSON', 'playground-builder' ),
	'authorPreview' => __( 'Demo in WordPress Playground', 'playground-builder' ),
	'linkModeBlueprint' => __( 'Blueprint', 'playground-builder' ),
	'linkModeQuery' => __( 'Query API', 'playground-builder' ),
	/* translators: %s: plugin last updated date. */
	'lastUpdated' => __( 'Updated %s', 'playground-builder' ),
	'blueprintRequired' => __( 'Blueprint required', 'playground-builder' ),
	'noMatches' => __( 'No matches. Try another search term.', 'playground-builder' ),
	'searchReady' => __( 'Press Search to look up this term.', 'playground-builder' ),
	'playgroundPreview' => __( 'Preview in WordPress Playground', 'playground-builder' ),
	'plugin' => __( 'Plugin', 'playground-builder' ),
	/* translators: %d: number of selected plugins. */
	'pluginCountSingular' => __( '%d plugin', 'playground-builder' ),
	/* translators: %d: number of selected plugins. */
	'pluginCountPlural' => __( '%d plugins', 'playground-builder' ),
	/* translators: %s: plugin or theme name. */
	'removeItem' => __( 'Remove %s', 'playground-builder' ),
	'searching' => __( 'Searching wordpress.org...', 'playground-builder' ),
	'searchPluginsPlaceholder' => __( 'Search plugins or paste a GitHub/ZIP URL', 'playground-builder' ),
	'searchThemesPlaceholder' => __( 'Search themes, for example astra or blocksy', 'playground-builder' ),
	/* translators: 1: selected plugin count, 2: selected theme count. */
	'selectionSummary' => __( '%1$s - %2$s', 'playground-builder' ),
	/* translators: %d: number of blueprint steps. */
	'stepCountSingular' => __( '%d step', 'playground-builder' ),
	/* translators: %d: number of blueprint steps. */
	'stepCountPlural' => __( '%d steps', 'playground-builder' ),
	'theme' => __( 'Theme', 'playground-builder' ),
	'themePreview' => __( 'Preview theme', 'playground-builder' ),
	/* translators: %d: number of selected themes. */
	'themeCountSingular' => __( '%d theme', 'playground-builder' ),
	/* translators: %d: number of selected themes. */
	'themeCountPlural' => __( '%d themes', 'playground-builder' ),
	'useTheme' => __( 'Use', 'playground-builder' ),
	'usingTheme' => __( 'Using', 'playground-builder' ),
	'viewJson' => __( 'View blueprint JSON', 'playground-builder' ),
	'wordpressOrg' => __( 'wordpress.org', 'playground-builder' ),
	'defaultThemeKind' => __( 'Default theme', 'playground-builder' ),
	'defaultThemeSummary' => __( 'default theme', 'playground-builder' ),
	'selectedThemeSummary' => __( 'selected theme', 'playground-builder' ),
	'switchToBlueprint' => __( 'Switch to blueprint', 'playground-builder' ),
	'switchToQueryApi' => __( 'Switch to Query API', 'playground-builder' ),
];

$config = [
	'defaultTab' => 'plugins',
	'defaultLanguage' => $current_language,
	'defaultWpVersion' => 'latest',
	'defaultPhpVersion' => 'latest',
	'defaultLandingPage' => '/',
	'defaultThemes' => [
		'latest' => [ 'slug' => 'twentytwentyfive', 'name' => __( 'Twenty Twenty-Five', 'playground-builder' ) ],
		'7.0' => [ 'slug' => 'twentytwentyfive', 'name' => __( 'Twenty Twenty-Five', 'playground-builder' ) ],
		'6.9' => [ 'slug' => 'twentytwentyfive', 'name' => __( 'Twenty Twenty-Five', 'playground-builder' ) ],
		'6.8' => [ 'slug' => 'twentytwentyfive', 'name' => __( 'Twenty Twenty-Five', 'playground-builder' ) ],
		'6.7' => [ 'slug' => 'twentytwentyfive', 'name' => __( 'Twenty Twenty-Five', 'playground-builder' ) ],
		'6.6' => [ 'slug' => 'twentytwentyfour', 'name' => __( 'Twenty Twenty-Four', 'playground-builder' ) ],
		'6.5' => [ 'slug' => 'twentytwentyfour', 'name' => __( 'Twenty Twenty-Four', 'playground-builder' ) ],
		'6.4' => [ 'slug' => 'twentytwentyfour', 'name' => __( 'Twenty Twenty-Four', 'playground-builder' ) ],
		'6.3' => [ 'slug' => 'twentytwentythree', 'name' => __( 'Twenty Twenty-Three', 'playground-builder' ) ],
		'6.2' => [ 'slug' => 'twentytwentythree', 'name' => __( 'Twenty Twenty-Three', 'playground-builder' ) ],
		'nightly' => [ 'slug' => 'twentytwentyfive', 'name' => __( 'Twenty Twenty-Five', 'playground-builder' ) ],
		'beta' => [ 'slug' => 'twentytwentyfive', 'name' => __( 'Twenty Twenty-Five', 'playground-builder' ) ],
	],
	'strings' => $strings,
	'fallback' => [
		'plugins' => [
			[ 'slug' => 'woocommerce', 'name' => __( 'WooCommerce', 'playground-builder' ), 'desc' => __( 'The most popular open-source eCommerce platform.', 'playground-builder' ) ],
			[ 'slug' => 'elementor', 'name' => __( 'Elementor Website Builder', 'playground-builder' ), 'desc' => __( 'Drag-and-drop page builder.', 'playground-builder' ) ],
			[ 'slug' => 'contact-form-7', 'name' => __( 'Contact Form 7', 'playground-builder' ), 'desc' => __( 'Manage multiple contact forms with ease.', 'playground-builder' ) ],
			[ 'slug' => 'wordpress-seo', 'name' => __( 'Yoast SEO', 'playground-builder' ), 'desc' => __( 'Improve your WordPress SEO.', 'playground-builder' ) ],
			[ 'slug' => 'akismet', 'name' => __( 'Akismet Anti-Spam', 'playground-builder' ), 'desc' => __( 'Filter out spam comments.', 'playground-builder' ) ],
			[ 'slug' => 'jetpack', 'name' => __( 'Jetpack', 'playground-builder' ), 'desc' => __( 'Security, performance, and growth tools.', 'playground-builder' ) ],
			[ 'slug' => 'wordfence', 'name' => __( 'Wordfence Security', 'playground-builder' ), 'desc' => __( 'Firewall and malware scanner.', 'playground-builder' ) ],
			[ 'slug' => 'gutenberg', 'name' => __( 'Gutenberg', 'playground-builder' ), 'desc' => __( 'The block editor plugin.', 'playground-builder' ) ],
			[ 'slug' => 'classic-editor', 'name' => __( 'Classic Editor', 'playground-builder' ), 'desc' => __( 'Restores the classic WordPress editor.', 'playground-builder' ) ],
		],
		'themes' => [
			[ 'slug' => 'twentytwentyfive', 'name' => __( 'Twenty Twenty-Five', 'playground-builder' ), 'desc' => __( 'The 2025 default theme.', 'playground-builder' ) ],
			[ 'slug' => 'twentytwentyfour', 'name' => __( 'Twenty Twenty-Four', 'playground-builder' ), 'desc' => __( 'The 2024 default theme.', 'playground-builder' ) ],
			[ 'slug' => 'twentytwentythree', 'name' => __( 'Twenty Twenty-Three', 'playground-builder' ), 'desc' => __( 'The 2023 default theme.', 'playground-builder' ) ],
			[ 'slug' => 'astra', 'name' => __( 'Astra', 'playground-builder' ), 'desc' => __( 'Fast, lightweight, customizable theme.', 'playground-builder' ) ],
			[ 'slug' => 'hello-elementor', 'name' => __( 'Hello Elementor', 'playground-builder' ), 'desc' => __( 'A plain and lightweight theme.', 'playground-builder' ) ],
			[ 'slug' => 'oceanwp', 'name' => __( 'OceanWP', 'playground-builder' ), 'desc' => __( 'A multipurpose responsive theme.', 'playground-builder' ) ],
			[ 'slug' => 'neve', 'name' => __( 'Neve', 'playground-builder' ), 'desc' => __( 'A super fast, easily customizable theme.', 'playground-builder' ) ],
		],
	],
];
?>
<!DOCTYPE html>
<html <?php wp_app_language_attributes(); ?>>
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="<?php echo esc_url( $asset_url( 'playground-builder.css' ) ); ?>">
	<?php wp_app_head(); ?>
</head>
<body>
	<?php wp_app_body_open(); ?>

	<main class="playground-builder" data-playground-builder>
		<script type="application/json" id="playground-builder-config"><?php echo wp_json_encode( $config, JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT ); ?></script>

		<div class="playground-builder__inner">
			<header class="playground-builder__header">
				<div class="playground-builder__mark" aria-hidden="true"></div>
				<div class="playground-builder__title-wrap">
					<h1><?php echo esc_html__( 'Playground Builder', 'playground-builder' ); ?></h1>
					<p class="playground-builder__subtitle">
						<?php
						printf(
							/* translators: %s: WordPress Playground documentation link. */
							esc_html__( 'Compose a %s blueprint and share it as one link.', 'playground-builder' ),
							'<a href="https://wordpress.github.io/wordpress-playground/" target="_blank" rel="noreferrer">WordPress Playground</a>'
						);
						?>
					</p>
				</div>
				<a class="playground-builder__external" href="https://akirk.github.io/playground-step-library/" target="_blank" rel="noreferrer">
					<?php echo esc_html__( 'Create more advanced blueprints', 'playground-builder' ); ?>
				</a>
			</header>

			<div class="playground-builder__layout">
				<section class="playground-builder__panel" aria-labelledby="playground-builder-browse-title">
					<h2 id="playground-builder-browse-title" class="screen-reader-text"><?php echo esc_html__( 'Browse plugins and themes', 'playground-builder' ); ?></h2>
					<div class="playground-builder__search-head">
						<div class="playground-builder__tabs" role="tablist" aria-label="<?php echo esc_attr__( 'Directory type', 'playground-builder' ); ?>">
							<button class="playground-builder__tab" type="button" role="tab" aria-selected="true" data-tab="plugins"><?php echo esc_html__( 'Plugins', 'playground-builder' ); ?></button>
							<button class="playground-builder__tab" type="button" role="tab" aria-selected="false" data-tab="themes"><?php echo esc_html__( 'Themes', 'playground-builder' ); ?></button>
						</div>

						<form class="playground-builder__search-form" data-search-form>
							<label class="screen-reader-text" for="playground-builder-query"><?php echo esc_html__( 'Search wordpress.org', 'playground-builder' ); ?></label>
							<input id="playground-builder-query" type="search" data-query>
							<button class="playground-builder__primary" type="submit"><?php echo esc_html__( 'Search', 'playground-builder' ); ?></button>
						</form>
						<p class="playground-builder__featured-note" data-featured-info hidden>
							<?php echo esc_html__( 'Featured plugins highlight rotating hidden gems from WordPress.org and refresh every two weeks.', 'playground-builder' ); ?>
							<a href="https://wordpress.slack.com/archives/C1LBM36LC/p1772211796347839" target="_blank" rel="noreferrer"><?php echo esc_html__( 'Discuss in Make WordPress Slack', 'playground-builder' ); ?></a>
						</p>
					</div>

					<div class="playground-builder__results playground-builder__scroll" data-results aria-live="polite"></div>
				</section>

				<aside class="playground-builder__panel playground-builder__build" aria-labelledby="playground-builder-build-title">
					<div class="playground-builder__build-head">
						<h2 id="playground-builder-build-title"><?php echo esc_html__( 'Your build', 'playground-builder' ); ?></h2>
						<span class="playground-builder__summary" data-selection-summary><?php echo esc_html__( 'empty', 'playground-builder' ); ?></span>
					</div>

					<div class="playground-builder__selection playground-builder__scroll" data-selected></div>

					<div class="playground-builder__config">
						<label class="playground-builder__field">
							<span class="playground-builder__label"><?php echo esc_html__( 'Site language', 'playground-builder' ); ?></span>
							<select data-language>
								<?php foreach ( $languages as $language ) : ?>
									<option value="<?php echo esc_attr( $language['code'] ); ?>" <?php selected( $language['code'], $current_language ); ?>><?php echo esc_html( $language['label'] ); ?></option>
								<?php endforeach; ?>
							</select>
						</label>

						<div class="playground-builder__field-row">
							<label class="playground-builder__field">
								<span class="playground-builder__label"><?php echo esc_html__( 'WordPress', 'playground-builder' ); ?></span>
								<select data-wp-version>
									<?php foreach ( [ 'latest', '7.0', '6.9', '6.8', '6.7', '6.6', '6.5', '6.4', '6.3', '6.2', 'nightly', 'beta' ] as $version ) : ?>
										<option value="<?php echo esc_attr( $version ); ?>"><?php echo esc_html( $version ); ?></option>
									<?php endforeach; ?>
								</select>
							</label>

							<label class="playground-builder__field">
								<span class="playground-builder__label"><?php echo esc_html__( 'PHP', 'playground-builder' ); ?></span>
								<select data-php-version>
									<?php foreach ( [ 'latest', '8.3', '8.2', '8.1', '8.0', '7.4' ] as $version ) : ?>
										<option value="<?php echo esc_attr( $version ); ?>"><?php echo esc_html( $version ); ?></option>
									<?php endforeach; ?>
								</select>
							</label>
						</div>

						<label class="playground-builder__field">
							<span class="playground-builder__label"><?php echo esc_html__( 'Start page', 'playground-builder' ); ?></span>
							<select data-landing-page>
								<?php foreach ( $landings as $landing ) : ?>
									<option value="<?php echo esc_attr( $landing['value'] ); ?>"><?php echo esc_html( $landing['label'] ); ?></option>
								<?php endforeach; ?>
							</select>
							<input type="text" value="/" placeholder="<?php echo esc_attr__( '/sample-page/', 'playground-builder' ); ?>" data-custom-landing hidden>
						</label>
					</div>

					<div class="playground-builder__output">
						<div class="playground-builder__output-head">
							<span class="playground-builder__output-label"><?php echo esc_html__( 'Shareable link', 'playground-builder' ); ?></span>
							<span class="playground-builder__caption" data-build-summary></span>
						</div>
						<div class="playground-builder__link playground-builder__scroll" data-link></div>
						<div class="playground-builder__actions">
							<button class="playground-builder__copy" type="button" data-copy><?php echo esc_html__( 'Copy link', 'playground-builder' ); ?></button>
							<a class="playground-builder__open" href="https://playground.wordpress.net/" target="_blank" rel="noreferrer" data-open><?php echo esc_html__( 'Open', 'playground-builder' ); ?></a>
						</div>
						<button class="playground-builder__json-toggle" type="button" data-json-toggle><?php echo esc_html__( 'View blueprint JSON', 'playground-builder' ); ?></button>
						<pre class="playground-builder__json playground-builder__scroll" data-json></pre>
					</div>
				</aside>
			</div>
		</div>
	</main>

	<script src="<?php echo esc_url( $asset_url( 'playground-builder.js' ) ); ?>" defer></script>
	<?php wp_app_body_close(); ?>
</body>
</html>
