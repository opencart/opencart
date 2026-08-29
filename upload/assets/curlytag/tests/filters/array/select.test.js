import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('select', () => {
    test('filters items by truthy key', () => {
        const data = { items: [ { active: true }, { active: false }, { active: true } ] };
        expect(curlytag.parse('{{ items | select: "active" | length }}', data)).toBe('2');
    });

    test('returns empty array that is chainable', () => {
        const data = { items: [ { active: false }, { active: false } ] };
        expect(curlytag.parse('{{ items | select: "active" | join: "," }}', data)).toBe('');
    });

    test('returns all when every item matches', () => {
        const data = { items: [ { x: 1 }, { x: 2 } ] };
        expect(curlytag.parse('{{ items | select: "x" | length }}', data)).toBe('2');
    });

    test('excludes items where key is missing', () => {
        const data = { items: [ { active: true }, {}, { active: true } ] };
        expect(curlytag.parse('{{ items | select: "active" | length }}', data)).toBe('2');
    });

    test('excludes items where key is null', () => {
        const data = { items: [ { active: null }, { active: true }, { active: null } ] };
        expect(curlytag.parse('{{ items | select: "active" | length }}', data)).toBe('1');
    });

    test('excludes items where key is zero', () => {
        const data = { items: [ { stock: 0 }, { stock: 5 }, { stock: 0 } ] };
        expect(curlytag.parse('{{ items | select: "stock" | length }}', data)).toBe('1');
    });

    test('keeps items with truthy string value', () => {
        const data = { items: [ { category: 'news' }, { category: '' }, { category: 'sport' } ] };
        expect(curlytag.parse('{{ items | select: "category" | length }}', data)).toBe('2');
    });

    test('on empty array returns empty', () => {
        const data = { items: [] };
        expect(curlytag.parse('{{ items | select: "active" | join: "," }}', data)).toBe('');
    });
});
