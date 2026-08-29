import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('addFilter', () => {
    test('registers and applies a custom filter', () => {
        curlytag.addFilter('shout', (v) => v + '!!!');
        expect(curlytag.parse('{{ msg | shout }}', { msg: 'hello' })).toBe('hello!!!');
    });

    test('custom filter with argument', () => {
        curlytag.addFilter('repeat', (v, n) => v.repeat(n));
        expect(curlytag.parse('{{ char | repeat: 3 }}', { char: 'ha' })).toBe('hahaha');
    });

    test('custom filter chains with built-in filters', () => {
        curlytag.addFilter('exclaim', (v) => v + '!');
        expect(curlytag.parse('{{ msg | upper | exclaim }}', { msg: 'hi' })).toBe('HI!');
    });
});
