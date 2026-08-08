import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('strip_newlines', () => {
    test('removes unix newlines', () => {
        expect(template.parse('{{ text | strip_newlines }}', { text: 'hello\nworld' })).toBe(
            'helloworld',
        );
    });

    test('removes windows newlines', () => {
        expect(template.parse('{{ text | strip_newlines }}', { text: 'hello\r\nworld' })).toBe(
            'helloworld',
        );
    });

    test('removes old mac newlines', () => {
        expect(template.parse('{{ text | strip_newlines }}', { text: 'hello\rworld' })).toBe(
            'helloworld',
        );
    });

    test('removes multiple newlines', () => {
        expect(
            template.parse('{{ text | strip_newlines }}', { text: 'a\nb\r\nc\rd' }),
        ).toBe('abcd');
    });

    test('removes leading and trailing newlines', () => {
        expect(template.parse('{{ text | strip_newlines }}', { text: '\nhello\n' })).toBe('hello');
    });

    test('string without newlines is unchanged', () => {
        expect(template.parse('{{ text | strip_newlines }}', { text: 'hello world' })).toBe(
            'hello world',
        );
    });

    test('empty string returns empty string', () => {
        expect(template.parse('{{ text | strip_newlines }}', { text: '' })).toBe('');
    });

    test('null returns empty string', () => {
        expect(template.parse('{{ text | strip_newlines }}', { text: null })).toBe('');
    });

    test('undefined variable returns empty string', () => {
        expect(template.parse('{{ text | strip_newlines }}', {})).toBe('');
    });

    test('only newlines returns empty string', () => {
        expect(template.parse('{{ text | strip_newlines }}', { text: '\n\r\n\r' })).toBe('');
    });

    test('chains with other filters', () => {
        expect(
            template.parse('{{ text | strip_newlines | upper }}', { text: 'hello\nworld' }),
        ).toBe('HELLOWORLD');
    });
});
