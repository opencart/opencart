import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('operators', () => {
    describe('comparison', () => {
        test('== true when equal', () => {
            expect(curlytag.parse('{% if x == 1 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('== false when unequal', () => {
            expect(curlytag.parse('{% if x == 2 %}yes{% endif %}', { x: 1 })).toBe('');
        });
        test('!= true when unequal', () => {
            expect(curlytag.parse('{% if x != 2 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('!= false when equal', () => {
            expect(curlytag.parse('{% if x != 1 %}yes{% endif %}', { x: 1 })).toBe('');
        });
        test('> true when greater', () => {
            expect(curlytag.parse('{% if x > 0 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('> false when not greater', () => {
            expect(curlytag.parse('{% if x > 2 %}yes{% endif %}', { x: 1 })).toBe('');
        });
        test('< true when less', () => {
            expect(curlytag.parse('{% if x < 5 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('< false when not less', () => {
            expect(curlytag.parse('{% if x < 0 %}yes{% endif %}', { x: 1 })).toBe('');
        });
        test('>= true when equal', () => {
            expect(curlytag.parse('{% if x >= 1 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('>= true when greater', () => {
            expect(curlytag.parse('{% if x >= 0 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('<= true when equal', () => {
            expect(curlytag.parse('{% if x <= 1 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('<= true when less', () => {
            expect(curlytag.parse('{% if x <= 5 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
    });

    describe('and', () => {
        test('true and true renders', () => {
            expect(curlytag.parse('{% if a and b %}yes{% endif %}', { a: true, b: true })).toBe('yes');
        });
        test('true and false does not render', () => {
            expect(curlytag.parse('{% if a and b %}yes{% endif %}', { a: true, b: false })).toBe('');
        });
        test('false and true does not render', () => {
            expect(curlytag.parse('{% if a and b %}yes{% endif %}', { a: false, b: true })).toBe('');
        });
    });

    describe('or', () => {
        test('false or true renders', () => {
            expect(curlytag.parse('{% if a or b %}yes{% endif %}', { a: false, b: true })).toBe('yes');
        });
        test('true or false renders', () => {
            expect(curlytag.parse('{% if a or b %}yes{% endif %}', { a: true, b: false })).toBe('yes');
        });
        test('false or false does not render', () => {
            expect(curlytag.parse('{% if a or b %}yes{% endif %}', { a: false, b: false })).toBe('');
        });
    });

    describe('not', () => {
        test('not false renders', () => {
            expect(curlytag.parse('{% if not a %}yes{% endif %}', { a: false })).toBe('yes');
        });
        test('not true does not render', () => {
            expect(curlytag.parse('{% if not a %}yes{% endif %}', { a: true })).toBe('');
        });
    });

    describe('string literals', () => {
        test('does not transform operators inside double-quoted strings', () => {
            expect(curlytag.parse('{% if label == "not and or" %}yes{% endif %}', { label: 'not and or' })).toBe('yes');
        });

        test('does not transform operators inside single-quoted strings', () => {
            expect(curlytag.parse("{% if label == 'not and or' %}yes{% endif %}", { label: 'not and or' })).toBe('yes');
        });
    });

    describe('combined', () => {
        test('comparison combined with and', () => {
            expect(curlytag.parse('{% if x > 0 and x < 10 %}yes{% endif %}', { x: 5 })).toBe('yes');
        });
        test('comparison combined with or', () => {
            expect(curlytag.parse('{% if x == 1 or x == 2 %}yes{% endif %}', { x: 2 })).toBe('yes');
        });
        test('not combined with and', () => {
            expect(curlytag.parse('{% if not a and b %}yes{% endif %}', { a: false, b: true })).toBe('yes');
        });
    });
});
