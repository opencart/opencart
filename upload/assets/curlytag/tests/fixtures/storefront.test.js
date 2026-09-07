import { afterEach, describe, expect, test, vi } from 'vite-plus/test';
import { curlytag } from '#curlytag';
import filterArgsTemplate from '#fixtures/storefront/filter-args.html?raw';
import filterChainTemplate from '#fixtures/storefront/filter-chain.html?raw';
import formatTemplate from '#fixtures/storefront/format.html?raw';
import navBlocksTemplate from '#fixtures/storefront/nav-blocks.html?raw';
import ratingStarsTemplate from '#fixtures/storefront/rating-stars.html?raw';
import reservedWordsTemplate from '#fixtures/storefront/reserved-words.html?raw';
import setCounterTemplate from '#fixtures/storefront/set-counter.html?raw';

const textContent = (output) => output.replace(/<[^>]+>/g, '').replace(/\s+/g, ' ').trim();

afterEach(() => {
    vi.restoreAllMocks();
});

describe('OpenCart storefront syntax', () => {
    test.fails('renders a numeric range as rating stars', () => {
        const result = curlytag.parse(ratingStarsTemplate, { rating: 3 });

        expect(textContent(result).replaceAll(' ', '')).toBe('★★★☆☆');
    });

    test('supports colon-style filter arguments when one argument is undefined', () => {
        const log = vi.spyOn(console, 'log').mockImplementation(() => {});
        const result = curlytag.parse(filterArgsTemplate, {
            headline: 'Featured %s',
            product_name: 'Camera',
            status: 'Status: %s',
        });

        expect(textContent(result)).toContain('Featured Camera');
        expect(textContent(result)).toContain('Status: %s');
        expect(log).toHaveBeenCalledWith(expect.stringContaining('[Template] Warning: Evaluate error'));
    });

    test.fails('supports the format filter in output and condition expressions', () => {
        const result = curlytag.parse(formatTemplate, {
            order: 'ASC',
            pattern: '%s × %s at %s',
            product_name: 'Camera',
            quantity: 2,
            sort: 'name',
            store_name: 'Example Store',
            sorts: [
                { href: '/name-asc', text: 'Name ascending', value: 'name-ASC' },
                { href: '/price-desc', text: 'Price descending', value: 'price-DESC' }
            ],
        });

        expect(textContent(result)).toBe(
            'Camera × 2 at Example Store Name ascending Price descending'
        );
        expect(result).toContain('<option value="/name-asc" selected>Name ascending</option>');
        expect(result).toContain('<option value="/price-desc">Price descending</option>');
    });

    test('sets and increments a counter inside a loop', () => {
        const result = curlytag.parse(setCounterTemplate, {
            products: ['Camera', 'Lens', 'Tripod'],
        });

        expect(textContent(result).replaceAll(' ', '')).toBe('2:Camera;3:Lens;4:Tripod;');
    });

    test.fails('renders JavaScript reserved words from the context', () => {
        const result = curlytag.parse(reservedWordsTemplate, {
            continue: 'next',
            class: 'featured',
            return: 'back',
        });

        expect(textContent(result)).toBe('next|featured|back');
    });

    test.fails('does not throw on a parenthesised filter call feeding a loop', () => {
        expect(() => curlytag.parse(filterChainTemplate, {
            banners: ['a', 'b', 'c', 'd', 'e'],
            items: 2,
            products: ['x', 'y', 'z'],
        })).not.toThrow();
    });

    test('renders nested navigation blocks and their empty branches', () => {
        const populated = curlytag.parse(navBlocksTemplate, {
            categories: [
                { name: 'Hardware', links: ['Cameras', 'Lenses'] },
                { name: 'Services', links: [] }
            ],
        });
        const empty = curlytag.parse(navBlocksTemplate, { categories: [] });

        expect(populated).toContain('<h2>Hardware</h2>');
        expect(populated).toContain('<li>Cameras</li>');
        expect(populated).toContain('<li>Lenses</li>');
        expect(populated).toContain('<h2>Services</h2>');
        expect(populated).toContain('<p>Coming soon</p>');
        expect(populated).not.toContain('<p>No categories</p>');
        expect(empty).toContain('<p>No categories</p>');
        expect(empty).not.toContain('<section>');
    });
});
