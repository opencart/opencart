import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('unless', () => {
    test('renders when condition is falsy', () => {
        expect(template.parse('{% unless hidden %}visible{% endunless %}', { hidden: false })).toBe(
            'visible',
        );
    });

    test('skips when condition is truthy', () => {
        expect(template.parse('{% unless hidden %}visible{% endunless %}', { hidden: true })).toBe(
            '',
        );
    });

    test('else renders when condition is truthy', () => {
        const tpl = '{% unless hidden %}visible{% else %}hidden{% endunless %}';
        expect(template.parse(tpl, { hidden: true })).toBe('hidden');
    });

    test('else does not render when condition is falsy', () => {
        const tpl = '{% unless hidden %}visible{% else %}hidden{% endunless %}';
        expect(template.parse(tpl, { hidden: false })).toBe('visible');
    });

    test('only one branch renders', () => {
        const tpl = '{% unless hidden %}A{% else %}B{% endunless %}';
        expect(template.parse(tpl, { hidden: true })).toBe('B');
        expect(template.parse(tpl, { hidden: false })).toBe('A');
    });

    test('content after endunless still renders', () => {
        const tpl = '{% unless hidden %}A{% else %}B{% endunless %}C';
        expect(template.parse(tpl, { hidden: true })).toBe('BC');
        expect(template.parse(tpl, { hidden: false })).toBe('AC');
    });
});
