import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('url', () => {
    test('urlencode', () => {
        expect(template.parse('{{ q | urlencode }}', { q: 'hello world' })).toBe('hello%20world');
    });
});
