import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('sum', () => {
    test('returns total of array', () => {
        expect(template.parse('{{ items | sum }}', { items: [1, 2, 3] })).toBe('6');
    });

    test('with initial value adds offset', () => {
        expect(template.parse('{{ items | sum: 10 }}', { items: [1, 2, 3] })).toBe('16');
    });

    test('with zero initial value behaves like no offset', () => {
        expect(template.parse('{{ items | sum: 0 }}', { items: [1, 2, 3] })).toBe('6');
    });

    test('of empty array returns zero', () => {
        expect(template.parse('{{ items | sum }}', { items: [] })).toBe('0');
    });

    test('of empty array with initial value returns initial value', () => {
        expect(template.parse('{{ items | sum: 5 }}', { items: [] })).toBe('5');
    });

    test('with negative numbers', () => {
        expect(template.parse('{{ items | sum }}', { items: [-1, -2, 3] })).toBe('0');
    });

    test('with floating point numbers', () => {
        expect(template.parse('{{ items | sum }}', { items: [0.1, 0.2] })).toBe(
            '0.30000000000000004',
        );
    });
});
