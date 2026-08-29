import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('array', () => {
    test('join', () => {
        expect(curlytag.parse('{{ items | join: ", " }}', { items: [ 'a', 'b', 'c' ] })).toBe(
            'a, b, c'
        );
    });

    test('reverse', () => {
        expect(curlytag.parse('{{ items | reverse | join: "" }}', { items: [ 'a', 'b', 'c' ] })).toBe(
            'cba'
        );
    });

    test('first and last', () => {
        const data = { items: [ 10, 20, 30 ] };
        expect(curlytag.parse('{{ items | first }}', data)).toBe('10');
        expect(curlytag.parse('{{ items | last }}', data)).toBe('30');
    });

    test('length', () => {
        expect(curlytag.parse('{{ items | length }}', { items: [ 1, 2, 3 ] })).toBe('3');
    });
});
