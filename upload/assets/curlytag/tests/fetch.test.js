import { afterEach, beforeEach, describe, expect, test, vi } from 'vite-plus/test';
import * as nodefs from 'node:fs/promises';
import { template } from '#curlytag';

vi.mock('node:fs/promises', () => ({
    readFile: vi.fn(),
}));

describe('CurlyTag fetch() path construction', () => {
    beforeEach(() => {
        vi.mocked(nodefs.readFile).mockResolvedValue('');
        template.path.clear();
        template.directory = '';
        template.cache.clear();
    });

    afterEach(() => {
        vi.clearAllMocks();
    });

    describe('directory (default path)', () => {
        test('simple path: directory + path + .html', async () => {
            template.directory = '/templates/';
            await template.fetch('greeting');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/templates/greeting.html',
                'utf-8',
            );
        });

        test('one-level nested path keeps directory prefix', async () => {
            template.directory = '/templates/';
            await template.fetch('sub/page');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/templates/sub/page.html',
                'utf-8',
            );
        });

        test('deeply nested path keeps directory prefix', async () => {
            template.directory = '/var/www/views/';
            await template.fetch('admin/user/list');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/var/www/views/admin/user/list.html',
                'utf-8',
            );
        });

        test('addPath with single arg sets directory', async () => {
            template.addPath('/my/templates/');
            await template.fetch('index');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/my/templates/index.html',
                'utf-8',
            );
        });

        test('empty directory produces path starting from root segment', async () => {
            template.directory = '';
            await template.fetch('page');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith('page.html', 'utf-8');
        });
    });

    describe('namespace path resolution', () => {
        test('first segment namespace suffix is appended correctly', async () => {
            template.addPath('catalog', '/themes/catalog');
            await template.fetch('catalog/product/view');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/themes/catalog/product/view.html',
                'utf-8',
            );
        });

        test('first segment namespace exact match produces no suffix', async () => {
            template.addPath('catalog', '/themes/catalog');
            await template.fetch('catalog');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/themes/catalog.html',
                'utf-8',
            );
        });

        test('first segment namespace one nested segment', async () => {
            template.addPath('admin', '/var/admin');
            await template.fetch('admin/dashboard');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/var/admin/dashboard.html',
                'utf-8',
            );
        });

        test('first segment namespace three nested segments', async () => {
            template.addPath('account', '/themes/account');
            await template.fetch('account/orders/detail');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/themes/account/orders/detail.html',
                'utf-8',
            );
        });

        test('deep namespace mid depth match builds correct path', async () => {
            template.addPath('catalog/product', '/themes/product');
            await template.fetch('catalog/product/view');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/themes/product/view.html',
                'utf-8',
            );
        });

        test('deep namespace exact match produces no suffix', async () => {
            template.addPath('catalog/product', '/themes/product');
            await template.fetch('catalog/product');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/themes/product.html',
                'utf-8',
            );
        });

        test('most specific namespace wins when multiple namespaces match', async () => {
            template.addPath('catalog', '/themes/catalog');
            template.addPath('catalog/product', '/themes/product');
            await template.fetch('catalog/product/view');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/themes/product/view.html',
                'utf-8',
            );
        });

        test('shallower namespace wins when deep namespace does not match', async () => {
            template.addPath('catalog', '/themes/catalog');
            template.addPath('catalog/product', '/themes/product');
            await template.fetch('catalog/category/list');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/themes/catalog/category/list.html',
                'utf-8',
            );
        });

        test('falls back to directory when no namespace matches', async () => {
            template.directory = '/templates/';
            template.addPath('catalog', '/themes/catalog');
            await template.fetch('admin/dashboard');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/templates/admin/dashboard.html',
                'utf-8',
            );
        });

        test('namespace does not affect path with different prefix', async () => {
            template.addPath('shop', '/shop-templates');
            await template.fetch('blog/post');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith('blog/post.html', 'utf-8');
        });

        test('three namespaces registered correct one is selected', async () => {
            template.addPath('a', '/dir-a');
            template.addPath('b', '/dir-b');
            template.addPath('c', '/dir-c');
            await template.fetch('b/index');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith('/dir-b/index.html', 'utf-8');
        });

        test('directory is ignored when namespace matches', async () => {
            template.directory = '/fallback/';
            template.addPath('catalog', '/themes/catalog');
            await template.fetch('catalog/view');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/themes/catalog/view.html',
                'utf-8',
            );
        });
    });

    describe('addPath()', () => {
        test('two args register namespace in this.path map', async () => {
            template.addPath('ns', '/path/to/ns');
            expect(template.path.has('ns')).toBe(true);
            expect(template.path.get('ns')).toBe('/path/to/ns');
        });

        test('one arg sets this.directory', async () => {
            template.addPath('/base/');
            expect(template.directory).toBe('/base/');
        });

        test('multiple addPath calls with namespaces accumulate independently', async () => {
            template.addPath('a', '/dir-a');
            template.addPath('b', '/dir-b');

            await template.fetch('a/file');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith('/dir-a/file.html', 'utf-8');

            vi.mocked(nodefs.readFile).mockClear();

            await template.fetch('b/file');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith('/dir-b/file.html', 'utf-8');
        });

        test('overwriting namespace replaces previous path', async () => {
            template.addPath('ns', '/old-path');
            template.addPath('ns', '/new-path');
            await template.fetch('ns/file');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith('/new-path/file.html', 'utf-8');
        });
    });

    describe('error handling', () => {
        test('returns empty string when file does not exist', async () => {
            vi.mocked(nodefs.readFile).mockRejectedValue(
                Object.assign(new Error('ENOENT'), { code: 'ENOENT' }),
            );
            const result = await template.fetch('missing/file');
            expect(result).toBe('');
        });

        test('returns empty string when file read throws permission error', async () => {
            vi.mocked(nodefs.readFile).mockRejectedValue(
                Object.assign(new Error('EACCES'), { code: 'EACCES' }),
            );
            const result = await template.fetch('protected/file');
            expect(result).toBe('');
        });

        test('returns content when file exists', async () => {
            vi.mocked(nodefs.readFile).mockResolvedValue('Hello {{ name }}!');
            const result = await template.fetch('greeting');
            expect(result).toBe('Hello {{ name }}!');
        });
    });

    describe('edge cases', () => {
        test('empty path with no directory produces just .html', async () => {
            await template.fetch('');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith('.html', 'utf-8');
        });

        test('empty path with directory produces directory + .html', async () => {
            template.directory = '/templates/';
            await template.fetch('');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith('/templates/.html', 'utf-8');
        });

        test('leading slash is preserved as part of path', async () => {
            template.directory = '/templates';
            await template.fetch('/page');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/templates/page.html',
                'utf-8',
            );
        });

        test('trailing slash is preserved as part of path', async () => {
            template.directory = '/templates/';
            await template.fetch('page/');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/templates/page/.html',
                'utf-8',
            );
        });

        test('double slashes in path are preserved literally', async () => {
            template.directory = '/templates/';
            await template.fetch('sub//page');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/templates/sub//page.html',
                'utf-8',
            );
        });

        test('path with dot segments is preserved literally', async () => {
            template.directory = '/templates/';
            await template.fetch('sub/./page');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/templates/sub/./page.html',
                'utf-8',
            );
        });

        test('namespace matches full segment only not prefix', async () => {
            template.addPath('cat', '/short');
            await template.fetch('catalog/page');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith('catalog/page.html', 'utf-8');
        });

        test('namespace with trailing slash in stored path is preserved', async () => {
            template.addPath('ns', '/dir/');
            await template.fetch('ns/page');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith('/dir//page.html', 'utf-8');
        });

        test('path containing dots in filename is preserved', async () => {
            template.directory = '/templates/';
            await template.fetch('page.v2');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/templates/page.v2.html',
                'utf-8',
            );
        });

        test('path with spaces is preserved literally', async () => {
            template.directory = '/templates/';
            await template.fetch('my page');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/templates/my page.html',
                'utf-8',
            );
        });

        test('path with unicode characters is preserved', async () => {
            template.directory = '/templates/';
            await template.fetch('страница');
            expect(vi.mocked(nodefs.readFile)).toHaveBeenCalledWith(
                '/templates/страница.html',
                'utf-8',
            );
        });
    });
});
