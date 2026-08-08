import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('assign', () => {
    test('sets a variable in context', () => {
        expect(template.parse('{% assign x = 42 %}{{ x }}')).toBe('42');
    });

    test('with filter', () => {
        expect(template.parse('{% assign name = "alice" | upper %}{{ name }}')).toBe('ALICE');
    });

    test('invalid syntax is silently ignored', () => {
        expect(template.parse('{% assign %}rest')).toBe('rest');
    });
});
