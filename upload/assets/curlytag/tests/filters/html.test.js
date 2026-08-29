import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('html', () => {
    test('escape encodes all HTML entities', () => {
        expect(curlytag.parse('{{ v | escape }}', { v: '&<>"\'' })).toBe(
            '&amp;amp;&amp;lt;&amp;gt;&amp;quot;&amp;#39;'
        );
    });

    test('safe passes value through', () => {
        expect(curlytag.parse('{{ html | safe }}', { html: '<b>bold</b>' })).toBe(
            '&lt;b&gt;bold&lt;/b&gt;'
        );
    });

    test('nl2br', () => {
        expect(curlytag.parse('{{ text | nl2br }}', { text: 'a\nb' })).toBe('a&lt;br/&gt;b');
    });
});
