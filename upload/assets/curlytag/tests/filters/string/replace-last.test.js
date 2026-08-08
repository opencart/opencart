import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('replace_last', () => {
    test('replaces only the last occurrence', () => {
        expect(
            template.parse('{{ value | replace_last: "o", "0" }}', { value: 'hello world' }),
        ).toBe('hello w0rld');
    });

    test('does not replace earlier occurrences', () => {
        expect(
            template.parse('{{ value | replace_last: "a", "x" }}', { value: 'banana' }),
        ).toBe('bananx');
    });

    test('returns original string when search not found', () => {
        expect(
            template.parse('{{ value | replace_last: "z", "x" }}', { value: 'hello' }),
        ).toBe('hello');
    });

    test('replaces with empty string by default', () => {
        expect(
            template.parse('{{ value | replace_last: "o" }}', { value: 'hello world' }),
        ).toBe('hello wrld');
    });

    test('replaces last occurrence at end of string', () => {
        expect(
            template.parse('{{ value | replace_last: "lo", "!" }}', { value: 'hello' }),
        ).toBe('hel!');
    });

    test('when only one occurrence, behaves same as replace_first', () => {
        expect(
            template.parse('{{ value | replace_last: "fox", "cat" }}', { value: 'fox' }),
        ).toBe('cat');
    });

    test('handles . literally', () => {
        expect(
            template.parse('{{ value | replace_last: ".", "-" }}', { value: 'a.b.c' }),
        ).toBe('a.b-c');
    });

    test('handles $ literally', () => {
        expect(
            template.parse('{{ value | replace_last: "$", "USD" }}', { value: '100$200$' }),
        ).toBe('100$200USD');
    });

    test('handles replacement pattern $& literally in replace string', () => {
        expect(
            template.parse('{{ value | replace_last: "x", "$&" }}', { value: 'axbxc' }),
        ).toBe('axb$&amp;c');
    });
});
