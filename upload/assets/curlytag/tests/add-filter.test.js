import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('addFilter', () => {
    test('registers and applies a custom filter', () => {
        template.addFilter('shout', (v) => v + '!!!');
        expect(template.parse('{{ msg | shout }}', { msg: 'hello' })).toBe('hello!!!');
    });

    test('custom filter with argument', () => {
        template.addFilter('repeat', (v, n) => v.repeat(n));
        expect(template.parse('{{ char | repeat: 3 }}', { char: 'ha' })).toBe('hahaha');
    });

    test('custom filter chains with built-in filters', () => {
        template.addFilter('exclaim', (v) => v + '!');
        expect(template.parse('{{ msg | upper | exclaim }}', { msg: 'hi' })).toBe('HI!');
    });
});
