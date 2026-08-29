import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('echo', () => {
    test('outputs a variable', () => {
        expect(curlytag.parse('{% echo greeting %}!', { greeting: 'hello' })).toBe('hello!');
    });

    test('outputs a literal string', () => {
        expect(curlytag.parse('say: {% echo "world" %}!', {})).toBe('say: world!');
    });

    test('outputs with filter', () => {
        expect(curlytag.parse('{% echo name | upper %}!', { name: 'alice' })).toBe('ALICE!');
    });

    test('outputs when echo is the last token in the template', () => {
        expect(curlytag.parse('{% echo greeting %}', { greeting: 'hello' })).toBe('hello');
    });

    test('outputs a number when echo is last', () => {
        expect(curlytag.parse('{% echo count %}', { count: 42 })).toBe('42');
    });

    test('outputs with filter when echo is last', () => {
        expect(curlytag.parse('{% echo name | upper %}', { name: 'alice' })).toBe('ALICE');
    });

    test('outputs empty string for undefined variable when echo is last', () => {
        expect(curlytag.parse('{% echo missing %}')).toBe('');
    });

    test('outputs zero when echo is last', () => {
        expect(curlytag.parse('{% echo n %}', { n: 0 })).toBe('0');
    });

    test('outputs false when echo is last', () => {
        expect(curlytag.parse('{% echo b %}', { b: false })).toBe('false');
    });

    test('outputs zero when echo is followed by a tag', () => {
        expect(curlytag.parse('{% echo n %}{% if true %}yes{% endif %}', { n: 0 })).toBe('0yes');
    });

    test('outputs false when echo is followed by a tag', () => {
        expect(curlytag.parse('{% echo b %}{% if true %}yes{% endif %}', { b: false })).toBe(
            'falseyes'
        );
    });

    test('outputs prefix + echo when echo is not last', () => {
        expect(curlytag.parse('prefix: {% echo greeting %}!', { greeting: 'hello' })).toBe(
            'prefix: hello!'
        );
    });
});
