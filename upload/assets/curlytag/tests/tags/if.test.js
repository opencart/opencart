import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('if / elseif / else', () => {
    test('renders truthy branch', () => {
        expect(template.parse('{% if show %}yes{% endif %}', { show: true })).toBe('yes');
    });

    test('skips falsy branch', () => {
        expect(template.parse('{% if show %}yes{% endif %}', { show: false })).toBe('');
    });

    test('else branch', () => {
        expect(template.parse('{% if show %}yes{% else %}no{% endif %}', { show: false })).toBe(
            'no',
        );
    });

    test('elseif branch', () => {
        const tpl = '{% if a %}A{% elseif b %}B{% else %}C{% endif %}';
        expect(template.parse(tpl, { a: false, b: true })).toBe('B');
    });

    test('invalid syntax is silently ignored', () => {
        expect(template.parse('{% if %}yes{% endif %}')).toBe('');
    });

    test('invalid syntax with else produces empty string', () => {
        expect(template.parse('{% if %}yes{% else %}no{% endif %}')).toBe('');
    });

    test('invalid syntax with elseif produces empty string', () => {
        expect(template.parse('{% if %}yes{% elseif b %}B{% endif %}', { b: true })).toBe('');
    });

    test('invalid syntax with content before and after', () => {
        expect(template.parse('before{% if %}yes{% endif %}after')).toBe('beforeafter');
    });

    test('valid if after invalid if works correctly', () => {
        expect(
            template.parse('{% if %}bad{% endif %}{% if show %}good{% endif %}', {
                show: true,
            }),
        ).toBe('good');
    });

    test('false elseif falls through to else', () => {
        const tpl = '{% if a %}A{% elseif b %}B{% else %}C{% endif %}';
        expect(template.parse(tpl, { a: false, b: false })).toBe('C');
    });

    test('false elseif does not render its body', () => {
        const tpl = '{% if a %}A{% elseif b %}B{% endif %}';
        expect(template.parse(tpl, { a: false, b: false })).toBe('');
    });

    test('nested if inside elseif does not leak', () => {
        const tpl = '{% if a %}A{% elseif b %}{% if c %}C{% endif %}B{% else %}D{% endif %}';
        expect(template.parse(tpl, { a: false, b: false, c: true })).toBe('D');
    });

    test('already active if skips all elseif and else', () => {
        const tpl = '{% if a %}A{% elseif b %}B{% else %}C{% endif %}';
        expect(template.parse(tpl, { a: true, b: true })).toBe('A');
    });

    describe('multiple elseif branches', () => {
        const tpl = '{% if a %}A{% elseif b %}B{% elseif c %}C{% else %}D{% endif %}';

        test('picks the if branch when first condition is true', () => {
            expect(template.parse(tpl, { a: true, b: true, c: true })).toBe('A');
        });

        test('picks the first elseif branch', () => {
            expect(template.parse(tpl, { a: false, b: true, c: true })).toBe('B');
        });

        test('picks the second elseif branch', () => {
            expect(template.parse(tpl, { a: false, b: false, c: true })).toBe('C');
        });

        test('falls through to else when all conditions are false', () => {
            expect(template.parse(tpl, { a: false, b: false, c: false })).toBe('D');
        });
    });
});
