import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('size', () => {
    test('returns length of a string', () => {
        expect(template.parse('{{ value | size }}', { value: 'hello' })).toBe('5');
    });

    test('returns character count of a longer string', () => {
        expect(
            template.parse('{{ value | size }}', { value: 'Ground control to Major Tom.' }),
        ).toBe('28');
    });

    test('returns length of an array', () => {
        expect(template.parse('{{ items | size }}', { items: [1, 2, 3, 4] })).toBe('4');
    });

    test('returns 0 for empty string', () => {
        expect(template.parse('{{ value | size }}', { value: '' })).toBe('0');
    });

    test('returns 0 for empty array', () => {
        expect(template.parse('{{ items | size }}', { items: [] })).toBe('0');
    });

    test('returns 0 for non-string non-array value', () => {
        expect(template.parse('{{ value | size }}', { value: 42 })).toBe('0');
    });

    test('behaves identically to length', () => {
        const data = { items: ['a', 'b', 'c'] };
        expect(template.parse('{{ items | size }}', data)).toBe(
            template.parse('{{ items | length }}', data),
        );
    });
});
