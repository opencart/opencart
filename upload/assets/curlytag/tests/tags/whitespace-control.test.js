import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('whitespace control', () => {
    test('leading dash {{- trims leading whitespace from value', () => {
        expect(curlytag.parse('{{- name }}', { name: '  hello' })).toBe('hello');
    });

    test('trailing dash -}} trims trailing whitespace from value', () => {
        expect(curlytag.parse('{{ name -}}', { name: 'hello  ' })).toBe('hello');
    });

    test('both dashes trim both sides', () => {
        expect(curlytag.parse('{{- name -}}', { name: '  hello  ' })).toBe('hello');
    });

    test('leading dash with no leading whitespace leaves value unchanged', () => {
        expect(curlytag.parse('{{- name }}', { name: 'hello' })).toBe('hello');
    });

    test('trailing dash with no trailing whitespace leaves value unchanged', () => {
        expect(curlytag.parse('{{ name -}}', { name: 'hello' })).toBe('hello');
    });

    test('no dash leaves whitespace intact', () => {
        expect(curlytag.parse('{{ name }}', { name: '  hello  ' })).toBe('  hello  ');
    });

    test('leading dash trims only leading whitespace, not trailing', () => {
        expect(curlytag.parse('{{- name }}', { name: '  hello  ' })).toBe('hello  ');
    });

    test('trailing dash trims only trailing whitespace, not leading', () => {
        expect(curlytag.parse('{{ name -}}', { name: '  hello  ' })).toBe('  hello');
    });
});
