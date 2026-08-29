import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('math', () => {
    test('plus and minus', () => {
        expect(curlytag.parse('{{ n | plus: 5 }}', { n: 10 })).toBe('15');
        expect(curlytag.parse('{{ n | minus: 3 }}', { n: 10 })).toBe('7');
    });

    test('round', () => {
        expect(curlytag.parse('{{ n | round: 2 }}', { n: 3.14159 })).toBe('3.14');
    });

    test('abs', () => {
        expect(curlytag.parse('{{ n | abs }}', { n: -42 })).toBe('42');
    });

    describe('at_least', () => {
        test('returns value when above minimum', () => {
            expect(curlytag.parse('{{ price | at_least: 0 }}', { price: 10 })).toBe('10');
        });

        test('returns minimum when value is below', () => {
            expect(curlytag.parse('{{ price | at_least: 0 }}', { price: -5 })).toBe('0');
        });

        test('returns value equal to minimum unchanged', () => {
            expect(curlytag.parse('{{ price | at_least: 10 }}', { price: 10 })).toBe('10');
        });

        test('works with float values', () => {
            expect(curlytag.parse('{{ price | at_least: 1.5 }}', { price: 0.5 })).toBe('1.5');
        });

        test('works with negative minimum', () => {
            expect(curlytag.parse('{{ temp | at_least: -10 }}', { temp: -20 })).toBe('-10');
        });

        test('chains with other math filters', () => {
            expect(curlytag.parse('{{ price | at_least: 0 | round: 2 }}', { price: -3 })).toBe('0.00');
        });

        test('zero value with zero minimum returns zero', () => {
            expect(curlytag.parse('{{ price | at_least: 0 }}', { price: 0 })).toBe('0');
        });

        test('large value is not clamped', () => {
            expect(curlytag.parse('{{ price | at_least: 100 }}', { price: 9999 })).toBe('9999');
        });

        test('non-numeric string returns minimum', () => {
            expect(curlytag.parse('{{ val | at_least: 0 }}', { val: 'hello' })).toBe('0');
        });

        test('undefined variable returns minimum', () => {
            expect(curlytag.parse('{{ val | at_least: 5 }}', {})).toBe('5');
        });

        test('numeric string is coerced and clamped', () => {
            expect(curlytag.parse('{{ val | at_least: 10 }}', { val: '3' })).toBe('10');
        });
    });

    describe('at_most', () => {
        test('returns value when below maximum', () => {
            expect(curlytag.parse('{{ discount | at_most: 100 }}', { discount: 50 })).toBe('50');
        });

        test('returns maximum when value exceeds it', () => {
            expect(curlytag.parse('{{ discount | at_most: 100 }}', { discount: 150 })).toBe('100');
        });

        test('returns value equal to maximum unchanged', () => {
            expect(curlytag.parse('{{ discount | at_most: 100 }}', { discount: 100 })).toBe('100');
        });

        test('works with float values', () => {
            expect(curlytag.parse('{{ rate | at_most: 0.99 }}', { rate: 1.5 })).toBe('0.99');
        });

        test('works with negative maximum', () => {
            expect(curlytag.parse('{{ temp | at_most: -1 }}', { temp: 5 })).toBe('-1');
        });

        test('chains with other math filters', () => {
            expect(curlytag.parse('{{ score | at_most: 100 | round: 0 }}', { score: 120 })).toBe('100');
        });

        test('zero value with zero maximum returns zero', () => {
            expect(curlytag.parse('{{ val | at_most: 0 }}', { val: 0 })).toBe('0');
        });

        test('negative value stays negative when below maximum', () => {
            expect(curlytag.parse('{{ val | at_most: 0 }}', { val: -5 })).toBe('-5');
        });

        test('non-numeric string returns maximum', () => {
            expect(curlytag.parse('{{ val | at_most: 100 }}', { val: 'hello' })).toBe('100');
        });

        test('undefined variable returns maximum', () => {
            expect(curlytag.parse('{{ val | at_most: 50 }}', {})).toBe('50');
        });

        test('numeric string is coerced and clamped', () => {
            expect(curlytag.parse('{{ val | at_most: 10 }}', { val: '99' })).toBe('10');
        });
    });
});
