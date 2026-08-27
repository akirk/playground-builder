# Playground Builder

Contributors: akirk
Tags: playground, blueprints, plugins, themes, developer-tools
Requires at least: 6.0
Tested up to: 7.1
Stable tag: 1.0.0
Requires PHP: 7.4
License: GPL-2.0-or-later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Compose WordPress Playground blueprints from plugins, themes, versions, language settings, and launch URLs.

[Try it in WordPress Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/akirk/playground-builder/main/blueprint.json)

[Try it in OpenStation](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/akirk/playground-builder/main/blueprint-openstation.json) — the same app opened in desktop mode with the [OpenStation](https://github.com/WordPress/openstation) plugin.

## Description

Playground Builder is a WordPress app for creating shareable WordPress Playground links. It helps you build a Playground configuration by searching WordPress.org plugins and themes, choosing runtime settings, and copying either a Query API link or a full blueprint link.

The app is powered by the [WpApp framework](https://github.com/akirk/wp-app), so it lives at `/playground-builder/` on your site, separate from your theme, while still using WordPress routing, translations, and plugin loading.

### Features

- Search WordPress.org plugins and themes.
- Add multiple plugins and one active theme to a Playground build.
- Use GitHub, GitLab, or ZIP URLs for custom plugins and themes.
- Choose WordPress version, PHP version, site language, and landing page.
- Generate WordPress Playground Query API links for simple builds.
- Generate blueprint links when custom package sources require them.
- Preview the generated blueprint JSON.
- Copy the finished Playground link.
- Includes fallback plugin and theme suggestions when wordpress.org cannot be reached.
- Translatable with the `playground-builder` text domain.

## Installation

1. Upload the `playground-builder` directory to `wp-content/plugins/`.
2. Run `composer install` if the `vendor/` directory is not already present.
3. Activate **Playground Builder** in WordPress.
4. Visit `/playground-builder/`.

## Frequently Asked Questions

### Where does the app appear?

The app is available at `/playground-builder/` after activation.

### Does this install plugins or themes on my WordPress site?

No. The selected plugins and themes are used to generate a WordPress Playground link. They are installed inside the launched Playground environment, not on the site running Playground Builder.

### Why are there two link modes?

Simple builds can use Playground's Query API parameters. Builds that include custom Git or ZIP sources need a blueprint, so Playground Builder switches to a blueprint link when required.

### Can I use plugins or themes that are not on WordPress.org?

Yes. Paste a GitHub, GitLab, or ZIP URL into the search field. Playground Builder will add it to the generated blueprint as a custom package source.

## Screenshots

1. The Playground Builder app for searching plugins and themes, selecting runtime options, and copying a Playground link.

## Changelog

### 1.0.0

- Initial release.

## Development

```bash
composer install
```

The main plugin file is `playground-builder.php`, the WpApp template is in `templates/index.php`, and the app assets are in `assets/`.

