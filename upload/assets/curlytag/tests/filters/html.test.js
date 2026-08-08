import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('html', () => {
    test('escape encodes all HTML entities', () => {
        expect(template.parse('{{ v | escape }}', { v: '&<>"\'' })).toBe(
            '&amp;amp;&amp;lt;&amp;gt;&amp;quot;&amp;#39;',
        );
    });

    test('safe passes value through', () => {
        expect(template.parse('{{ html | safe }}', { html: '<b>bold</b>' })).toBe(
            '&lt;b&gt;bold&lt;/b&gt;',
        );
    });

    test('nl2br', () => {
        expect(template.parse('{{ text | nl2br }}', { text: 'a\nb' })).toBe('a&lt;br/&gt;b');
    });
});
