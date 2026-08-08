import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('unshift', () => {
    test('prepends item and result is chainable', () => {
        expect(template.parse('{{ items | unshift: 0 | join: "," }}', { items: [1, 2, 3] })).toBe(
            '0,1,2,3',
        );
    });

    test('increases length by one', () => {
        expect(template.parse('{{ items | unshift: "z" | length }}', { items: ['a', 'b'] })).toBe(
            '3',
        );
    });

    test('onto empty array returns single-element array', () => {
        expect(template.parse('{{ items | unshift: "first" | join: "," }}', { items: [] })).toBe(
            'first',
        );
    });

    test('prepends the correct element at index zero', () => {
        expect(template.parse('{{ items | unshift: "new" | first }}', { items: ['a', 'b'] })).toBe(
            'new',
        );
    });

    test('existing elements shift to the right', () => {
        expect(template.parse('{{ items | unshift: "x" | last }}', { items: ['a', 'b'] })).toBe(
            'b',
        );
    });

    test('does not mutate the original array', () => {
        const data = { items: [1, 2] };
        template.parse('{{ items | unshift: 0 | join: "," }}', data);
        expect(data.items.length).toBe(2);
    });
});
