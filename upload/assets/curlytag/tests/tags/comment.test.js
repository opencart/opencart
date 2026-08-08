import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('comment', () => {
    test('block comment is stripped', () => {
        expect(template.parse('A{% comment %}hidden{% endcomment %}B')).toBe('AB');
    });

    test('twig-style {# #} comment is stripped', () => {
        expect(template.parse('A{# this is a comment #}B')).toBe('AB');
    });

    test('empty comment produces no output', () => {
        expect(template.parse('{% comment %}{% endcomment %}')).toBe('');
    });
});
