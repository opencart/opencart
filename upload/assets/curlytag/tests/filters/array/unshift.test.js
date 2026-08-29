import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('unshift', () => {
    test('prepends item and result is chainable', () => {
        expect(curlytag.parse('{{ items | unshift: 0 | join: "," }}', { items: [ 1, 2, 3 ] })).toBe(
            '0,1,2,3'
        );
    });

    test('increases length by one', () => {
        expect(curlytag.parse('{{ items | unshift: "z" | length }}', { items: [ 'a', 'b' ] })).toBe(
            '3'
        );
    });

    test('onto empty array returns single-element array', () => {
        expect(curlytag.parse('{{ items | unshift: "first" | join: "," }}', { items: [] })).toBe(
            'first'
        );
    });

    test('prepends the correct element at index zero', () => {
        expect(curlytag.parse('{{ items | unshift: "new" | first }}', { items: [ 'a', 'b' ] })).toBe(
            'new'
        );
    });

    test('existing elements shift to the right', () => {
        expect(curlytag.parse('{{ items | unshift: "x" | last }}', { items: [ 'a', 'b' ] })).toBe(
            'b'
        );
    });

    test('does not mutate the original array', () => {
        const data = { items: [ 1, 2 ] };
        curlytag.parse('{{ items | unshift: 0 | join: "," }}', data);
        expect(data.items.length).toBe(2);
    });
});
