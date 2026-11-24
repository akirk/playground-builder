const assert = require('node:assert/strict');
const { before, after, test } = require('node:test');
const path = require('node:path');
const { pathToFileURL } = require('node:url');
const { chromium } = require('playwright');

let browser;
const site = pathToFileURL(path.resolve('build/static/index.html')).href;

before(async () => {
    // GitHub's Ubuntu image already includes Chrome; no browser/OS installation is needed.
    browser = await chromium.launch({ channel: 'chrome', headless: true });
});

after(async () => {
    if (browser) await browser.close();
});

async function openBuilder() {
    const page = await browser.newPage();
    await page.goto(site, { waitUntil: 'domcontentloaded' });
    return page;
}

test('runtime and custom landing selections produce the complete Query API link', async () => {
    const page = await openBuilder();
    try {
        await page.locator('[data-language]').selectOption('de_DE');
        await page.locator('[data-wp-version]').selectOption('6.9');
        await page.locator('[data-php-version]').selectOption('8.2');
        await page.locator('[data-landing-page]').selectOption('custom');
        await page.locator('[data-custom-landing]').fill('wp-admin/edit.php?post_type=page');
        const link = new URL(await page.locator('[data-open]').getAttribute('href'));
        assert.equal(link.origin, 'https://playground.wordpress.net');
        assert.equal(link.pathname, '/');
        assert.deepEqual(Object.fromEntries(link.searchParams), {
            language: 'de_DE', wp: '6.9', php: '8.2', url: '/wp-admin/edit.php?post_type=page'
        });
        assert.equal(await page.locator('[data-link]').textContent(), link.href);
    } finally {
        await page.close();
    }
});

const packages = [
    {
        name: 'Git branch and subdirectory survive the generated blueprint and open link',
        input: 'https://github.com/example/plugin-suite/tree/develop/plugins/weather-map',
        expected: {
            steps: [{
                step: 'installPlugin',
                pluginData: {
                    resource: 'git:directory', url: 'https://github.com/example/plugin-suite',
                    ref: 'develop', refType: 'branch', path: 'plugins/weather-map'
                },
                options: { activate: true, targetFolderName: 'weather-map' }
            }]
        }
    },
    {
        name: 'ZIP source retains its query parameters and normalized plugin folder',
        input: 'https://example.invalid/downloads/Weather%20Plugin.zip?token=a%2Bb&download=1',
        expected: {
            steps: [{
                step: 'installPlugin',
                pluginData: {
                    resource: 'url',
                    url: 'https://example.invalid/downloads/Weather%20Plugin.zip?token=a%2Bb&download=1'
                },
                options: { activate: true, targetFolderName: 'weather-plugin' }
            }]
        }
    }
];

for (const fixture of packages) {
    test(fixture.name, async () => {
        const page = await openBuilder();
        try {
            // Custom sources are parsed immediately by the real UI, without a directory lookup.
            await page.locator('[data-query]').fill(fixture.input);
            await page.locator('[data-results] .playground-builder__add').click();
            assert.equal(await page.locator('[data-output-label]').textContent(), 'Blueprint JSON');
            assert.deepEqual(JSON.parse(await page.locator('[data-link]').textContent()), fixture.expected);
            const link = new URL(await page.locator('[data-open]').getAttribute('href'));
            assert.equal(link.origin, 'https://playground.wordpress.net');
            const blueprintUrl = link.searchParams.get('blueprint-url');
            assert.ok(blueprintUrl.startsWith('data:application/json,'));
            assert.deepEqual(JSON.parse(blueprintUrl.slice('data:application/json,'.length)), fixture.expected);
        } finally {
            await page.close();
        }
    });
}
