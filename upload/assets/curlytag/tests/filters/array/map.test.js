import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('map', () => {
    test('extracts a property from each object', () => {
        const books = [{ title: 'A' }, { title: 'B' }, { title: 'C' }];
        expect(
            template.parse('{{ books | map: "title" | join: "," }}', { books }),
        ).toBe('A,B,C');
    });

    test('extracts numeric property', () => {
        const items = [{ score: 1 }, { score: 2 }, { score: 3 }];
        expect(
            template.parse('{{ items | map: "score" | join: "," }}', { items }),
        ).toBe('1,2,3');
    });

    test('returns undefined for missing property', () => {
        const items = [{ name: 'x' }, { name: 'y' }];
        expect(
            template.parse('{{ items | map: "age" | join: "," }}', { items }),
        ).toBe(',');
    });

    test('handles mixed presence of property', () => {
        const items = [{ title: 'A' }, {}, { title: 'C' }];
        expect(
            template.parse('{{ items | map: "title" | join: "," }}', { items }),
        ).toBe('A,,C');
    });

    test('preserves order', () => {
        const items = [{ v: 3 }, { v: 1 }, { v: 2 }];
        expect(
            template.parse('{{ items | map: "v" | join: "," }}', { items }),
        ).toBe('3,1,2');
    });

    test('returns empty array for empty input', () => {
        expect(
            template.parse('{{ items | map: "name" | join: "," }}', { items: [] }),
        ).toBe('');
    });

    test('can chain with other filters', () => {
        const users = [{ name: 'alice' }, { name: 'bob' }, { name: 'alice' }];
        expect(
            template.parse('{{ users | map: "name" | uniq | join: "," }}', { users }),
        ).toBe('alice,bob');
    });

    test('works with nested property access chaining map twice', () => {
        const items = [{ meta: { tag: 'js' } }, { meta: { tag: 'ts' } }];
        expect(
            template.parse('{{ items | map: "meta" | map: "tag" | join: "," }}', { items }),
        ).toBe('js,ts');
    });
});
