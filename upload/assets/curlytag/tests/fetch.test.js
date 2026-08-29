import { afterEach, beforeEach, describe, expect, test, vi } from 'vite-plus/test';
import { curlytag } from '#curlytag';

let fetchSpy;

describe('CurlyTag fetch() path construction', () => {
    beforeEach(() => {
        fetchSpy = vi
            .spyOn(globalThis, 'fetch')
            .mockImplementation(() => Promise.resolve(new Response('', { status: 200 })));
        curlytag.path.clear();
        curlytag.directory = '';
        curlytag.cache.clear();
    });

    afterEach(() => {
        vi.restoreAllMocks();
    });

    describe('directory (default path)', () => {
        test('simple path: directory + path + .html', async () => {
            curlytag.directory = '/templates/';
            await curlytag.fetch('greeting');
            expect(fetchSpy).toHaveBeenCalledWith('/templates/greeting.html');
        });

        test('one-level nested path keeps directory prefix', async () => {
            curlytag.directory = '/templates/';
            await curlytag.fetch('sub/page');
            expect(fetchSpy).toHaveBeenCalledWith('/templates/sub/page.html');
        });

        test('deeply nested path keeps directory prefix', async () => {
            curlytag.directory = '/var/www/views/';
            await curlytag.fetch('admin/user/list');
            expect(fetchSpy).toHaveBeenCalledWith('/var/www/views/admin/user/list.html');
        });

        test('addPath with single arg sets directory', async () => {
            curlytag.addPath('/my/templates/');
            await curlytag.fetch('index');
            expect(fetchSpy).toHaveBeenCalledWith('/my/templates/index.html');
        });

        test('empty directory produces path starting from root segment', async () => {
            await curlytag.fetch('page');
            expect(fetchSpy).toHaveBeenCalledWith('page.html');
        });
    });

    describe('namespace path resolution', () => {
        test('first segment namespace suffix is appended correctly', async () => {
            curlytag.addPath('catalog', '/themes/catalog');
            await curlytag.fetch('catalog/product/view');
            expect(fetchSpy).toHaveBeenCalledWith('/themes/catalog/product/view.html');
        });

        test('first segment namespace exact match produces no suffix', async () => {
            curlytag.addPath('catalog', '/themes/catalog');
            await curlytag.fetch('catalog');
            expect(fetchSpy).toHaveBeenCalledWith('/themes/catalog.html');
        });

        test('first segment namespace one nested segment', async () => {
            curlytag.addPath('admin', '/var/admin');
            await curlytag.fetch('admin/dashboard');
            expect(fetchSpy).toHaveBeenCalledWith('/var/admin/dashboard.html');
        });

        test('first segment namespace three nested segments', async () => {
            curlytag.addPath('account', '/themes/account');
            await curlytag.fetch('account/orders/detail');
            expect(fetchSpy).toHaveBeenCalledWith('/themes/account/orders/detail.html');
        });

        test('deep namespace mid depth match builds correct path', async () => {
            curlytag.addPath('catalog/product', '/themes/product');
            await curlytag.fetch('catalog/product/view');
            expect(fetchSpy).toHaveBeenCalledWith('/themes/product/view.html');
        });

        test('deep namespace exact match produces no suffix', async () => {
            curlytag.addPath('catalog/product', '/themes/product');
            await curlytag.fetch('catalog/product');
            expect(fetchSpy).toHaveBeenCalledWith('/themes/product.html');
        });

        test('most specific namespace wins when multiple namespaces match', async () => {
            curlytag.addPath('catalog', '/themes/catalog');
            curlytag.addPath('catalog/product', '/themes/product');
            await curlytag.fetch('catalog/product/view');
            expect(fetchSpy).toHaveBeenCalledWith('/themes/product/view.html');
        });

        test('shallower namespace wins when deep namespace does not match', async () => {
            curlytag.addPath('catalog', '/themes/catalog');
            curlytag.addPath('catalog/product', '/themes/product');
            await curlytag.fetch('catalog/category/list');
            expect(fetchSpy).toHaveBeenCalledWith('/themes/catalog/category/list.html');
        });

        test('falls back to directory when no namespace matches', async () => {
            curlytag.directory = '/templates/';
            curlytag.addPath('catalog', '/themes/catalog');
            await curlytag.fetch('admin/dashboard');
            expect(fetchSpy).toHaveBeenCalledWith('/templates/admin/dashboard.html');
        });

        test('namespace does not affect path with different prefix', async () => {
            curlytag.addPath('shop', '/shop-templates');
            await curlytag.fetch('blog/post');
            expect(fetchSpy).toHaveBeenCalledWith('blog/post.html');
        });

        test('three namespaces registered correct one is selected', async () => {
            curlytag.addPath('a', '/dir-a');
            curlytag.addPath('b', '/dir-b');
            curlytag.addPath('c', '/dir-c');
            await curlytag.fetch('b/index');
            expect(fetchSpy).toHaveBeenCalledWith('/dir-b/index.html');
        });

        test('directory is ignored when namespace matches', async () => {
            curlytag.directory = '/fallback/';
            curlytag.addPath('catalog', '/themes/catalog');
            await curlytag.fetch('catalog/view');
            expect(fetchSpy).toHaveBeenCalledWith('/themes/catalog/view.html');
        });
    });

    describe('addPath()', () => {
        test('two args register namespace in this.path map', () => {
            curlytag.addPath('ns', '/path/to/ns');
            expect(curlytag.path.has('ns')).toBe(true);
            expect(curlytag.path.get('ns')).toBe('/path/to/ns');
        });

        test('one arg sets this.directory', () => {
            curlytag.addPath('/base/');
            expect(curlytag.directory).toBe('/base/');
        });

        test('multiple addPath calls with namespaces accumulate independently', async () => {
            curlytag.addPath('a', '/dir-a');
            curlytag.addPath('b', '/dir-b');

            await curlytag.fetch('a/file');
            expect(fetchSpy).toHaveBeenCalledWith('/dir-a/file.html');

            fetchSpy.mockClear();

            await curlytag.fetch('b/file');
            expect(fetchSpy).toHaveBeenCalledWith('/dir-b/file.html');
        });

        test('overwriting namespace replaces previous path', async () => {
            curlytag.addPath('ns', '/old-path');
            curlytag.addPath('ns', '/new-path');
            await curlytag.fetch('ns/file');
            expect(fetchSpy).toHaveBeenCalledWith('/new-path/file.html');
        });
    });

    describe('response handling', () => {
        test('returns content when fetch responds successfully', async () => {
            fetchSpy.mockResolvedValueOnce(new Response('Hello {{ name }}!', { status: 200 }));

            const result = await curlytag.fetch('greeting');

            expect(result).toBe('Hello {{ name }}!');
            expect(fetchSpy).toHaveBeenCalledWith('greeting.html');
        });

        test('returns empty string when fetch responds with 404 content', async () => {
            fetchSpy.mockResolvedValueOnce(new Response('Not Found', { status: 404 }));

            const result = await curlytag.fetch('missing/file');

            expect(result).toBe('');
            expect(fetchSpy).toHaveBeenCalledWith('missing/file.html');
        });

        test('returns empty string when fetch responds with error content', async () => {
            fetchSpy.mockResolvedValueOnce(new Response('Internal Server Error', { status: 500 }));

            const result = await curlytag.fetch('protected/file');

            expect(result).toBe('');
            expect(fetchSpy).toHaveBeenCalledWith('protected/file.html');
        });
    });

    describe('edge cases', () => {
        test('empty path with no directory produces just .html', async () => {
            await curlytag.fetch('');
            expect(fetchSpy).toHaveBeenCalledWith('.html');
        });

        test('empty path with directory produces directory + .html', async () => {
            curlytag.directory = '/templates/';
            await curlytag.fetch('');
            expect(fetchSpy).toHaveBeenCalledWith('/templates/.html');
        });

        test('leading slash is preserved as part of path', async () => {
            curlytag.directory = '/templates';
            await curlytag.fetch('/page');
            expect(fetchSpy).toHaveBeenCalledWith('/templates/page.html');
        });

        test('trailing slash is preserved as part of path', async () => {
            curlytag.directory = '/templates/';
            await curlytag.fetch('page/');
            expect(fetchSpy).toHaveBeenCalledWith('/templates/page/.html');
        });

        test('double slashes in path are preserved literally', async () => {
            curlytag.directory = '/templates/';
            await curlytag.fetch('sub//page');
            expect(fetchSpy).toHaveBeenCalledWith('/templates/sub//page.html');
        });

        test('path with dot segments is preserved literally', async () => {
            curlytag.directory = '/templates/';
            await curlytag.fetch('sub/./page');
            expect(fetchSpy).toHaveBeenCalledWith('/templates/sub/./page.html');
        });

        test('namespace matches full segment only not prefix', async () => {
            curlytag.addPath('cat', '/short');
            await curlytag.fetch('catalog/page');
            expect(fetchSpy).toHaveBeenCalledWith('catalog/page.html');
        });

        test('namespace with trailing slash in stored path is preserved', async () => {
            curlytag.addPath('ns', '/dir/');
            await curlytag.fetch('ns/page');
            expect(fetchSpy).toHaveBeenCalledWith('/dir//page.html');
        });

        test('path containing dots in filename is preserved', async () => {
            curlytag.directory = '/templates/';
            await curlytag.fetch('page.v2');
            expect(fetchSpy).toHaveBeenCalledWith('/templates/page.v2.html');
        });

        test('path with spaces is preserved literally', async () => {
            curlytag.directory = '/templates/';
            await curlytag.fetch('my page');
            expect(fetchSpy).toHaveBeenCalledWith('/templates/my page.html');
        });

        test('path with unicode characters is preserved', async () => {
            curlytag.directory = '/templates/';
            await curlytag.fetch('страница');
            expect(fetchSpy).toHaveBeenCalledWith('/templates/страница.html');
        });
    });
});
