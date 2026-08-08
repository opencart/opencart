import { readFileSync } from 'node:fs';
import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

const fixture = (name) => readFileSync(new URL(`fixtures/${name}`, import.meta.url), 'utf-8');

describe('CurlyTag - multi-line templates', () => {
    describe('product card', () => {
        const tpl = fixture('product-card.html');

        test('in-stock product with tags', () => {
            const result = template.parse(tpl, {
                product: {
                    name: 'Widget',
                    price: 19.995,
                    in_stock: true,
                    tags: ['new', 'sale'],
                },
            });

            expect(result).toContain('WIDGET');
            expect(result).toContain('20.00');
            expect(result).toContain('In Stock');
            expect(result).not.toContain('Sold Out');
            expect(result).toContain('<li>new</li>');
            expect(result).toContain('<li>sale</li>');
        });

        test('sold-out product without tags', () => {
            const result = template.parse(tpl, {
                product: {
                    name: 'Gadget',
                    price: 5.5,
                    in_stock: false,
                    tags: null,
                },
            });

            expect(result).toContain('GADGET');
            expect(result).toContain('Sold Out');
            expect(result).not.toContain('In Stock');
            expect(result).not.toContain('<li>');
        });
    });

    describe('user list table', () => {
        const tpl = fixture('user-list.html');

        test('renders rows with loop.index and default role', () => {
            const result = template.parse(tpl, {
                users: [{ name: 'Alice', role: 'admin' }, { name: 'Bob' }],
            });

            expect(result).toContain('<td>1</td>');
            expect(result).toContain('<td>Alice</td>');
            expect(result).toContain('<td>admin</td>');
            expect(result).toContain('<td>2</td>');
            expect(result).toContain('<td>Bob</td>');
            expect(result).toContain('<td>member</td>');
        });

        test('empty users renders no rows', () => {
            const result = template.parse(tpl, { users: [] });

            expect(result).toContain('<thead>');
            expect(result).not.toContain('<td>');
        });
    });

    describe('nested conditions', () => {
        const tpl = fixture('nested-conditions.html');

        test('admin with permissions', () => {
            const result = template.parse(tpl, {
                user: {
                    name: 'Root',
                    is_admin: true,
                    permissions: ['read', 'write', 'delete'],
                },
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
            const result = template.parse(tpl, {
                user: {
                    name: 'Mod',
                    is_admin: false,
                    is_moderator: true,
                },
            });

            expect(result).toContain('Moderator');
            expect(result).not.toContain('Administrator');
        });

        test('regular user', () => {
            const result = template.parse(tpl, {
                user: {
                    name: 'Guest',
                    is_admin: false,
                    is_moderator: false,
                },
            });

            expect(result).toContain('<span class="role">User</span>');
            expect(result).not.toContain('Administrator');
            expect(result).not.toContain('Moderator');
        });

        test('no user shows login prompt', () => {
            const result = template.parse(tpl, {});

            expect(result).toContain('Please log in');
            expect(result).not.toContain('profile');
        });
    });
});
