import { afterEach, beforeEach, describe, expect, test, vi } from 'vite-plus/test';
import { curlytag } from '#curlytag';
import loopTemplate from '../playground/examples/loop/template.html?raw';
import conditionsTemplate from '../playground/examples/conditions/template.html?raw';
import filtersTemplate from '../playground/examples/filters/template.html?raw';
import nestedTemplate from '../playground/examples/nested/template.html?raw';

const templates = new Map([
    [ '/templates/examples/loop/template.html', loopTemplate ],
    [ '/templates/examples/conditions/template.html', conditionsTemplate ],
    [ '/templates/examples/filters/template.html', filtersTemplate ],
    [ '/templates/examples/nested/template.html', nestedTemplate ]
]);

const resetCurlytag = () => {
    curlytag.directory = '';
    curlytag.path.clear();
    curlytag.cache.clear();
};

describe('render (browser)', () => {
    beforeEach(() => {
        resetCurlytag();
        curlytag.directory = '/templates/';
        vi.spyOn(globalThis, 'fetch').mockImplementation(async (url) => {
            const template = templates.get(String(url));

            return new Response(template ?? '', { status: template === undefined ? 404 : 200 });
        });
    });

    afterEach(() => {
        resetCurlytag();
        vi.restoreAllMocks();
    });

    test('render() loads a loop template and renders items', async () => {
        const result = await curlytag.render('examples/loop/template', {
            team: [ 'Alice', 'Bob', 'Carol' ]
        });
        expect(result).toContain('Alice');
        expect(result).toContain('Bob');
        expect(result).toContain('Carol');
    });

    test('render() loop template renders loop.index correctly', async () => {
        const result = await curlytag.render('examples/loop/template', { team: [ 'Alice' ] });
        expect(result).toContain('1. Alice');
    });

    test('render() loads a conditions template — admin branch', async () => {
        const result = await curlytag.render('examples/conditions/template', { role: 'admin' });
        expect(result).toContain('Admin access');
        expect(result).not.toContain('Editor access');
        expect(result).not.toContain('Viewer access');
    });

    test('render() loads a conditions template — elseif branch', async () => {
        const result = await curlytag.render('examples/conditions/template', {
            role: 'editor'
        });
        expect(result).toContain('Editor access');
        expect(result).not.toContain('Admin access');
    });

    test('render() loads a conditions template — else branch', async () => {
        const result = await curlytag.render('examples/conditions/template', { role: 'guest' });
        expect(result).toContain('Viewer access');
    });

    test('render() loads a filters template', async () => {
        const result = await curlytag.render('examples/filters/template', {
            title: 'hello',
            greeting: 'Hello world',
            price: 9.999,
            tags: [ 'js', 'html' ]
        });
        expect(result).toContain('HELLO');
        expect(result).toContain('Hey world');
        expect(result).toContain('10.00');
    });

    test('render() loads a nested template', async () => {
        const result = await curlytag.render('examples/nested/template', {
            title: 'Team',
            users: [
                { name: 'Alice', active: true, roles: [ 'admin' ] },
                { name: 'Bob', active: false, roles: [ 'editor' ] }
            ]
        });
        expect(result).toContain('Alice');
        expect(result).toContain('ADMIN');
        expect(result).toContain('Bob');
        expect(result).toContain('EDITOR');
    });

    test('render() caches the template on second call', async () => {
        await curlytag.render('examples/loop/template', { team: [ 'Alice' ] });
        expect(curlytag.cache.has('examples/loop/template')).toBe(true);
        const result = await curlytag.render('examples/loop/template', { team: [ 'Bob' ] });
        expect(result).toContain('Bob');
        expect(globalThis.fetch).toHaveBeenCalledTimes(1);
    });

    test('render() returns empty string for non-existent file', async () => {
        const result = await curlytag.render('examples/does-not-exist/template', {});
        expect(result).toBe('');
    });

    test('render() with empty data object renders static template', async () => {
        const result = await curlytag.render('examples/loop/template', {});
        expect(typeof result).toBe('string');
    });
});
