import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('for', () => {
    test('iterates over array', () => {
        expect(
            curlytag.parse('{% for item in items %}{{ item }} {% endfor %}', {
                items: [ 'a', 'b', 'c' ]
            })
        ).toBe('a b c ');
    });

    test('loop.index starts at 1', () => {
        expect(
            curlytag.parse('{% for x in items %}{{ loop.index }}{% endfor %}', {
                items: [ 'a', 'b' ]
            })
        ).toBe('12');
    });

    test('loop.first and loop.last', () => {
        const tpl
            = '{% for x in items %}{% if loop.first %}[{% endif %}{{ x }}{% if loop.last %}]{% endif %}{% endfor %}';
        expect(curlytag.parse(tpl, { items: [ 'a', 'b', 'c' ] })).toBe('[abc]');
    });

    test('empty array produces no output', () => {
        expect(curlytag.parse('{% for x in items %}{{ x }}{% endfor %}', { items: [] })).toBe('');
    });

    test('with filter', () => {
        expect(
            curlytag.parse('{% for x in items | sort %}{{ x }}{% endfor %}', {
                items: [ 'c', 'a', 'b' ]
            })
        ).toBe('abc');
    });

    test('if inside for', () => {
        const tpl = '{% for n in nums %}{% if n > 1 %}{{ n }}{% endif %}{% endfor %}';
        expect(curlytag.parse(tpl, { nums: [ 1, 2, 3 ] })).toBe('23');
    });

    test('nested for loops', () => {
        const tpl
            = '{% for row in matrix %}{% for cell in row %}{{ cell }}{% endfor %}-{% endfor %}';
        expect(
            curlytag.parse(tpl, {
                matrix: [
                    [ 1, 2 ],
                    [ 3, 4 ]
                ]
            })
        ).toBe('12-34-');
    });

    test('non-iterable produces no output', () => {
        expect(curlytag.parse('{% for x in items %}{{ x }}{% endfor %}', { items: 42 })).toBe('');
    });

    test('undefined variable produces no output', () => {
        expect(curlytag.parse('{% for x in items %}{{ x }}{% endfor %}')).toBe('');
    });

    test('for without else still works when empty', () => {
        expect(curlytag.parse('{% for x in items %}{{ x }}{% endfor %}', { items: [] })).toBe('');
    });

    test('null variable produces no output', () => {
        expect(curlytag.parse('{% for x in items %}{{ x }}{% endfor %}', { items: null })).toBe('');
    });

    test('null variable does not crash', () => {
        expect(() =>
            curlytag.parse('{% for x in items %}{{ x }}{% endfor %}', { items: null })
        ).not.toThrow();
    });

    test('false variable produces no output', () => {
        expect(curlytag.parse('{% for x in items %}{{ x }}{% endfor %}', { items: false })).toBe(
            ''
        );
    });
});
