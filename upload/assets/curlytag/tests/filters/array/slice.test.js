import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('slice', () => {
    test('with start and end returns subarray', () => {
        expect(
            curlytag.parse('{{ items | slice: 1, 3 | join: "," }}', {
                items: [ 'a', 'b', 'c', 'd' ]
            })
        ).toBe('b,c');
    });

    test('with start only returns tail', () => {
        expect(
            curlytag.parse('{{ items | slice: 2 | join: "," }}', { items: [ 'a', 'b', 'c', 'd' ] })
        ).toBe('c,d');
    });

    test('with zero start and end returns prefix', () => {
        expect(
            curlytag.parse('{{ items | slice: 0, 2 | join: "," }}', { items: [ 'x', 'y', 'z' ] })
        ).toBe('x,y');
    });

    test('with negative start returns last N items', () => {
        expect(
            curlytag.parse('{{ items | slice: -2 | join: "," }}', { items: [ 'a', 'b', 'c', 'd' ] })
        ).toBe('c,d');
    });

    test('with end beyond length returns elements to end', () => {
        expect(
            curlytag.parse('{{ items | slice: 1, 99 | join: "," }}', { items: [ 'a', 'b', 'c' ] })
        ).toBe('b,c');
    });

    test('with start beyond length returns empty', () => {
        expect(
            curlytag.parse('{{ items | slice: 10 | join: "," }}', { items: [ 'a', 'b', 'c' ] })
        ).toBe('');
    });

    test('on empty array returns empty', () => {
        expect(curlytag.parse('{{ items | slice: 0, 2 | join: "," }}', { items: [] })).toBe('');
    });
});
