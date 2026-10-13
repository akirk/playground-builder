<?php
namespace PlaygroundBuilder\View;
/** Shared app markup. The host provides escaping callbacks; WordPress supplies translations. */
?>
	<main class="playground-builder" data-playground-builder>
		<div class="playground-builder__inner">
			<header class="playground-builder__header">
				<div class="playground-builder__mark" aria-hidden="true"></div>
				<div class="playground-builder__title-wrap">
					<h1><?php echo $escape_html( __( 'Playground Builder', 'playground-builder' ) ); ?></h1>
					<p class="playground-builder__subtitle">
						<?php
						printf(
							/* translators: %s: WordPress Playground documentation link. */
							$escape_html( __( 'Compose a %s blueprint and share it as one link.', 'playground-builder' ) ),
							'<a href="https://wordpress.github.io/wordpress-playground/" target="_blank" rel="noreferrer">WordPress Playground</a>'
						);
						?>
					</p>
				</div>
				<a class="playground-builder__external" data-advanced-blueprint href="https://akirk.github.io/playground-step-library/" target="_blank" rel="noreferrer">
					<?php echo $escape_html( __( 'Create more advanced blueprints', 'playground-builder' ) ); ?>
				</a>
			</header>

			<div class="playground-builder__layout">
				<section class="playground-builder__panel" aria-labelledby="playground-builder-browse-title">
					<h2 id="playground-builder-browse-title" class="screen-reader-text"><?php echo $escape_html( __( 'Browse plugins and themes', 'playground-builder' ) ); ?></h2>
					<div class="playground-builder__search-head">
						<div class="playground-builder__tabs" role="tablist" aria-label="<?php echo $escape_attr( __( 'Directory type', 'playground-builder' ) ); ?>">
							<button class="playground-builder__tab" type="button" role="tab" aria-selected="true" data-tab="plugins"><?php echo $escape_html( __( 'Plugins', 'playground-builder' ) ); ?></button>
							<button class="playground-builder__tab" type="button" role="tab" aria-selected="false" data-tab="themes"><?php echo $escape_html( __( 'Themes', 'playground-builder' ) ); ?></button>
							<button class="playground-builder__tab" type="button" role="tab" aria-selected="false" data-tab="favorites"><?php echo $escape_html( __( 'Favorites', 'playground-builder' ) ); ?></button>
						</div>

						<form class="playground-builder__search-form" data-search-form>
							<label class="screen-reader-text" for="playground-builder-query"><?php echo $escape_html( __( 'Search wordpress.org', 'playground-builder' ) ); ?></label>
							<input id="playground-builder-query" type="search" aria-describedby="playground-builder-favorites-help" data-query>
							<button class="playground-builder__primary" type="submit"><?php echo $escape_html( __( 'Search', 'playground-builder' ) ); ?></button>
						</form>
						<p id="playground-builder-favorites-help" class="playground-builder__featured-note" data-favorites-help hidden><?php echo $escape_html( __( 'Enter a WordPress.org username to browse their favorite plugins and themes.', 'playground-builder' ) ); ?></p>
						<p class="playground-builder__featured-note" data-featured-info hidden>
							<?php echo $escape_html( __( 'Featured plugins highlight rotating hidden gems from WordPress.org and refresh every two weeks.', 'playground-builder' ) ); ?>
							<a href="https://wordpress.slack.com/archives/C1LBM36LC/p1772211796347839" target="_blank" rel="noreferrer"><?php echo $escape_html( __( 'Discuss in Make WordPress Slack', 'playground-builder' ) ); ?></a>
						</p>
					</div>

					<div class="playground-builder__results playground-builder__scroll" data-results aria-live="polite"></div>
				</section>

				<aside class="playground-builder__panel playground-builder__build" aria-labelledby="playground-builder-build-title">
					<div class="playground-builder__build-head">
						<h2 id="playground-builder-build-title"><?php echo $escape_html( __( 'Your build', 'playground-builder' ) ); ?></h2>
						<span class="playground-builder__summary" data-selection-summary><?php echo $escape_html( __( 'empty', 'playground-builder' ) ); ?></span>
					</div>

					<div class="playground-builder__selection playground-builder__scroll" data-selected></div>

					<div class="playground-builder__config">
						<label class="playground-builder__field">
							<span class="playground-builder__label"><?php echo $escape_html( __( 'Site language', 'playground-builder' ) ); ?></span>
							<select data-language>
								<?php foreach ( $languages as $language ) : ?>
									<option value="<?php echo $escape_attr( $language['code'] ); ?>" <?php echo $language['code'] === $current_language ? 'selected="selected"' : ''; ?>><?php echo $escape_html( $language['label'] ); ?></option>
								<?php endforeach; ?>
							</select>
						</label>

						<div class="playground-builder__field-row">
							<label class="playground-builder__field">
								<span class="playground-builder__label"><?php echo $escape_html( __( 'WordPress', 'playground-builder' ) ); ?></span>
								<select data-wp-version>
									<?php foreach ( [ 'latest', '7.0', '6.9', '6.8', '6.7', '6.6', '6.5', '6.4', '6.3', '6.2', 'nightly', 'beta' ] as $version ) : ?>
										<option value="<?php echo $escape_attr( $version ); ?>"><?php echo $escape_html( $version ); ?></option>
									<?php endforeach; ?>
								</select>
							</label>

							<label class="playground-builder__field">
								<span class="playground-builder__label"><?php echo $escape_html( __( 'PHP', 'playground-builder' ) ); ?></span>
								<select data-php-version>
									<?php foreach ( [ 'latest', '8.3', '8.2', '8.1', '8.0', '7.4' ] as $version ) : ?>
										<option value="<?php echo $escape_attr( $version ); ?>"><?php echo $escape_html( $version ); ?></option>
									<?php endforeach; ?>
								</select>
							</label>
						</div>

						<label class="playground-builder__field">
							<span class="playground-builder__label"><?php echo $escape_html( __( 'Start page', 'playground-builder' ) ); ?></span>
							<select data-landing-page>
								<?php foreach ( $landings as $landing ) : ?>
									<option value="<?php echo $escape_attr( $landing['value'] ); ?>"><?php echo $escape_html( $landing['label'] ); ?></option>
								<?php endforeach; ?>
							</select>
							<input type="text" value="/" placeholder="<?php echo $escape_attr( __( '/sample-page/', 'playground-builder' ) ); ?>" data-custom-landing hidden>
						</label>
					</div>

					<div class="playground-builder__output">
						<div class="playground-builder__output-head">
							<span class="playground-builder__output-label" data-output-label><?php echo $escape_html( __( 'Shareable link', 'playground-builder' ) ); ?></span>
							<span class="playground-builder__caption" data-build-summary></span>
						</div>
						<div class="playground-builder__link playground-builder__scroll" data-link></div>
						<div class="playground-builder__actions">
							<button class="playground-builder__copy" type="button" data-copy><?php echo $escape_html( __( 'Copy link', 'playground-builder' ) ); ?></button>
							<a class="playground-builder__open" href="https://playground.wordpress.net/" target="_blank" rel="noreferrer" data-open><?php echo $escape_html( __( 'Open', 'playground-builder' ) ); ?></a>
						</div>
					</div>
				</aside>
			</div>
		</div>
	</main>

