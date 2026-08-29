import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('variable output {{ }}', () => {
    test('renders a simple variable', () => {
        expect(curlytag.parse('{{ name }}', { name: 'Alice' })).toBe('Alice');
    });

    test('escapes HTML by default', () => {
        expect(curlytag.parse('{{ html }}', { html: '<b>bold</b>' })).toBe(
            '&lt;b&gt;bold&lt;/b&gt;'
        );
    });

    test('dot notation for nested objects', () => {
        expect(curlytag.parse('{{ user.name }}', { user: { name: 'Bob' } })).toBe('Bob');
    });

    test('deep nesting', () => {
        expect(curlytag.parse('{{ a.b.c }}', { a: { b: { c: 'deep' } } })).toBe('deep');
    });

    test('four-level nesting', () => {
        expect(curlytag.parse('{{ catalog.product.manufacturer.name }}', {
            catalog: { product: { manufacturer: { name: 'OpenCart' } } }
        })).toBe('OpenCart');
    });

    test('missing nested value renders as empty string', () => {
        expect(curlytag.parse('{{ catalog.product.manufacturer.name }}', { catalog: {} })).toBe('');
    });

    test('undefined variable renders as empty string', () => {
        expect(curlytag.parse('{{ missing }}')).toBe('');
    });

    test('null renders as empty string', () => {
        expect(curlytag.parse('{{ v }}', { v: null })).toBe('');
    });

    test('empty string renders as empty', () => {
        expect(curlytag.parse('{{ v }}', { v: '' })).toBe('');
    });

    test('numeric zero renders as "0"', () => {
        expect(curlytag.parse('{{ n }}', { n: 0 })).toBe('0');
    });

    test('boolean false renders as "false"', () => {
        expect(curlytag.parse('{{ v }}', { v: false })).toBe('false');
    });
});
