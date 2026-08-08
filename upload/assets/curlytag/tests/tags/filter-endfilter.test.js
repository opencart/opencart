import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('filter / endfilter', () => {
    test('applies a filter to a block of content', () => {
        expect(template.parse('{% filter upper %}hello{% endfilter %}text')).toBe('HELLOtext');
    });

    test('filter block followed by more content', () => {
        expect(template.parse('before {% filter upper %}hello{% endfilter %} after')).toBe(
            'before HELLO after',
        );
    });

    test('applies lower filter to a block', () => {
        expect(template.parse('{% filter lower %}HELLO{% endfilter %} after')).toBe('hello after');
    });

    test('applies filter to block with variable inside', () => {
        expect(
            template.parse('{% filter upper %}{{ name }}{% endfilter %} end', {
                name: 'alice',
            }),
        ).toBe('ALICE end');
    });

    test('applies filter to multiline block', () => {
        expect(template.parse('{% filter upper %}hello\nworld{% endfilter %} end')).toBe(
            'HELLO\nWORLD end',
        );
    });

    test('filter block with mixed static and variable content', () => {
        expect(
            template.parse('{% filter upper %}hello {{ name }}{% endfilter %} end', {
                name: 'world',
            }),
        ).toBe('HELLO WORLD end');
    });

    test('filter block result preserved when endfilter is the last token', () => {
        expect(template.parse('{% filter upper %}hello{% endfilter %}')).toBe('HELLO');
    });

    test('lower filter block as last token', () => {
        expect(template.parse('{% filter lower %}WORLD{% endfilter %}')).toBe('world');
    });

    test('filter block with variable as last token', () => {
        expect(
            template.parse('{% filter upper %}{{ name }}{% endfilter %}', { name: 'alice' }),
        ).toBe('ALICE');
    });

    test('prefix + filter block as last token', () => {
        expect(template.parse('prefix{% filter upper %}hello{% endfilter %}')).toBe('prefixHELLO');
    });

    test('filter block followed by more content is unchanged', () => {
        expect(template.parse('{% filter upper %}hello{% endfilter %} world')).toBe('HELLO world');
    });
});
