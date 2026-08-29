import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('assign', () => {
    test('sets a variable in context', () => {
        expect(curlytag.parse('{% assign x = 42 %}{{ x }}')).toBe('42');
    });

    test('with filter', () => {
        expect(curlytag.parse('{% assign name = "alice" | upper %}{{ name }}')).toBe('ALICE');
    });

    test('invalid syntax is silently ignored', () => {
        expect(curlytag.parse('{% assign %}rest')).toBe('rest');
    });
});
