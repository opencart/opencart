import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('for', () => {
    test('iterates over array', () => {
        expect(
            template.parse('{% for item in items %}{{ item }} {% endfor %}', {
                items: ['a', 'b', 'c'],
            }),
        ).toBe('a b c ');
    });

    test('loop.index starts at 1', () => {
        expect(
            template.parse('{% for x in items %}{{ loop.index }}{% endfor %}', {
                items: ['a', 'b'],
            }),
        ).toBe('12');
    });

    test('loop.first and loop.last', () => {
        const tpl =
            '{% for x in items %}{% if loop.first %}[{% endif %}{{ x }}{% if loop.last %}]{% endif %}{% endfor %}';
        expect(template.parse(tpl, { items: ['a', 'b', 'c'] })).toBe('[abc]');
    });

    test('empty array produces no output', () => {
        expect(template.parse('{% for x in items %}{{ x }}{% endfor %}', { items: [] })).toBe('');
    });

    test('with filter', () => {
        expect(
            template.parse('{% for x in items | sort %}{{ x }}{% endfor %}', {
                items: ['c', 'a', 'b'],
            }),
        ).toBe('abc');
    });

    test('continue skips current iteration', () => {
        const tpl =
            '{% for n in nums %}{% if n == 2 %}{% continue %}{% endif %}{{ n }}{% endfor %}';
        expect(template.parse(tpl, { nums: [1, 2, 3] })).toBe('13');
    });

    test('break exits the loop', () => {
        const tpl = '{% for n in nums %}{% if n == 3 %}{% break %}{% endif %}{{ n }}{% endfor %}';
        expect(template.parse(tpl, { nums: [1, 2, 3, 4] })).toBe('12');
    });

    test('break on first iteration produces empty output', () => {
        const tpl = '{% for n in nums %}{% break %}{{ n }}{% endfor %}';
        expect(template.parse(tpl, { nums: [1, 2, 3] })).toBe('');
    });

    test('break only exits the inner loop', () => {
        const tpl =
            '{% for i in outer %}{% for j in inner %}{% if j == 2 %}{% break %}{% endif %}{{ i }}.{{ j }} {% endfor %}{% endfor %}';
        expect(template.parse(tpl, { outer: [1, 2], inner: [1, 2, 3] })).toBe('1.1 2.1 ');
    });

    test('if inside for', () => {
        const tpl = '{% for n in nums %}{% if n > 1 %}{{ n }}{% endif %}{% endfor %}';
        expect(template.parse(tpl, { nums: [1, 2, 3] })).toBe('23');
    });

    test('nested for loops', () => {
        const tpl =
            '{% for row in matrix %}{% for cell in row %}{{ cell }}{% endfor %}-{% endfor %}';
        expect(
            template.parse(tpl, {
                matrix: [
                    [1, 2],
                    [3, 4],
                ],
            }),
        ).toBe('12-34-');
    });

    test('non-iterable produces no output', () => {
        expect(template.parse('{% for x in items %}{{ x }}{% endfor %}', { items: 42 })).toBe('');
    });

    test('undefined variable produces no output', () => {
        expect(template.parse('{% for x in items %}{{ x }}{% endfor %}')).toBe('');
    });

    test('else renders when array is empty', () => {
        const tpl = '{% for x in items %}{{ x }}{% else %}empty{% endfor %}';
        expect(template.parse(tpl, { items: [] })).toBe('empty');
    });

    test('else does not render when array has items', () => {
        const tpl = '{% for x in items %}{{ x }}{% else %}empty{% endfor %}';
        expect(template.parse(tpl, { items: ['a', 'b'] })).toBe('ab');
    });

    test('only one branch renders for non-empty array', () => {
        const tpl = '{% for x in items %}{{ x }} {% else %}none{% endfor %}';
        expect(template.parse(tpl, { items: [1, 2, 3] })).toBe('1 2 3 ');
    });

    test('content after endfor renders after else', () => {
        const tpl = '{% for x in items %}{{ x }}{% else %}empty{% endfor %}done';
        expect(template.parse(tpl, { items: [] })).toBe('emptydone');
        expect(template.parse(tpl, { items: ['a'] })).toBe('adone');
    });

    test('for without else still works when empty', () => {
        expect(template.parse('{% for x in items %}{{ x }}{% endfor %}', { items: [] })).toBe('');
    });

    test('null variable produces no output', () => {
        expect(template.parse('{% for x in items %}{{ x }}{% endfor %}', { items: null })).toBe('');
    });

    test('null variable does not crash', () => {
        expect(() =>
            template.parse('{% for x in items %}{{ x }}{% endfor %}', { items: null }),
        ).not.toThrow();
    });

    test('false variable produces no output', () => {
        expect(template.parse('{% for x in items %}{{ x }}{% endfor %}', { items: false })).toBe(
            '',
        );
    });

    test('null variable renders else branch', () => {
        const tpl = '{% for x in items %}{{ x }}{% else %}empty{% endfor %}';
        expect(template.parse(tpl, { items: null })).toBe('empty');
    });

    test('break outside loop is a no-op', () => {
        expect(template.parse('before{% break %}after')).toBe('beforeafter');
    });

    test('break outside loop does not corrupt if/else', () => {
        expect(template.parse('{% if true %}{% break %}{% else %}hidden{% endif %}result')).toBe(
            'result',
        );
    });

    test('break outside loop does not corrupt elseif', () => {
        expect(
            template.parse('{% if true %}{% break %}{% elseif false %}hidden{% endif %}result'),
        ).toBe('result');
    });

    test('break outside loop does not affect subsequent ifs', () => {
        expect(template.parse('{% break %}{% if true %}yes{% else %}no{% endif %}')).toBe('yes');
    });

    test('break outside loop does not corrupt case', () => {
        const tpl = '{% case status %}{% when "ok" %}{% break %}OK{% when "err" %}ERR{% endcase %}';
        expect(template.parse(tpl, { status: 'ok' })).toBe('OK');
    });

    test('continue outside loop is a no-op', () => {
        expect(template.parse('before{% continue %}after')).toBe('beforeafter');
    });

    test('continue outside loop does not corrupt if/else', () => {
        expect(template.parse('{% if true %}{% continue %}{% else %}hidden{% endif %}result')).toBe(
            'result',
        );
    });

    test('continue outside loop does not corrupt elseif', () => {
        expect(
            template.parse('{% if true %}{% continue %}{% elseif false %}hidden{% endif %}result'),
        ).toBe('result');
    });

    test('continue outside loop does not corrupt case', () => {
        const tpl =
            '{% case status %}{% when "ok" %}{% continue %}OK{% when "err" %}ERR{% endcase %}';
        expect(template.parse(tpl, { status: 'ok' })).toBe('OK');
    });
});
