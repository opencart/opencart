import stylistic from '@stylistic/eslint-plugin';

const style = stylistic.configs.customize({
    indent: 4,
    quotes: 'single',
    semi: true,
    jsx: false,
    arrowParens: true,
    braceStyle: '1tbs',
    blockSpacing: true,
    quoteProps: 'as-needed',
    commaDangle: 'never'
});

export default [
    {
        ignores: [ 'dist/**', '.vite-hooks/**' ]
    },
    {
        files: [ '**/*.js' ],
        plugins: style.plugins,
        rules: {
            ...style.rules,
            '@stylistic/array-bracket-spacing': [ 'error', 'always' ],
            '@stylistic/quotes': [ 'error', 'single', { avoidEscape: true } ]
        }
    }
];
