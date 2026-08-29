import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('reject', () => {
    test('excludes items by truthy key', () => {
        const data = { items: [ { active: true }, { active: false }, { active: false } ] };
        expect(curlytag.parse('{{ items | reject: "active" | length }}', data)).toBe('2');
    });

    test('returns empty array that is chainable', () => {
        const data = { items: [ { active: true }, { active: true } ] };
        expect(curlytag.parse('{{ items | reject: "active" | join: "," }}', data)).toBe('');
    });

    test('keeps all items when none match', () => {
        const data = { items: [ { active: false }, { active: false } ] };
        expect(curlytag.parse('{{ items | reject: "active" | length }}', data)).toBe('2');
    });

    test('keeps items where key is missing', () => {
        const data = { items: [ { active: true }, {}, { active: true } ] };
        expect(curlytag.parse('{{ items | reject: "active" | length }}', data)).toBe('1');
    });

    test('keeps items where key is null', () => {
        const data = { items: [ { active: null }, { active: true }, { active: null } ] };
        expect(curlytag.parse('{{ items | reject: "active" | length }}', data)).toBe('2');
    });

    test('keeps items where key is zero', () => {
        const data = { items: [ { stock: 0 }, { stock: 5 }, { stock: 0 } ] };
        expect(curlytag.parse('{{ items | reject: "stock" | length }}', data)).toBe('2');
    });

    test('keeps items with falsy empty string value', () => {
        const data = { items: [ { category: 'news' }, { category: '' }, { category: 'sport' } ] };
        expect(curlytag.parse('{{ items | reject: "category" | length }}', data)).toBe('1');
    });

    test('on empty array returns empty', () => {
        const data = { items: [] };
        expect(curlytag.parse('{{ items | reject: "active" | join: "," }}', data)).toBe('');
    });
});
