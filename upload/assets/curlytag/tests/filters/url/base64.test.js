import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('base64_encode', () => {
    test('encodes basic string', () => {
        expect(
            template.parse('{{ value | base64_encode }}', { value: 'hello' }),
        ).toBe('aGVsbG8=');
    });

    test('encodes string with spaces', () => {
        expect(
            template.parse('{{ value | base64_encode }}', { value: 'hello world' }),
        ).toBe('aGVsbG8gd29ybGQ=');
    });

    test('encodes unicode string', () => {
        expect(
            template.parse('{{ value | base64_encode }}', { value: 'привет' }),
        ).toBe('0L/RgNC40LLQtdGC');
    });

    test('encodes emoji', () => {
        expect(
            template.parse('{{ value | base64_encode }}', { value: '🎉' }),
        ).toBe('8J+OiQ==');
    });

    test('encodes chinese characters', () => {
        expect(
            template.parse('{{ value | base64_encode }}', { value: '你好' }),
        ).toBe('5L2g5aW9');
    });

    test('encodes null as empty string', () => {
        expect(
            template.parse('{{ value | base64_encode }}', { value: null }),
        ).toBe('');
    });

    test('encodes undefined as empty string', () => {
        expect(
            template.parse('{{ value | base64_encode }}', {}),
        ).toBe('');
    });
});

describe('base64_decode', () => {
    test('decodes basic string', () => {
        expect(
            template.parse('{{ value | base64_decode }}', { value: 'aGVsbG8=' }),
        ).toBe('hello');
    });

    test('decodes string with spaces', () => {
        expect(
            template.parse('{{ value | base64_decode }}', { value: 'aGVsbG8gd29ybGQ=' }),
        ).toBe('hello world');
    });

    test('decodes unicode string', () => {
        expect(
            template.parse('{{ value | base64_decode }}', { value: '0L/RgNC40LLQtdGC' }),
        ).toBe('привет');
    });

    test('decodes empty string', () => {
        expect(
            template.parse('{{ value | base64_decode }}', { value: '' }),
        ).toBe('');
    });

    test('decodes emoji', () => {
        expect(
            template.parse('{{ value | base64_decode }}', { value: '8J+OiQ==' }),
        ).toBe('🎉');
    });

    test('returns empty string for invalid base64', () => {
        expect(
            template.parse('{{ value | base64_decode }}', { value: 'not valid base64!!!' }),
        ).toBe('');
    });

    test('decodes chinese characters', () => {
        expect(
            template.parse('{{ value | base64_decode }}', { value: '5L2g5aW9' }),
        ).toBe('你好');
    });

    test('returns empty string for null', () => {
        expect(
            template.parse('{{ value | base64_decode }}', { value: null }),
        ).toBe('');
    });

    test('returns empty string for undefined', () => {
        expect(
            template.parse('{{ value | base64_decode }}', {}),
        ).toBe('');
    });

    test('encode then decode roundtrip', () => {
        expect(
            template.parse('{{ value | base64_encode | base64_decode }}', { value: 'roundtrip test' }),
        ).toBe('roundtrip test');
    });
});
