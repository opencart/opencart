import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('array_to_sentence_string', () => {
    test('joins three items with and', () => {
        expect(template.parse('{{ value | array_to_sentence_string }}', { value: ['one', 'two', 'three'] })).toBe('one, two, and three');
    });

    test('joins two items with and', () => {
        expect(template.parse('{{ value | array_to_sentence_string }}', { value: ['one', 'two'] })).toBe('one and two');
    });

    test('returns single item as string', () => {
        expect(template.parse('{{ value | array_to_sentence_string }}', { value: ['one'] })).toBe('one');
    });

    test('returns empty string for empty array', () => {
        expect(template.parse('{{ value | array_to_sentence_string }}', { value: [] })).toBe('');
    });

    test('uses custom connector', () => {
        expect(template.parse("{{ value | array_to_sentence_string: 'or' }}", { value: ['one', 'two', 'three'] })).toBe('one, two, or three');
    });

    test('uses custom connector for two items', () => {
        expect(template.parse("{{ value | array_to_sentence_string: 'or' }}", { value: ['one', 'two'] })).toBe('one or two');
    });

    test('handles four items', () => {
        expect(template.parse('{{ value | array_to_sentence_string }}', { value: ['a', 'b', 'c', 'd'] })).toBe('a, b, c, and d');
    });

    test('handles numeric values', () => {
        expect(template.parse('{{ value | array_to_sentence_string }}', { value: [1, 2, 3] })).toBe('1, 2, and 3');
    });

    test('skips null items', () => {
        expect(template.parse('{{ value | array_to_sentence_string }}', { value: ['one', null, 'three'] })).toBe('one and three');
    });

    test('skips undefined items', () => {
        expect(template.parse('{{ value | array_to_sentence_string }}', { value: ['one', undefined, 'three'] })).toBe('one and three');
    });

    test('returns empty string for array of nulls', () => {
        expect(template.parse('{{ value | array_to_sentence_string }}', { value: [null, null] })).toBe('');
    });
});
