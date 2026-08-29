import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('html', () => {
    describe('unescape', () => {
        test.each([
            [ '&amp;', '&' ],
            [ '&#38;', '&' ],
            [ '&lt;', '<' ],
            [ '&#60;', '<' ],
            [ '&gt;', '>' ],
            [ '&#62;', '>' ],
            [ '&apos;', "'" ],
            [ '&#39;', "'" ],
            [ '&quot;', '"' ],
            [ '&#34;', '"' ]
        ])('decodes %s', (value, expected) => {
            expect(curlytag.filter.unescape(value)).toBe(expected);
        });

        test('decodes mixed and repeated entities', () => {
            expect(curlytag.filter.unescape('&lt;b&gt;Tom &amp; Jerry&lt;/b&gt;')).toBe(
                '<b>Tom & Jerry</b>'
            );
        });

        test('decodes one level at a time', () => {
            expect(curlytag.filter.unescape('&amp;lt;')).toBe('&lt;');
        });

        test('leaves unsupported entities unchanged', () => {
            expect(curlytag.filter.unescape('&copy; &#169; &unknown;')).toBe(
                '&copy; &#169; &unknown;'
            );
        });

        test('leaves plain text unchanged', () => {
            expect(curlytag.filter.unescape('plain text')).toBe('plain text');
        });

        test('handles null and undefined values', () => {
            expect(curlytag.filter.unescape(null)).toBe('');
            expect(curlytag.filter.unescape(undefined)).toBe('');
        });

        test('decoded values are escaped during rendering', () => {
            expect(curlytag.parse('{{ html | unescape }}', { html: '&lt;b&gt;' })).toBe(
                '&lt;b&gt;'
            );
        });
    });
});
