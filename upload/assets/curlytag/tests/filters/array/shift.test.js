import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('shift', () => {
    test('removes first item and result is chainable', () => {
        expect(template.parse('{{ items | shift | join: "," }}', { items: [1, 2, 3] })).toBe('2,3');
    });

    test('decreases length by one', () => {
        expect(template.parse('{{ items | shift | length }}', { items: ['a', 'b', 'c'] })).toBe(
            '2',
        );
    });

    test('on single-element array returns empty', () => {
        expect(template.parse('{{ items | shift | join: "," }}', { items: ['only'] })).toBe('');
    });

    test('removes the correct first element', () => {
        expect(template.parse('{{ items | shift | first }}', { items: ['a', 'b', 'c'] })).toBe('b');
    });

    test('on empty array returns empty', () => {
        expect(template.parse('{{ items | shift | join: "," }}', { items: [] })).toBe('');
    });

    test('does not mutate the original array', () => {
        const data = { items: [1, 2, 3] };
        template.parse('{{ items | shift | join: "," }}', data);
        expect(data.items.length).toBe(3);
    });
});
