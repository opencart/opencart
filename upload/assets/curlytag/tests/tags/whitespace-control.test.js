import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('whitespace control', () => {
    test('leading dash {{- trims leading whitespace from value', () => {
        expect(template.parse('{{- name }}', { name: '  hello' })).toBe('hello');
    });

    test('trailing dash -}} trims trailing whitespace from value', () => {
        expect(template.parse('{{ name -}}', { name: 'hello  ' })).toBe('hello');
    });

    test('both dashes trim both sides', () => {
        expect(template.parse('{{- name -}}', { name: '  hello  ' })).toBe('hello');
    });

    test('leading dash with no leading whitespace leaves value unchanged', () => {
        expect(template.parse('{{- name }}', { name: 'hello' })).toBe('hello');
    });

    test('trailing dash with no trailing whitespace leaves value unchanged', () => {
        expect(template.parse('{{ name -}}', { name: 'hello' })).toBe('hello');
    });

    test('no dash leaves whitespace intact', () => {
        expect(template.parse('{{ name }}', { name: '  hello  ' })).toBe('  hello  ');
    });

    test('leading dash trims only leading whitespace, not trailing', () => {
        expect(template.parse('{{- name }}', { name: '  hello  ' })).toBe('hello  ');
    });

    test('trailing dash trims only trailing whitespace, not leading', () => {
        expect(template.parse('{{ name -}}', { name: '  hello  ' })).toBe('  hello');
    });
});
