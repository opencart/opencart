import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('size', () => {
    test('returns length of a string', () => {
        expect(curlytag.parse('{{ value | size }}', { value: 'hello' })).toBe('5');
    });

    test('returns character count of a longer string', () => {
        expect(
            curlytag.parse('{{ value | size }}', { value: 'Ground control to Major Tom.' })
        ).toBe('28');
    });

    test('returns length of an array', () => {
        expect(curlytag.parse('{{ items | size }}', { items: [ 1, 2, 3, 4 ] })).toBe('4');
    });

    test('returns 0 for empty string', () => {
        expect(curlytag.parse('{{ value | size }}', { value: '' })).toBe('0');
    });

    test('returns 0 for empty array', () => {
        expect(curlytag.parse('{{ items | size }}', { items: [] })).toBe('0');
    });

    test('returns 0 for non-string non-array value', () => {
        expect(curlytag.parse('{{ value | size }}', { value: 42 })).toBe('0');
    });

    test('behaves identically to length', () => {
        const data = { items: [ 'a', 'b', 'c' ] };
        expect(curlytag.parse('{{ items | size }}', data)).toBe(
            curlytag.parse('{{ items | length }}', data)
        );
    });
});
