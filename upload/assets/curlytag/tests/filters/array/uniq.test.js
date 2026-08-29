import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('uniq', () => {
    test('removes duplicate numbers', () => {
        expect(
            curlytag.parse('{{ items | uniq | join: "," }}', { items: [ 1, 2, 2, 3, 1 ] })
        ).toBe('1,2,3');
    });

    test('removes duplicate strings', () => {
        expect(
            curlytag.parse('{{ items | uniq | join: "," }}', { items: [ 'a', 'b', 'a', 'c' ] })
        ).toBe('a,b,c');
    });

    test('preserves order of first occurrence', () => {
        expect(
            curlytag.parse('{{ items | uniq | join: "," }}', { items: [ 3, 1, 2, 1, 3 ] })
        ).toBe('3,1,2');
    });

    test('array with no duplicates is unchanged', () => {
        expect(
            curlytag.parse('{{ items | uniq | join: "," }}', { items: [ 1, 2, 3 ] })
        ).toBe('1,2,3');
    });

    test('all identical values return single element', () => {
        expect(
            curlytag.parse('{{ items | uniq | join: "," }}', { items: [ 5, 5, 5 ] })
        ).toBe('5');
    });

    test('empty array returns empty array', () => {
        expect(curlytag.parse('{{ items | uniq | length }}', { items: [] })).toBe('0');
    });

    test('preserves null and undefined as unique values', () => {
        expect(
            curlytag.parse('{{ items | uniq | length }}', { items: [ null, null, undefined ] })
        ).toBe('2');
    });

    test('treats 0 and false as distinct', () => {
        expect(
            curlytag.parse('{{ items | uniq | length }}', { items: [ 0, false, 0, false ] })
        ).toBe('2');
    });

    test('chains with other filters', () => {
        expect(
            curlytag.parse('{{ items | uniq | length }}', { items: [ 1, 2, 2, 3, 3, 3 ] })
        ).toBe('3');
    });

    test('works with mixed types', () => {
        expect(
            curlytag.parse('{{ items | uniq | length }}', { items: [ 1, '1', 1, '1' ] })
        ).toBe('2');
    });
});
