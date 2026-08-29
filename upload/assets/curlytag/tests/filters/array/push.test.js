import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('push', () => {
    test('appends item and result is chainable', () => {
        expect(curlytag.parse('{{ items | push: 4 | join: "," }}', { items: [ 1, 2, 3 ] })).toBe(
            '1,2,3,4'
        );
    });

    test('increases length by one', () => {
        expect(curlytag.parse('{{ items | push: "x" | length }}', { items: [ 'a', 'b' ] })).toBe('3');
    });

    test('onto empty array returns single-element array', () => {
        expect(curlytag.parse('{{ items | push: "only" | join: "," }}', { items: [] })).toBe(
            'only'
        );
    });

    test('appends a string value', () => {
        expect(curlytag.parse('{{ items | push: "d" | last }}', { items: [ 'a', 'b', 'c' ] })).toBe(
            'd'
        );
    });

    test('does not mutate the original array', () => {
        const data = { items: [ 1, 2 ] };
        curlytag.parse('{{ items | push: 3 | join: "," }}', data);
        expect(data.items.length).toBe(2);
    });
});
