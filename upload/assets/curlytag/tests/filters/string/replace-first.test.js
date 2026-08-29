import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('replace_first', () => {
    test('replaces only the first occurrence', () => {
        expect(
            curlytag.parse('{{ value | replace_first: "a", "x" }}', { value: 'banana' })
        ).toBe('bxnana');
    });

    test('does not replace later occurrences', () => {
        expect(
            curlytag.parse('{{ value | replace_first: "o", "0" }}', { value: 'hello world' })
        ).toBe('hell0 world');
    });

    test('returns original string when search not found', () => {
        expect(
            curlytag.parse('{{ value | replace_first: "z", "x" }}', { value: 'hello' })
        ).toBe('hello');
    });

    test('replaces with empty string by default', () => {
        expect(
            curlytag.parse('{{ value | replace_first: "l" }}', { value: 'hello' })
        ).toBe('helo');
    });

    test('replaces at start of string', () => {
        expect(
            curlytag.parse('{{ value | replace_first: "he", "Hi" }}', { value: 'hello' })
        ).toBe('Hillo');
    });

    test('handles . literally', () => {
        expect(
            curlytag.parse('{{ value | replace_first: ".", "-" }}', { value: 'a.b.c' })
        ).toBe('a-b.c');
    });

    test('handles $ literally', () => {
        expect(
            curlytag.parse('{{ value | replace_first: "$", "USD" }}', { value: '1$2$' })
        ).toBe('1USD2$');
    });

    test('handles $& in replacement literally', () => {
        expect(
            curlytag.parse('{{ value | replace_first: "x", "$&" }}', { value: 'axbxc' })
        ).toBe('a$&amp;bxc');
    });

    test('handles $$ in replacement literally', () => {
        expect(
            curlytag.parse('{{ value | replace_first: "x", "$$" }}', { value: 'axb' })
        ).toBe('a$$b');
    });
});
