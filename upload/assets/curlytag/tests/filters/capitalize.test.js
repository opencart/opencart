import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('capitalize', () => {
    test('capitalizes first letter, lowercases rest', () => {
        expect(curlytag.parse('{{ name | capitalize }}', { name: 'alice' })).toBe('Alice');
    });

    test('handles already capitalized string', () => {
        expect(curlytag.parse('{{ name | capitalize }}', { name: 'Alice' })).toBe('Alice');
    });

    test('lowercases subsequent letters', () => {
        expect(curlytag.parse('{{ name | capitalize }}', { name: 'ALICE BOB' })).toBe('Alice bob');
    });

    test('single character', () => {
        expect(curlytag.parse('{{ name | capitalize }}', { name: 'a' })).toBe('A');
    });

    test('single uppercase character', () => {
        expect(curlytag.parse('{{ name | capitalize }}', { name: 'A' })).toBe('A');
    });

    test('works in chain with other filters', () => {
        expect(curlytag.parse('{{ name | trim | capitalize }}', { name: '  hello world  ' })).toBe(
            'Hello world'
        );
    });

    test('works as part of output with other text', () => {
        expect(curlytag.parse('Hello, {{ name | capitalize }}!', { name: 'world' })).toBe(
            'Hello, World!'
        );
    });

    test('string starting with number', () => {
        expect(curlytag.parse('{{ name | capitalize }}', { name: '1st place' })).toBe('1st place');
    });

    test('multiword string lowercases non-first words', () => {
        expect(curlytag.parse('{{ title | capitalize }}', { title: 'the QUICK brown FOX' })).toBe(
            'The quick brown fox'
        );
    });

    test('empty string returns empty string', () => {
        expect(curlytag.parse('{{ name | capitalize }}', { name: '' })).toBe('');
    });

    test('null value returns empty string', () => {
        expect(curlytag.parse('{{ name | capitalize }}', { name: null })).toBe('');
    });

    test('undefined value returns empty string', () => {
        expect(curlytag.parse('{{ name | capitalize }}', { name: undefined })).toBe('');
    });

    test('numeric value is stringified and capitalized', () => {
        expect(curlytag.parse('{{ val | capitalize }}', { val: 42 })).toBe('42');
    });

    test('whitespace-only string', () => {
        expect(curlytag.parse('{{ name | capitalize }}', { name: '   ' })).toBe('   ');
    });

    test('string starting with emoji is returned unchanged', () => {
        expect(curlytag.parse('{{ title | capitalize }}', { title: '🔥 hot post' })).toBe('🔥 hot post');
    });

    test('string with emoji in middle preserves emoji', () => {
        expect(curlytag.parse('{{ title | capitalize }}', { title: 'hello 🌍 WORLD' })).toBe('Hello 🌍 world');
    });
});
