import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('variable output {{ }}', () => {
    test('renders a simple variable', () => {
        expect(template.parse('{{ name }}', { name: 'Alice' })).toBe('Alice');
    });

    test('escapes HTML by default', () => {
        expect(template.parse('{{ html }}', { html: '<b>bold</b>' })).toBe(
            '&lt;b&gt;bold&lt;/b&gt;',
        );
    });

    test('dot notation for nested objects', () => {
        expect(template.parse('{{ user.name }}', { user: { name: 'Bob' } })).toBe('Bob');
    });

    test('deep nesting', () => {
        expect(template.parse('{{ a.b.c }}', { a: { b: { c: 'deep' } } })).toBe('deep');
    });

    test('undefined variable renders as empty string', () => {
        expect(template.parse('{{ missing }}')).toBe('');
    });

    test('null renders as empty string', () => {
        expect(template.parse('{{ v }}', { v: null })).toBe('');
    });

    test('empty string renders as empty', () => {
        expect(template.parse('{{ v }}', { v: '' })).toBe('');
    });

    test('numeric zero renders as "0"', () => {
        expect(template.parse('{{ n }}', { n: 0 })).toBe('0');
    });

    test('boolean false renders as "false"', () => {
        expect(template.parse('{{ v }}', { v: false })).toBe('false');
    });
});
