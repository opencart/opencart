import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('text output', () => {
    test('plain text passes through unchanged', () => {
        expect(template.parse('hello world')).toBe('hello world');
    });

    test('empty string returns empty', () => {
        expect(template.parse('')).toBe('');
    });

    test('only whitespace is preserved', () => {
        expect(template.parse('   \n\t  ')).toBe('   \n\t  ');
    });

    test('special characters pass through', () => {
        expect(template.parse('Price: $100 & 50% off')).toBe('Price: $100 & 50% off');
    });
});
