import { afterEach, describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

const mountNamedElement = () => {
    const form = document.createElement('form');

    form.id = 'product';
    form.method = 'post';
    document.body.append(form);

    return form;
};

afterEach(() => {
    document.getElementById('product')?.remove();
});

describe('browser globals', () => {
    test.fails('does not resolve window.name as a template variable', () => {
        const previousName = window.name;

        window.name = 'OpenCart';

        try {
            expect(curlytag.parse('{{ name }}', {})).toBe('');
        } finally {
            window.name = previousName;
        }
    });

    test('does not render named elements in simple conditions', () => {
        const form = mountNamedElement();

        expect(window.product).toBe(form);
        expect(curlytag.parse('{% if product %}yes{% else %}no{% endif %}', {})).toBe('no');
    });

    test.fails('does not resolve named elements inside expressions', () => {
        const form = mountNamedElement();

        expect(window.product).toBe(form);
        expect(curlytag.parse('{% if product and true %}yes{% else %}no{% endif %}', {})).toBe('no');
    });

    test.fails('does not output named elements', () => {
        const form = mountNamedElement();

        expect(window.product).toBe(form);
        expect(curlytag.parse('{{ product }}', {})).toBe('');
    });

    test.fails('does not read properties from named elements', () => {
        const form = mountNamedElement();

        expect(window.product).toBe(form);
        expect(curlytag.parse('{{ product.method }}', {})).toBe('');
    });

    test.fails('does not expose window properties', () => {
        expect(curlytag.parse('{{ window.location }}', {})).toBe('');
    });

    test.fails('does not expose inherited object properties', () => {
        expect(curlytag.parse('{{ constructor }}', {})).toBe('');
    });

    test('uses explicit context instead of named elements', () => {
        const form = mountNamedElement();

        expect(window.product).toBe(form);
        expect(curlytag.parse('{{ product.name }}', {
            product: { name: 'OpenCart' }
        })).toBe('OpenCart');
    });
});
