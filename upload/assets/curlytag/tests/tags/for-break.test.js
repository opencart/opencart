import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('for', () => {
    describe('break', () => {
        test('exits the loop', () => {
            const tpl
                = '{% for n in nums %}{% if n == 3 %}{% break %}{% endif %}{{ n }}{% endfor %}';
            expect(curlytag.parse(tpl, { nums: [ 1, 2, 3, 4 ] })).toBe('12');
        });

        test('on first iteration produces empty output', () => {
            const tpl = '{% for n in nums %}{% break %}{{ n }}{% endfor %}';
            expect(curlytag.parse(tpl, { nums: [ 1, 2, 3 ] })).toBe('');
        });

        test('skips the else branch', () => {
            const tpl = '{% for item in items %}{{ item }}{% break %}{% else %}empty{% endfor %}';
            expect(curlytag.parse(tpl, { items: [ 'A', 'B' ] })).toBe('A');
        });

        test('conditionally skips the else branch after multiple iterations', () => {
            const tpl
                = '{% for item in items %}{{ item }}{% if item == "B" %}{% break %}{% endif %}{% else %}empty{% endfor %}';
            expect(curlytag.parse(tpl, { items: [ 'A', 'B', 'C' ] })).toBe('AB');
        });

        test('preserves content after the loop', () => {
            const tpl
                = '{% for item in items %}{{ item }}{% break %}{% else %}empty{% endfor %}after';
            expect(curlytag.parse(tpl, { items: [ 'A', 'B' ] })).toBe('Aafter');
        });

        test('in an inner loop skips its else branch and preserves the outer loop', () => {
            const tpl
                = '{% for outer in outerItems %}{% for inner in innerItems %}{{ outer }}{{ inner }}{% break %}{% else %}inner-empty{% endfor %}{% endfor %}';
            expect(
                curlytag.parse(tpl, {
                    outerItems: [ 'A', 'B' ],
                    innerItems: [ 1, 2 ]
                })
            ).toBe('A1B1');
        });

        test('removes loop variables from the context', () => {
            const data = { items: [ 'A', 'B' ] };

            curlytag.parse('{% for item in items %}{% break %}{% endfor %}', data);

            expect(data.item).toBeUndefined();
            expect(data.loop).toBeUndefined();
        });

        test('removes loop variables after a conditional break', () => {
            const data = { items: [ 'A', 'B' ] };
            const tpl
                = '{% for item in items %}{% if item == "B" %}{% break %}{% endif %}{% endfor %}';

            curlytag.parse(tpl, data);

            expect(data.item).toBeUndefined();
            expect(data.loop).toBeUndefined();
        });

        test('only exits the inner loop', () => {
            const tpl
                = '{% for i in outer %}{% for j in inner %}{% if j == 2 %}{% break %}{% endif %}{{ i }}.{{ j }} {% endfor %}{% endfor %}';
            expect(curlytag.parse(tpl, { outer: [ 1, 2 ], inner: [ 1, 2, 3 ] })).toBe(
                '1.1 2.1 '
            );
        });

        test('outside loop is a no-op', () => {
            expect(curlytag.parse('before{% break %}after')).toBe('beforeafter');
        });

        test('outside loop does not corrupt if/else', () => {
            expect(
                curlytag.parse('{% if true %}{% break %}{% else %}hidden{% endif %}result')
            ).toBe('result');
        });

        test('outside loop does not corrupt elseif', () => {
            expect(
                curlytag.parse(
                    '{% if true %}{% break %}{% elseif false %}hidden{% endif %}result'
                )
            ).toBe('result');
        });

        test('outside loop does not affect subsequent ifs', () => {
            expect(curlytag.parse('{% break %}{% if true %}yes{% else %}no{% endif %}')).toBe(
                'yes'
            );
        });

        test('outside loop does not corrupt case', () => {
            const tpl
                = '{% case status %}{% when "ok" %}{% break %}OK{% when "err" %}ERR{% endcase %}';
            expect(curlytag.parse(tpl, { status: 'ok' })).toBe('OK');
        });
    });
});
