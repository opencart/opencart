import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('array', () => {
    test('join', () => {
        expect(template.parse('{{ items | join: ", " }}', { items: ['a', 'b', 'c'] })).toBe(
            'a, b, c',
        );
    });

    test('reverse', () => {
        expect(template.parse('{{ items | reverse | join: "" }}', { items: ['a', 'b', 'c'] })).toBe(
            'cba',
        );
    });

    test('first and last', () => {
        const data = { items: [10, 20, 30] };
        expect(template.parse('{{ items | first }}', data)).toBe('10');
        expect(template.parse('{{ items | last }}', data)).toBe('30');
    });

    test('length', () => {
        expect(template.parse('{{ items | length }}', { items: [1, 2, 3] })).toBe('3');
    });
});
