import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('compact', () => {
    test('removes null values', () => {
        expect(
            template.parse('{{ items | compact | join: "," }}', { items: [1, null, 2, null, 3] }),
        ).toBe('1,2,3');
    });

    test('removes undefined values', () => {
        expect(
            template.parse('{{ items | compact | join: "," }}', {
                items: [1, undefined, 2, undefined, 3],
            }),
        ).toBe('1,2,3');
    });

    test('removes both null and undefined', () => {
        expect(
            template.parse('{{ items | compact | join: "," }}', {
                items: [null, 'a', undefined, 'b', null],
            }),
        ).toBe('a,b');
    });

    test('preserves false values', () => {
        expect(
            template.parse('{{ items | compact | join: "," }}', {
                items: [false, null, true, undefined],
            }),
        ).toBe('false,true');
    });

    test('preserves zero values', () => {
        expect(
            template.parse('{{ items | compact | join: "," }}', { items: [0, null, 1, undefined] }),
        ).toBe('0,1');
    });

    test('preserves empty strings', () => {
        expect(
            template.parse('{{ items | compact | join: "," }}', {
                items: ['', null, 'a', undefined],
            }),
        ).toBe(',a');
    });

    test('returns array unchanged when no nullish values', () => {
        expect(
            template.parse('{{ items | compact | join: "," }}', { items: [1, 2, 3] }),
        ).toBe('1,2,3');
    });

    test('returns empty array when all values are null', () => {
        expect(
            template.parse('{{ items | compact | join: "," }}', {
                items: [null, null, undefined],
            }),
        ).toBe('');
    });

    test('works with string items', () => {
        expect(
            template.parse('{{ items | compact | join: ", " }}', {
                items: ['foo', null, 'bar', undefined, 'baz'],
            }),
        ).toBe('foo, bar, baz');
    });

    test('chains with other filters', () => {
        expect(
            template.parse('{{ items | compact | length }}', {
                items: [1, null, 2, null, 3],
            }),
        ).toBe('3');
    });

    test('empty array returns empty array', () => {
        expect(template.parse('{{ items | compact | length }}', { items: [] })).toBe('0');
    });
});
