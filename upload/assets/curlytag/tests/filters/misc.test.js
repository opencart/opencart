import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('misc', () => {
    test('default provides fallback', () => {
        expect(template.parse('{{ missing | default: "none" }}')).toBe('none');
    });

    test('chained filters', () => {
        expect(template.parse('{{ name | upper | truncate: 3, "." }}', { name: 'alice' })).toBe(
            'AL.',
        );
    });

    test('unknown filter returns empty', () => {
        const result = template.parse('{{ name | nonexistent_filter }}', { name: 'test' });
        expect(result).toBe('');
    });
});
