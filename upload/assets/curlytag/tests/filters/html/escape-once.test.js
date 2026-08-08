import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('escape_once', () => {
    test('escapes unescaped HTML', () => {
        expect(
            template.parse('{{ value | escape_once }}', { value: '<b>hello</b>' }),
        ).toBe('&amp;lt;b&amp;gt;hello&amp;lt;/b&amp;gt;');
    });

    test('does not double escape already escaped string', () => {
        expect(
            template.parse('{{ value | escape_once }}', { value: '&lt;b&gt;hello&lt;/b&gt;' }),
        ).toBe('&amp;lt;b&amp;gt;hello&amp;lt;/b&amp;gt;');
    });

    test('escapes mixed partially escaped string', () => {
        expect(
            template.parse('{{ value | escape_once }}', { value: '&lt;b&gt;<i>hello</i>' }),
        ).toBe('&amp;lt;b&amp;gt;&amp;lt;i&amp;gt;hello&amp;lt;/i&amp;gt;');
    });

    test('does not double escape ampersand', () => {
        expect(
            template.parse('{{ value | escape_once }}', { value: '&amp;' }),
        ).toBe('&amp;amp;');
    });

    test('escapes raw ampersand', () => {
        expect(
            template.parse('{{ value | escape_once }}', { value: 'a & b' }),
        ).toBe('a &amp;amp; b');
    });

    test('raw string and escaped string produce same result', () => {
        const fromRaw = template.parse('{{ value | escape_once }}', { value: '"hello" & \'world\'' });
        const fromEscaped = template.parse('{{ value | escape_once }}', { value: '&quot;hello&quot; &amp; &#39;world&#39;' });
        expect(fromRaw).toBe(fromEscaped);
    });

    test('handles empty string', () => {
        expect(
            template.parse('{{ value | escape_once }}', { value: '' }),
        ).toBe('');
    });

    test('handles null', () => {
        expect(
            template.parse('{{ value | escape_once }}', { value: null }),
        ).toBe('');
    });

    test('handles plain text without special chars', () => {
        expect(
            template.parse('{{ value | escape_once }}', { value: 'hello world' }),
        ).toBe('hello world');
    });
});
