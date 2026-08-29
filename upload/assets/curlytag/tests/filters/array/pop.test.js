import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('pop', () => {
    test('removes last item and result is chainable', () => {
        expect(curlytag.parse('{{ items | pop | join: "," }}', { items: [ 1, 2, 3 ] })).toBe('1,2');
    });

    test('decreases length by one', () => {
        expect(curlytag.parse('{{ items | pop | length }}', { items: [ 'a', 'b', 'c' ] })).toBe('2');
    });

    test('on single-element array returns empty', () => {
        expect(curlytag.parse('{{ items | pop | join: "," }}', { items: [ 'only' ] })).toBe('');
    });

    test('removes the correct last element', () => {
        expect(curlytag.parse('{{ items | pop | last }}', { items: [ 'a', 'b', 'c' ] })).toBe('b');
    });

    test('on empty array returns empty', () => {
        expect(curlytag.parse('{{ items | pop | join: "," }}', { items: [] })).toBe('');
    });

    test('does not mutate the original array', () => {
        const data = { items: [ 1, 2, 3 ] };
        curlytag.parse('{{ items | pop | join: "," }}', data);
        expect(data.items.length).toBe(3);
    });
});
