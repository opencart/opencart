import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('capture', () => {
    test('stores block content in a variable', () => {
        expect(template.parse('{% capture msg %}Hello!{% endcapture %}{{ msg }}')).toBe('Hello!');
    });

    test('captured variable is available after the block', () => {
        const tpl =
            '{% capture greeting %}Hi {% capture name %}World{% endcapture %}{% endcapture %}{{ greeting }}{{ name }}';
        expect(template.parse(tpl)).toBe('Hi World');
    });
});
