import { describe, expect, test, vi } from 'vite-plus/test';
import { curlytag } from '#curlytag';

/*
 * OpenCart writes filter calls two ways, and both appear in templates CurlyTag has to render.
 * The split runs per file, never inside one file:
 *
 *   colon        catalog/product_info.html, component/menu.html, common/header.html,
 *                common/footer.html, catalog/category.html, catalog/manufacturer_list.html
 *   parentheses  information/gdpr.html, catalog/search.html, catalog/special.html,
 *                cms/comment.html, extension/.../module/banner.html
 *
 * Only the colon spelling is supported. Everything under `parentheses` below is a red test
 * describing what the storefront needs, not what the engine does today.
 */

const list = ['a', 'b', 'c', 'd', 'e'];

describe('filter call styles', () => {
    describe('colon', () => {
        // https://github.com/opencart/opencart/blob/01254c2/upload/catalog/view/template/common/header.html#L34
        test('passes several arguments to a filter', () => {
            expect(
                curlytag.parse("{{ text | replace: '%s', count }}", { text: 'Wish List (%s)', count: 3 })
            ).toBe('Wish List (3)');
        });

        // https://github.com/opencart/opencart/blob/01254c2/upload/catalog/view/template/common/footer.html#L45
        test('chains two calls of the same filter', () => {
            expect(
                curlytag.parse(
                    "{{ text | replace_first: '%s', name | replace_first: '%s', year }}",
                    { text: '%s (c) %s', name: 'Shop', year: 2026 }
                )
            ).toBe('Shop (c) 2026');
        });

        // https://github.com/opencart/opencart/blob/01254c2/upload/catalog/view/template/component/menu.html#L12
        test('chains an argument call into an argument-less one inside assign', () => {
            expect(
                curlytag.parse('{% assign columns = total | divide: 4 | round %}{{ columns }}', { total: 18 })
            ).toBe('5');
        });

        // https://github.com/opencart/opencart/blob/01254c2/upload/catalog/view/template/catalog/category.html#L38
        test('feeds a loop', () => {
            expect(
                curlytag.parse('{% for row in list | batch: 2 %}[{{ row|length }}]{% endfor %}', { list })
            ).toBe('[2][2][1]');
        });

        test('applies an argument-less filter in an output', () => {
            expect(curlytag.parse('{{ list | length }}', { list })).toBe('5');
        });
    });

    describe('parentheses', () => {
        // https://github.com/opencart/opencart/blob/01254c2/upload/catalog/view/template/information/gdpr.html#L2
        test.fails('passes several arguments to a filter', () => {
            expect(
                curlytag.parse('{{ text|format(store, title) }}', {
                    text: '%s / %s',
                    store: 'Shop',
                    title: 'GDPR',
                })
            ).toBe('Shop / GDPR');
        });

        // https://github.com/opencart/opencart/blob/01254c2/upload/catalog/view/template/catalog/search.html#L54
        test.fails('applies a filter to a string literal inside a condition', () => {
            expect(
                curlytag.parse(
                    "{% if value == '%s-%s'|format(sort, order) %}Y{% else %}N{% endif %}",
                    { value: 'name-ASC', sort: 'name', order: 'ASC' }
                )
            ).toBe('Y');
        });

        // https://github.com/opencart/opencart/blob/01254c2/upload/extension/opencart/catalog/view/template/module/banner.html#L5
        test.fails('does not throw when a parenthesised filter call feeds a loop', () => {
            expect(() => curlytag.parse('{% for row in list|batch(2) %}[]{% endfor %}', { list })).not.toThrow();
        });

        test.fails('accepts an empty argument list', () => {
            expect(curlytag.parse('{{ value|upper() }}', { value: 'ab' })).toBe('AB');
        });

        test('reports the whole call as an unknown filter name', () => {
            const log = vi.spyOn(console, 'log').mockImplementation(() => {});

            expect(curlytag.parse('{{ value|upper() }}', { value: 'ab' })).toBe('');
            expect(log).toHaveBeenCalledWith(expect.stringContaining('Unknown filter: upper()'));

            log.mockRestore();
        });
    });

    /*
     * Conditions never apply filters at all - handleIf hands the whole expression to
     * evaluate(), where `|` stays a bitwise OR and the filter name is a free identifier.
     * In a browser that identifier can resolve to a window property, so the branch is
     * picked from unrelated data; tests/browser-globals.test.js tracks the same leak.
     */
    describe('filters inside conditions', () => {
        test.fails('applies a colon-style filter', () => {
            expect(
                curlytag.parse('{% if list | batch: 2 %}Y{% else %}N{% endif %}', { list })
            ).toBe('Y');
        });

        test('applies filters in a loop source, an assign and an output', () => {
            expect(curlytag.parse('{% for row in list | batch: 2 %}[]{% endfor %}', { list })).toBe('[][][]');
            expect(curlytag.parse('{% assign total = list | length %}{{ total }}', { list })).toBe('5');
            expect(curlytag.parse('{{ list | length }}', { list })).toBe('5');
        });
    });
});
