import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('text output', () => {
    test('plain text passes through unchanged', () => {
        expect(curlytag.parse('hello world')).toBe('hello world');
    });

    test('empty string returns empty', () => {
        expect(curlytag.parse('')).toBe('');
    });

    test('only whitespace is preserved', () => {
        expect(curlytag.parse('   \n\t  ')).toBe('   \n\t  ');
    });

    test('special characters pass through', () => {
        expect(curlytag.parse('Price: $100 & 50% off')).toBe('Price: $100 & 50% off');
    });
});
