import { playwright } from 'vite-plus/test/browser-playwright';
import { defineConfig } from 'vite-plus';

export default defineConfig({
    root: 'playground',
    resolve: {
        alias: {
            '#curlytag': new URL('./curlytag.js', import.meta.url).pathname,
            '#fixtures': new URL('./tests/fixtures', import.meta.url).pathname,
        },
    },
    build: {
        outDir: '../dist',
        emptyOutDir: true,
    },
    server: {
        host: '127.0.0.1',
    },
    staged: {
        '*': 'vp check --fix',
        '**/*.js': 'vp exec eslint --fix',
    },
    lint: {
        ignorePatterns: ['docs/.vitepress/cache/**'],
        rules: {
            eqeqeq: 'off',
            'no-with': 'off',
        },
    },
    test: {
        coverage: {
            provider: 'istanbul',
            include: [new URL('./curlytag.js', import.meta.url).pathname],
            allowExternal: true,
            reporter: ['text', 'html', 'lcov'],
        },
        projects: [
            {
                resolve: {
                    alias: {
                        '#curlytag': new URL('./tests/helpers/curlytag.js', import.meta.url)
                            .pathname,
                        '#fixtures': new URL('./tests/fixtures', import.meta.url).pathname,
                    },
                },
                test: {
                    name: 'browser',
                    include: ['../tests/**/*.test.js'],
                    browser: {
                        enabled: true,
                        headless: true,
                        provider: playwright(),
                        instances: [
                            { browser: 'chromium' },
                            { browser: 'firefox' },
                            { browser: 'webkit' },
                        ],
                    },
                },
            },
        ],
    },
    fmt: {
        tabWidth: 4,
        singleQuote: true,
        ignorePatterns: [
            '**/*.js',
            '**/*.md',
            '**/*.yml',
            '**/*.yaml',
            '**/*.json',
            'playground/examples/**/template.html',
            'docs/.vitepress/cache/**',
        ],
    },
});
