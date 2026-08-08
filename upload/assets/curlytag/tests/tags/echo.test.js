import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('echo', () => {
    test('outputs a variable', () => {
        expect(template.parse('{% echo greeting %}!', { greeting: 'hello' })).toBe('hello!');
    });

    test('outputs a literal string', () => {
        expect(template.parse('say: {% echo "world" %}!', {})).toBe('say: world!');
    });

    test('outputs with filter', () => {
        expect(template.parse('{% echo name | upper %}!', { name: 'alice' })).toBe('ALICE!');
    });

    test('outputs when echo is the last token in the template', () => {
        expect(template.parse('{% echo greeting %}', { greeting: 'hello' })).toBe('hello');
    });

    test('outputs a number when echo is last', () => {
        expect(template.parse('{% echo count %}', { count: 42 })).toBe('42');
    });

    test('outputs with filter when echo is last', () => {
        expect(template.parse('{% echo name | upper %}', { name: 'alice' })).toBe('ALICE');
    });

    test('outputs empty string for undefined variable when echo is last', () => {
        expect(template.parse('{% echo missing %}')).toBe('');
    });

    test('outputs zero when echo is last', () => {
        expect(template.parse('{% echo n %}', { n: 0 })).toBe('0');
    });

    test('outputs false when echo is last', () => {
        expect(template.parse('{% echo b %}', { b: false })).toBe('false');
    });

    test('outputs zero when echo is followed by a tag', () => {
        expect(template.parse('{% echo n %}{% if true %}yes{% endif %}', { n: 0 })).toBe('0yes');
    });

    test('outputs false when echo is followed by a tag', () => {
        expect(template.parse('{% echo b %}{% if true %}yes{% endif %}', { b: false })).toBe(
            'falseyes',
        );
    });

    test('outputs prefix + echo when echo is not last', () => {
        expect(template.parse('prefix: {% echo greeting %}!', { greeting: 'hello' })).toBe(
            'prefix: hello!',
        );
    });
});
