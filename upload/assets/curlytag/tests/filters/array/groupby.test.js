import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('groupby', () => {
    const items = [
        { name: 'apple', type: 'fruit' },
        { name: 'carrot', type: 'vegetable' },
        { name: 'banana', type: 'fruit' },
        { name: 'broccoli', type: 'vegetable' }
    ];

    test('groups items by the specified key', () => {
        const result = curlytag.filter.groupby(items, 'type');
        expect(result.fruit).toHaveLength(2);
        expect(result.vegetable).toHaveLength(2);
    });

    test('groups items into correct buckets', () => {
        const result = curlytag.filter.groupby(items, 'type');
        expect(result.fruit.map((i) => i.name)).toEqual([ 'apple', 'banana' ]);
        expect(result.vegetable.map((i) => i.name)).toEqual([ 'carrot', 'broccoli' ]);
    });

    test('groups by a key that is not named type', () => {
        const products = [
            { name: 'shirt', color: 'red' },
            { name: 'hat', color: 'blue' },
            { name: 'scarf', color: 'red' }
        ];
        const result = curlytag.filter.groupby(products, 'color');
        expect(result.red).toHaveLength(2);
        expect(result.blue).toHaveLength(1);
    });

    test('uses the parameter key, not the hardcoded type property', () => {
        const mixed = [
            { name: 'apple', type: 'fruit', category: 'produce' },
            { name: 'gold', type: 'metal', category: 'material' },
            { name: 'banana', type: 'fruit', category: 'produce' }
        ];
        const result = curlytag.filter.groupby(mixed, 'category');
        expect(result.produce).toHaveLength(2);
        expect(result.material).toHaveLength(1);
        expect(result.fruit).toBeUndefined();
        expect(result.metal).toBeUndefined();
    });

    test('single item produces one group', () => {
        const result = curlytag.filter.groupby([ { name: 'apple', type: 'fruit' } ], 'type');
        expect(result.fruit).toHaveLength(1);
    });

    test('empty array produces no groups', () => {
        const result = curlytag.filter.groupby([], 'type');
        expect(Object.keys(result)).toHaveLength(0);
    });

    test('missing key groups all items under undefined', () => {
        const result = curlytag.filter.groupby(items, 'nonexistent');
        expect(result[undefined]).toHaveLength(items.length);
    });

    test('works via template assign and property access', () => {
        const tpl
            = '{% assign g = items | groupby: "type" %}{{ g.fruit | length }}/{{ g.vegetable | length }}';
        expect(curlytag.parse(tpl, { items })).toBe('2/2');
    });

    test('group result is accessible in a for loop', () => {
        const tpl
            = '{% assign g = items | groupby: "type" %}{% for item in g.fruit %}{{ item.name }} {% endfor %}';
        expect(curlytag.parse(tpl, { items })).toBe('apple banana ');
    });
});
