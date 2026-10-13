<?php
namespace PlaygroundBuilder\View;
/** Shared labels and defaults. The host provides $current_language. */

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

if ( ! in_array( $current_language, array_column( $languages, 'code' ), true ) ) {
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
	'blueprintJson' => __( 'Blueprint JSON', 'playground-builder' ),
	'shareableLink' => __( 'Shareable link', 'playground-builder' ),
	'authorPreview' => __( 'Demo in WordPress Playground', 'playground-builder' ),
	'linkModeBlueprint' => __( 'Blueprint', 'playground-builder' ),
	'linkModeQuery' => __( 'Query API', 'playground-builder' ),
	/* translators: %s: plugin last updated date. */
	'lastUpdated' => __( 'Updated %s', 'playground-builder' ),
	'blueprintRequired' => __( 'Blueprint required', 'playground-builder' ),
	'favoritesPlaceholder' => __( 'WordPress.org username', 'playground-builder' ),
	'favoritesHelp' => __( 'Enter a WordPress.org username to browse their favorite plugins and themes.', 'playground-builder' ),
	'noFavorites' => __( 'No favorites found for this WordPress.org username.', 'playground-builder' ),
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

