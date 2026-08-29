import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('comment', () => {
    test('block comment is stripped', () => {
        expect(curlytag.parse('A{% comment %}hidden{% endcomment %}B')).toBe('AB');
    });

    test('twig-style {# #} comment is stripped', () => {
        expect(curlytag.parse('A{# this is a comment #}B')).toBe('AB');
    });

    test('empty comment produces no output', () => {
        expect(curlytag.parse('{% comment %}{% endcomment %}')).toBe('');
    });
});
