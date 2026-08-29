import { afterEach, beforeEach, describe, expect, test, vi } from 'vite-plus/test';
import { curlytag } from '#curlytag';
import nestedConditionsTemplate from './fixtures/nested-conditions.html?raw';
import productCardTemplate from './fixtures/product-card.html?raw';
import userListTemplate from './fixtures/user-list.html?raw';

const resetCurlytag = () => {
    curlytag.directory = '';
    curlytag.path.clear();
    curlytag.cache.clear();
};

describe('CurlyTag - multi-line templates', () => {
    beforeEach(() => {
        resetCurlytag();
    });

    afterEach(() => {
        resetCurlytag();
        vi.restoreAllMocks();
    });

    describe('product card', () => {
        test('in-stock product with tags', () => {
            const result = curlytag.parse(productCardTemplate, {
                product: {
                    name: 'Widget',
                    price: 19.995,
                    in_stock: true,
                    tags: [ 'new', 'sale' ]
                }
            });

            expect(result).toContain('WIDGET');
            expect(result).toContain('20.00');
            expect(result).toContain('In Stock');
            expect(result).not.toContain('Sold Out');
            expect(result).toContain('<li>new</li>');
            expect(result).toContain('<li>sale</li>');
        });

        test('sold-out product without tags', () => {
            const result = curlytag.parse(productCardTemplate, {
                product: {
                    name: 'Gadget',
                    price: 5.5,
                    in_stock: false,
                    tags: null
                }
            });

            expect(result).toContain('GADGET');
            expect(result).toContain('Sold Out');
            expect(result).not.toContain('In Stock');
            expect(result).not.toContain('<li>');
        });
    });

    describe('user list table', () => {
        test('renders rows with loop.index and default role', () => {
            const result = curlytag.parse(userListTemplate, {
                users: [ { name: 'Alice', role: 'admin' }, { name: 'Bob' } ]
            });

            expect(result).toContain('<td>1</td>');
            expect(result).toContain('<td>Alice</td>');
            expect(result).toContain('<td>admin</td>');
            expect(result).toContain('<td>2</td>');
            expect(result).toContain('<td>Bob</td>');
            expect(result).toContain('<td>member</td>');
        });

        test('empty users renders no rows', () => {
            const result = curlytag.parse(userListTemplate, { users: [] });

            expect(result).toContain('<thead>');
            expect(result).not.toContain('<td>');
        });
    });

    describe('nested conditions', () => {
        test('admin with permissions', () => {
            const result = curlytag.parse(nestedConditionsTemplate, {
                user: {
                    name: 'Root',
                    is_admin: true,
                    permissions: [ 'read', 'write', 'delete' ]
                }
            });

            expect(result).toContain('Root');
            expect(result).toContain('Administrator');
            expect(result).toContain('<li>read</li>');
            expect(result).toContain('<li>write</li>');
            expect(result).toContain('<li>delete</li>');
            expect(result).not.toContain('Moderator');
            expect(result).not.toContain('Please log in');
        });

        test('moderator', () => {
            const result = curlytag.parse(nestedConditionsTemplate, {
                user: {
                    name: 'Mod',
                    is_admin: false,
                    is_moderator: true
                }
            });

            expect(result).toContain('Moderator');
            expect(result).not.toContain('Administrator');
        });

        test('regular user', () => {
            const result = curlytag.parse(nestedConditionsTemplate, {
                user: {
                    name: 'Guest',
                    is_admin: false,
                    is_moderator: false
                }
            });

            expect(result).toContain('<span class="role">User</span>');
            expect(result).not.toContain('Administrator');
            expect(result).not.toContain('Moderator');
        });

        test('no user shows login prompt', () => {
            const result = curlytag.parse(nestedConditionsTemplate, {});

            expect(result).toContain('Please log in');
            expect(result).not.toContain('profile');
        });
    });
});
