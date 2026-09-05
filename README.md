# Playground Builder

- Contributors: akirk
- Tags: blueprints, demo, sandbox, developer-tools, wp-app
- Requires at least: 6.0
- Requires PHP: 7.4
- Tested up to: 7.1
- Stable tag: 1.0.0
- License: GPL-2.0-or-later
- License URI: https://www.gnu.org/licenses/gpl-2.0.html

Build shareable WordPress Playground links: pick plugins, themes, WordPress and PHP versions, a language and a start page, then copy the link.

## Description

[Try it in WordPress Playground](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/akirk/playground-builder/main/blueprint.json)

[Try it in OpenStation](https://playground.wordpress.net/?blueprint-url=https://raw.githubusercontent.com/akirk/playground-builder/main/blueprint-openstation.json) — the same app opened in desktop mode with the [OpenStation](https://github.com/WordPress/openstation) plugin.

Playground Builder is a WordPress app for creating shareable WordPress Playground links. It helps you build a Playground configuration by searching WordPress.org plugins and themes, choosing runtime settings, and copying either a Query API link or a full blueprint link.

Instead of hand-writing blueprint JSON, you pick what you want in a two-panel interface: search the plugin and theme directories on the left, watch your build take shape on the right, and copy the finished link when it looks right. That link boots a throwaway WordPress in the browser with exactly those plugins and that theme installed — useful for bug reports, demos, workshop handouts, support replies, and "try my plugin" buttons in a README.

The app picks the simplest link format that can express your build. As long as everything comes from WordPress.org, it emits a short Query API URL. The moment you add a package that Playground cannot resolve by slug — a GitHub or GitLab repository, or a plain ZIP URL — it switches to a blueprint link and tells you why. You can inspect the generated blueprint JSON at any time before copying.

The app is powered by the [WpApp framework](https://github.com/akirk/wp-app), so it lives at `/playground-builder/` on your site, separate from your theme, while still using WordPress routing, translations, and plugin loading. Nothing you select is installed on the site that runs Playground Builder; the selection only describes the sandbox that the link will launch.

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

### Does it need an internet connection?

Searching queries the WordPress.org plugin and theme directories, so results need a connection. If wordpress.org cannot be reached, the app falls back to a small built-in list of well-known plugins and themes so you can still assemble a build.

### Can I build more complicated blueprints?

For steps beyond installing packages and setting versions, the app links out to the [Playground Step Library](https://akirk.github.io/playground-step-library/), which covers the full blueprint step vocabulary.

## Screenshots

1. The Playground Builder app for searching plugins and themes, selecting runtime options, and copying a Playground link.
2. Choosing plugins on a phone: the search field above the results, each with the button that adds it to the blueprint.

## Changelog

### 1.0.0

- Initial release.

## Development

```bash
composer install
```

The main plugin file is `playground-builder.php`, the WpApp template is in `templates/index.php`, and the app assets are in `assets/`.
