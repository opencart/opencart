import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('sum', () => {
    test('returns total of array', () => {
        expect(curlytag.parse('{{ items | sum }}', { items: [ 1, 2, 3 ] })).toBe('6');
    });

    test('with initial value adds offset', () => {
        expect(curlytag.parse('{{ items | sum: 10 }}', { items: [ 1, 2, 3 ] })).toBe('16');
    });

    test('with zero initial value behaves like no offset', () => {
        expect(curlytag.parse('{{ items | sum: 0 }}', { items: [ 1, 2, 3 ] })).toBe('6');
    });

    test('of empty array returns zero', () => {
        expect(curlytag.parse('{{ items | sum }}', { items: [] })).toBe('0');
    });

    test('of empty array with initial value returns initial value', () => {
        expect(curlytag.parse('{{ items | sum: 5 }}', { items: [] })).toBe('5');
    });

    test('with negative numbers', () => {
        expect(curlytag.parse('{{ items | sum }}', { items: [ -1, -2, 3 ] })).toBe('0');
    });

    test('with floating point numbers', () => {
        expect(curlytag.parse('{{ items | sum }}', { items: [ 0.1, 0.2 ] })).toBe(
            '0.30000000000000004'
        );
    });
});
