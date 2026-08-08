import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('normalize_whitespace', () => {
    test('collapses multiple spaces into one', () => {
        expect(
            template.parse('{{ text | normalize_whitespace }}', { text: 'hello   world' }),
        ).toBe('hello world');
    });

    test('collapses tabs into single space', () => {
        expect(
            template.parse('{{ text | normalize_whitespace }}', { text: 'hello\t\tworld' }),
        ).toBe('hello world');
    });

    test('collapses newlines into single space', () => {
        expect(
            template.parse('{{ text | normalize_whitespace }}', { text: 'hello\n\nworld' }),
        ).toBe('hello world');
    });

    test('collapses mixed whitespace into single space', () => {
        expect(
            template.parse('{{ text | normalize_whitespace }}', { text: 'hello \t\n world' }),
        ).toBe('hello world');
    });

    test('trims leading whitespace', () => {
        expect(
            template.parse('{{ text | normalize_whitespace }}', { text: '   hello' }),
        ).toBe('hello');
    });

    test('trims trailing whitespace', () => {
        expect(
            template.parse('{{ text | normalize_whitespace }}', { text: 'hello   ' }),
        ).toBe('hello');
    });

    test('trims and collapses in one pass', () => {
        expect(
            template.parse('{{ text | normalize_whitespace }}', { text: '  hello   world  ' }),
        ).toBe('hello world');
    });

    test('string without extra whitespace is unchanged', () => {
        expect(
            template.parse('{{ text | normalize_whitespace }}', { text: 'hello world' }),
        ).toBe('hello world');
    });

    test('empty string returns empty string', () => {
        expect(template.parse('{{ text | normalize_whitespace }}', { text: '' })).toBe('');
    });

    test('whitespace-only string returns empty string', () => {
        expect(template.parse('{{ text | normalize_whitespace }}', { text: '   \t\n  ' })).toBe('');
    });

    test('null returns empty string', () => {
        expect(template.parse('{{ text | normalize_whitespace }}', { text: null })).toBe('');
    });

    test('undefined variable returns empty string', () => {
        expect(template.parse('{{ text | normalize_whitespace }}', {})).toBe('');
    });

    test('chains with other filters', () => {
        expect(
            template.parse('{{ text | normalize_whitespace | upper }}', { text: '  hello   world  ' }),
        ).toBe('HELLO WORLD');
    });
});
