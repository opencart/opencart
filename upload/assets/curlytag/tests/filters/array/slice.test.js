import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('slice', () => {
    test('with start and end returns subarray', () => {
        expect(
            template.parse('{{ items | slice: 1, 3 | join: "," }}', {
                items: ['a', 'b', 'c', 'd'],
            }),
        ).toBe('b,c');
    });

    test('with start only returns tail', () => {
        expect(
            template.parse('{{ items | slice: 2 | join: "," }}', { items: ['a', 'b', 'c', 'd'] }),
        ).toBe('c,d');
    });

    test('with zero start and end returns prefix', () => {
        expect(
            template.parse('{{ items | slice: 0, 2 | join: "," }}', { items: ['x', 'y', 'z'] }),
        ).toBe('x,y');
    });

    test('with negative start returns last N items', () => {
        expect(
            template.parse('{{ items | slice: -2 | join: "," }}', { items: ['a', 'b', 'c', 'd'] }),
        ).toBe('c,d');
    });

    test('with end beyond length returns elements to end', () => {
        expect(
            template.parse('{{ items | slice: 1, 99 | join: "," }}', { items: ['a', 'b', 'c'] }),
        ).toBe('b,c');
    });

    test('with start beyond length returns empty', () => {
        expect(
            template.parse('{{ items | slice: 10 | join: "," }}', { items: ['a', 'b', 'c'] }),
        ).toBe('');
    });

    test('on empty array returns empty', () => {
        expect(template.parse('{{ items | slice: 0, 2 | join: "," }}', { items: [] })).toBe('');
    });
});
