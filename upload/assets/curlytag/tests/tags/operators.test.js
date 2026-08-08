import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('operators', () => {
    describe('comparison', () => {
        test('== true when equal', () => {
            expect(template.parse('{% if x == 1 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('== false when unequal', () => {
            expect(template.parse('{% if x == 2 %}yes{% endif %}', { x: 1 })).toBe('');
        });
        test('!= true when unequal', () => {
            expect(template.parse('{% if x != 2 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('!= false when equal', () => {
            expect(template.parse('{% if x != 1 %}yes{% endif %}', { x: 1 })).toBe('');
        });
        test('> true when greater', () => {
            expect(template.parse('{% if x > 0 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('> false when not greater', () => {
            expect(template.parse('{% if x > 2 %}yes{% endif %}', { x: 1 })).toBe('');
        });
        test('< true when less', () => {
            expect(template.parse('{% if x < 5 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('< false when not less', () => {
            expect(template.parse('{% if x < 0 %}yes{% endif %}', { x: 1 })).toBe('');
        });
        test('>= true when equal', () => {
            expect(template.parse('{% if x >= 1 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('>= true when greater', () => {
            expect(template.parse('{% if x >= 0 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('<= true when equal', () => {
            expect(template.parse('{% if x <= 1 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
        test('<= true when less', () => {
            expect(template.parse('{% if x <= 5 %}yes{% endif %}', { x: 1 })).toBe('yes');
        });
    });

    describe('and', () => {
        test('true and true renders', () => {
            expect(template.parse('{% if a and b %}yes{% endif %}', { a: true, b: true })).toBe('yes');
        });
        test('true and false does not render', () => {
            expect(template.parse('{% if a and b %}yes{% endif %}', { a: true, b: false })).toBe('');
        });
        test('false and true does not render', () => {
            expect(template.parse('{% if a and b %}yes{% endif %}', { a: false, b: true })).toBe('');
        });
    });

    describe('or', () => {
        test('false or true renders', () => {
            expect(template.parse('{% if a or b %}yes{% endif %}', { a: false, b: true })).toBe('yes');
        });
        test('true or false renders', () => {
            expect(template.parse('{% if a or b %}yes{% endif %}', { a: true, b: false })).toBe('yes');
        });
        test('false or false does not render', () => {
            expect(template.parse('{% if a or b %}yes{% endif %}', { a: false, b: false })).toBe('');
        });
    });

    describe('not', () => {
        test('not false renders', () => {
            expect(template.parse('{% if not a %}yes{% endif %}', { a: false })).toBe('yes');
        });
        test('not true does not render', () => {
            expect(template.parse('{% if not a %}yes{% endif %}', { a: true })).toBe('');
        });
    });

    describe('combined', () => {
        test('comparison combined with and', () => {
            expect(template.parse('{% if x > 0 and x < 10 %}yes{% endif %}', { x: 5 })).toBe('yes');
        });
        test('comparison combined with or', () => {
            expect(template.parse('{% if x == 1 or x == 2 %}yes{% endif %}', { x: 2 })).toBe('yes');
        });
        test('not combined with and', () => {
            expect(template.parse('{% if not a and b %}yes{% endif %}', { a: false, b: true })).toBe('yes');
        });
    });
});
