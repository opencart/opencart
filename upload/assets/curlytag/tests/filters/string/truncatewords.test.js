import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('truncatewords', () => {
    test('truncates to given word count', () => {
        expect(
            template.parse('{{ value | truncatewords: 3 }}', { value: 'one two three four five' }),
        ).toBe('one two three...');
    });

    test('returns original string when word count not exceeded', () => {
        expect(
            template.parse('{{ value | truncatewords: 5 }}', { value: 'one two three' }),
        ).toBe('one two three');
    });

    test('returns original string when word count equals word length', () => {
        expect(
            template.parse('{{ value | truncatewords: 3 }}', { value: 'one two three' }),
        ).toBe('one two three');
    });

    test('appends custom end string', () => {
        expect(
            template.parse('{{ value | truncatewords: 2, " [more]" }}', { value: 'one two three four' }),
        ).toBe('one two [more]');
    });

    test('appends empty end string', () => {
        expect(
            template.parse('{{ value | truncatewords: 2, "" }}', { value: 'one two three four' }),
        ).toBe('one two');
    });

    test('handles single word', () => {
        expect(
            template.parse('{{ value | truncatewords: 3 }}', { value: 'hello' }),
        ).toBe('hello');
    });

    test('handles empty string', () => {
        expect(
            template.parse('{{ value | truncatewords: 3 }}', { value: '' }),
        ).toBe('');
    });

    test('handles extra whitespace between words', () => {
        expect(
            template.parse('{{ value | truncatewords: 2 }}', { value: 'one   two   three' }),
        ).toBe('one two...');
    });

    test('trims leading and trailing spaces', () => {
        expect(
            template.parse('{{ value | truncatewords: 2 }}', { value: '  one two three  ' }),
        ).toBe('one two...');
    });

    test('returns empty string for count zero', () => {
        expect(
            template.parse('{{ value | truncatewords: 0 }}', { value: 'one two three' }),
        ).toBe('');
    });

    test('returns empty string for negative count', () => {
        expect(
            template.parse('{{ value | truncatewords: -1 }}', { value: 'one two three' }),
        ).toBe('');
    });

    test('handles emoji as words', () => {
        expect(
            template.parse('{{ value | truncatewords: 2 }}', { value: '🎉 🎊 🎈 done' }),
        ).toBe('🎉 🎊...');
    });

    test('handles emoji mixed with text', () => {
        expect(
            template.parse('{{ value | truncatewords: 2 }}', { value: 'hello 🎉 world' }),
        ).toBe('hello 🎉...');
    });
});
