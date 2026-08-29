import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('to_integer', () => {
    test('converts string integer to number', () => {
        expect(curlytag.parse('{{ val | to_integer }}', { val: '42' })).toBe('42');
    });

    test('converts string float to integer by truncating', () => {
        expect(curlytag.parse('{{ val | to_integer }}', { val: '3.9' })).toBe('3');
    });

    test('converts negative string to integer', () => {
        expect(curlytag.parse('{{ val | to_integer }}', { val: '-7' })).toBe('-7');
    });

    test('converts float number to integer by truncating', () => {
        expect(curlytag.parse('{{ val | to_integer }}', { val: 4.8 })).toBe('4');
    });

    test('integer stays unchanged', () => {
        expect(curlytag.parse('{{ val | to_integer }}', { val: 10 })).toBe('10');
    });

    test('zero stays zero', () => {
        expect(curlytag.parse('{{ val | to_integer }}', { val: 0 })).toBe('0');
    });

    test('string with leading whitespace is parsed', () => {
        expect(curlytag.parse('{{ val | to_integer }}', { val: '  5' })).toBe('5');
    });

    test('non-numeric string returns NaN', () => {
        expect(curlytag.parse('{{ val | to_integer }}', { val: 'hello' })).toBe('NaN');
    });

    test('empty string returns NaN', () => {
        expect(curlytag.parse('{{ val | to_integer }}', { val: '' })).toBe('NaN');
    });

    test('chains with math filters', () => {
        expect(curlytag.parse('{{ val | to_integer | plus: 1 }}', { val: '9' })).toBe('10');
    });

    test('chains with at_least', () => {
        expect(curlytag.parse('{{ val | to_integer | at_least: 0 }}', { val: '-5' })).toBe('0');
    });
});
