import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('misc', () => {
    test('default provides fallback', () => {
        expect(curlytag.parse('{{ missing | default: "none" }}')).toBe('none');
    });

    test('chained filters', () => {
        expect(curlytag.parse('{{ name | upper | truncate: 3, "." }}', { name: 'alice' })).toBe(
            'AL.'
        );
    });

    test('unknown filter returns empty', () => {
        const result = curlytag.parse('{{ name | nonexistent_filter }}', { name: 'test' });
        expect(result).toBe('');
    });
});
