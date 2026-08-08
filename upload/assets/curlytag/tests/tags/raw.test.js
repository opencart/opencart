import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('raw', () => {
    test('preserves template syntax literally', () => {
        const result = template.parse('{% raw %}{{ not_a_var }}{% endraw %}');
        expect(result).toBe('{{ not_a_var }}');
    });
});
