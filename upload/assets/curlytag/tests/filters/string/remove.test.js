import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('remove', () => {
    test('removes all occurrences of a substring', () => {
        expect(template.parse('{{ value | remove: "o" }}', { value: 'hello world' })).toBe(
            'hell wrld',
        );
    });

    test('removes multiple occurrences', () => {
        expect(template.parse('{{ value | remove: "a" }}', { value: 'banana' })).toBe('bnn');
    });

    test('returns original string when substring not found', () => {
        expect(template.parse('{{ value | remove: "z" }}', { value: 'hello' })).toBe('hello');
    });

    test('removes empty string returns original', () => {
        expect(template.parse('{{ value | remove: "" }}', { value: 'hello' })).toBe('hello');
    });

    test('removes entire string when value equals search', () => {
        expect(template.parse('{{ value | remove: "hello" }}', { value: 'hello' })).toBe('');
    });

    test('handles regex special chars literally', () => {
        expect(template.parse('{{ value | remove: "." }}', { value: 'a.b.c' })).toBe('abc');
    });

    test('handles $ literally', () => {
        expect(template.parse('{{ value | remove: "$" }}', { value: '100$' })).toBe('100');
    });

    test('handles * literally', () => {
        expect(template.parse('{{ value | remove: "*" }}', { value: 'a*b*c' })).toBe('abc');
    });

    test('handles [ literally', () => {
        expect(template.parse('{{ value | remove: "[" }}', { value: 'a[b[c' })).toBe('abc');
    });

    test('handles replacement pattern $& literally', () => {
        expect(template.parse('{{ value | remove: "$&" }}', { value: 'a$&b$&c' })).toBe('abc');
    });

    test('handles replacement pattern $$ literally', () => {
        expect(template.parse('{{ value | remove: "$$" }}', { value: 'a$$b' })).toBe('ab');
    });
});

describe('remove_first', () => {
    test('removes only the first occurrence', () => {
        expect(template.parse('{{ value | remove_first: "o" }}', { value: 'hello world' })).toBe(
            'hell world',
        );
    });

    test('does not remove subsequent occurrences', () => {
        expect(template.parse('{{ value | remove_first: "a" }}', { value: 'banana' })).toBe(
            'bnana',
        );
    });

    test('returns original string when substring not found', () => {
        expect(template.parse('{{ value | remove_first: "z" }}', { value: 'hello' })).toBe(
            'hello',
        );
    });

    test('removes from the start of the string', () => {
        expect(template.parse('{{ value | remove_first: "he" }}', { value: 'hello' })).toBe('llo');
    });

    test('handles regex special chars literally', () => {
        expect(template.parse('{{ value | remove_first: "." }}', { value: 'a.b.c' })).toBe('ab.c');
    });

    test('handles $ literally', () => {
        expect(template.parse('{{ value | remove_first: "$" }}', { value: '100$200$' })).toBe('100200$');
    });

    test('handles * literally', () => {
        expect(template.parse('{{ value | remove_first: "*" }}', { value: 'a*b*c' })).toBe('ab*c');
    });

    test('handles replacement pattern $& literally', () => {
        expect(template.parse('{{ value | remove_first: "$&" }}', { value: 'x$&y$&z' })).toBe('xy$&amp;z');
    });
});

describe('remove_last', () => {
    test('removes only the last occurrence', () => {
        expect(template.parse('{{ value | remove_last: "o" }}', { value: 'hello world' })).toBe(
            'hello wrld',
        );
    });

    test('does not remove earlier occurrences', () => {
        expect(template.parse('{{ value | remove_last: "a" }}', { value: 'banana' })).toBe(
            'banan',
        );
    });

    test('returns original string when substring not found', () => {
        expect(template.parse('{{ value | remove_last: "z" }}', { value: 'hello' })).toBe('hello');
    });

    test('removes from the end of the string', () => {
        expect(template.parse('{{ value | remove_last: "lo" }}', { value: 'hello' })).toBe('hel');
    });

    test('when only one occurrence, behaves same as remove_first', () => {
        expect(template.parse('{{ value | remove_last: "x" }}', { value: 'fox' })).toBe('fo');
    });

    test('handles regex special chars literally', () => {
        expect(template.parse('{{ value | remove_last: "." }}', { value: 'a.b.c' })).toBe('a.bc');
    });

    test('handles $ literally', () => {
        expect(template.parse('{{ value | remove_last: "$" }}', { value: '100$200$' })).toBe('100$200');
    });
});
