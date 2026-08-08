import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('capitalize', () => {
    test('capitalizes first letter, lowercases rest', () => {
        expect(template.parse('{{ name | capitalize }}', { name: 'alice' })).toBe('Alice');
    });

    test('handles already capitalized string', () => {
        expect(template.parse('{{ name | capitalize }}', { name: 'Alice' })).toBe('Alice');
    });

    test('lowercases subsequent letters', () => {
        expect(template.parse('{{ name | capitalize }}', { name: 'ALICE BOB' })).toBe('Alice bob');
    });

    test('single character', () => {
        expect(template.parse('{{ name | capitalize }}', { name: 'a' })).toBe('A');
    });

    test('single uppercase character', () => {
        expect(template.parse('{{ name | capitalize }}', { name: 'A' })).toBe('A');
    });

    test('works in chain with other filters', () => {
        expect(template.parse('{{ name | trim | capitalize }}', { name: '  hello world  ' })).toBe(
            'Hello world',
        );
    });

    test('works as part of output with other text', () => {
        expect(template.parse('Hello, {{ name | capitalize }}!', { name: 'world' })).toBe(
            'Hello, World!',
        );
    });

    test('string starting with number', () => {
        expect(template.parse('{{ name | capitalize }}', { name: '1st place' })).toBe('1st place');
    });

    test('multiword string lowercases non-first words', () => {
        expect(template.parse('{{ title | capitalize }}', { title: 'the QUICK brown FOX' })).toBe(
            'The quick brown fox',
        );
    });

    test('empty string returns empty string', () => {
        expect(template.parse('{{ name | capitalize }}', { name: '' })).toBe('');
    });

    test('null value returns empty string', () => {
        expect(template.parse('{{ name | capitalize }}', { name: null })).toBe('');
    });

    test('undefined variable returns empty string', () => {
        expect(template.parse('{{ name | capitalize }}', {})).toBe('');
    });

    test('numeric value is stringified and capitalized', () => {
        expect(template.parse('{{ val | capitalize }}', { val: 42 })).toBe('42');
    });

    test('whitespace-only string', () => {
        expect(template.parse('{{ name | capitalize }}', { name: '   ' })).toBe('   ');
    });

    test('string starting with emoji is returned unchanged', () => {
        expect(template.parse('{{ title | capitalize }}', { title: '🔥 hot post' })).toBe('🔥 hot post');
    });

    test('string with emoji in middle preserves emoji', () => {
        expect(template.parse('{{ title | capitalize }}', { title: 'hello 🌍 WORLD' })).toBe('Hello 🌍 world');
    });
});
