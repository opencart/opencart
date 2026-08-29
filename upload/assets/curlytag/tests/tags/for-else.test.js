import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('for', () => {
    describe('else', () => {
        test('renders when array is empty', () => {
            const tpl = '{% for x in items %}{{ x }}{% else %}empty{% endfor %}';
            expect(curlytag.parse(tpl, { items: [] })).toBe('empty');
        });

        test('does not render when array has items', () => {
            const tpl = '{% for x in items %}{{ x }}{% else %}empty{% endfor %}';
            expect(curlytag.parse(tpl, { items: [ 'a', 'b' ] })).toBe('ab');
        });

        test('only renders one branch for non-empty array', () => {
            const tpl = '{% for x in items %}{{ x }} {% else %}none{% endfor %}';
            expect(curlytag.parse(tpl, { items: [ 1, 2, 3 ] })).toBe('1 2 3 ');
        });

        test('preserves content after endfor', () => {
            const tpl = '{% for x in items %}{{ x }}{% else %}empty{% endfor %}done';
            expect(curlytag.parse(tpl, { items: [] })).toBe('emptydone');
            expect(curlytag.parse(tpl, { items: [ 'a' ] })).toBe('adone');
        });

        test('renders when variable is null', () => {
            const tpl = '{% for x in items %}{{ x }}{% else %}empty{% endfor %}';
            expect(curlytag.parse(tpl, { items: null })).toBe('empty');
        });
    });
});
