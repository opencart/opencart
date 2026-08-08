import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('replace', () => {
    test('replaces all occurrences', () => {
        expect(
            template.parse('{{ value | replace: "a", "x" }}', { value: 'banana' }),
        ).toBe('bxnxnx');
    });

    test('replaces single occurrence', () => {
        expect(
            template.parse('{{ value | replace: "world", "there" }}', { value: 'hello world' }),
        ).toBe('hello there');
    });

    test('returns original string when search not found', () => {
        expect(
            template.parse('{{ value | replace: "z", "x" }}', { value: 'hello' }),
        ).toBe('hello');
    });

    test('replaces with empty string by default', () => {
        expect(
            template.parse('{{ value | replace: "l" }}', { value: 'hello' }),
        ).toBe('heo');
    });

    test('handles . literally', () => {
        expect(
            template.parse('{{ value | replace: ".", "-" }}', { value: 'a.b.c' }),
        ).toBe('a-b-c');
    });

    test('handles * literally', () => {
        expect(
            template.parse('{{ value | replace: "*", "x" }}', { value: 'a*b*c' }),
        ).toBe('axbxc');
    });

    test('handles [ literally', () => {
        expect(
            template.parse('{{ value | replace: "[", "(" }}', { value: 'a[b[c' }),
        ).toBe('a(b(c');
    });

    test('handles $ literally', () => {
        expect(
            template.parse('{{ value | replace: "$", "USD" }}', { value: '1$2$' }),
        ).toBe('1USD2USD');
    });

    test('handles $& in replacement literally', () => {
        expect(
            template.parse('{{ value | replace: "x", "$&" }}', { value: 'axbxc' }),
        ).toBe('a$&amp;b$&amp;c');
    });

    test('handles $$ in replacement literally', () => {
        expect(
            template.parse('{{ value | replace: "x", "$$" }}', { value: 'axb' }),
        ).toBe('a$$b');
    });
});
