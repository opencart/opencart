import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('cycle', () => {
    test('cycles through values on each call', () => {
        const tpl = '{% for x in items %}{% cycle "odd", "even" %}{% endfor %}';
        expect(curlytag.parse(tpl, { items: [ 1, 2, 3 ] })).toBe('oddevenodd');
    });

    test('cycles wrap around when values are exhausted', () => {
        const tpl = '{% for x in items %}{% cycle "a", "b" %}{% endfor %}';
        expect(curlytag.parse(tpl, { items: [ 1, 2, 3, 4 ] })).toBe('abab');
    });

    test('single value always repeats', () => {
        const tpl = '{% for x in items %}{% cycle "x" %}{% endfor %}';
        expect(curlytag.parse(tpl, { items: [ 1, 2, 3 ] })).toBe('xxx');
    });

    test('named groups cycle independently', () => {
        const tpl
            = '{% for x in items %}'
                + '{% cycle "g1": "a", "b" %}'
                + '{% cycle "g2": "1", "2" %}'
                + '{% endfor %}';
        expect(curlytag.parse(tpl, { items: [ 1, 2, 3 ] })).toBe('a1b2a1');
    });

    test('same unnamed args share state', () => {
        const tpl
            = '{% for x in items %}' + '{% cycle "a", "b" %}{% cycle "a", "b" %}' + '{% endfor %}';
        expect(curlytag.parse(tpl, { items: [ 1, 2 ] })).toBe('abab');
    });

    test('different unnamed args are separate groups', () => {
        const tpl
            = '{% for x in items %}' + '{% cycle "a", "b" %}{% cycle "1", "2" %}' + '{% endfor %}';
        expect(curlytag.parse(tpl, { items: [ 1, 2 ] })).toBe('a1b2');
    });

    test('cycle with variables instead of literals', () => {
        const tpl = '{% for x in items %}{% cycle first, second %}{% endfor %}';
        expect(curlytag.parse(tpl, { items: [ 1, 2, 3 ], first: 'X', second: 'Y' })).toBe('XYX');
    });

    test('cycle outside of for loop', () => {
        const tpl = '{% cycle "a", "b" %}-{% cycle "a", "b" %}-{% cycle "a", "b" %}-';
        expect(curlytag.parse(tpl, {})).toBe('a-b-a-');
    });

    test('invalid syntax produces no output', () => {
        expect(curlytag.parse('{% cycle %}text', {})).toBe('text');
    });
});
