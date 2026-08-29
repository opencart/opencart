import { afterEach, beforeEach, describe, expect, test, vi } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('CurlyTag - browser fetch()', () => {
    beforeEach(() => {
        curlytag.directory = '/templates/';
        curlytag.cache.clear();
    });

    afterEach(() => {
        curlytag.directory = '';
        vi.restoreAllMocks();
    });

    test('fetches template via window.fetch and renders it', async () => {
        vi.spyOn(globalThis, 'fetch').mockResolvedValueOnce(
            new Response('Hello {{ name }}!', { status: 200 })
        );

        const result = await curlytag.render('greeting', { name: 'World' });

        expect(result).toBe('Hello World!');
        expect(fetch).toHaveBeenCalledWith('/templates/greeting.html');
    });

    test('returns empty string when fetch responds with 404', async () => {
        vi.spyOn(globalThis, 'fetch').mockResolvedValueOnce(
            new Response('Not Found', { status: 404 })
        );

        const result = await curlytag.render('missing', {});

        expect(result).toBe('');
    });

    test('caches template after first fetch and does not re-fetch', async () => {
        vi.spyOn(globalThis, 'fetch').mockResolvedValue(
            new Response('Cached: {{ value }}', { status: 200 })
        );

        await curlytag.render('cached', { value: 'one' });
        await curlytag.render('cached', { value: 'two' });

        expect(fetch).toHaveBeenCalledTimes(1);
    });
});
