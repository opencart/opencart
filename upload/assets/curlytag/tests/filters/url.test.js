import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('url', () => {
    test('urlencode', () => {
        expect(curlytag.parse('{{ q | urlencode }}', { q: 'hello world' })).toBe('hello%20world');
    });
});
