import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('raw', () => {
    test('preserves template syntax literally', () => {
        const result = curlytag.parse('{% raw %}{{ not_a_var }}{% endraw %}');
        expect(result).toBe('{{ not_a_var }}');
    });
});
