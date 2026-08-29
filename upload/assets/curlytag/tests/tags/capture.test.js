import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('capture', () => {
    test('stores block content in a variable', () => {
        expect(curlytag.parse('{% capture msg %}Hello!{% endcapture %}{{ msg }}')).toBe('Hello!');
    });

    test('captured variable is available after the block', () => {
        const tpl
            = '{% capture greeting %}Hi {% capture name %}World{% endcapture %}{% endcapture %}{{ greeting }}{{ name }}';
        expect(curlytag.parse(tpl)).toBe('Hi World');
    });
});
