import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('string', () => {
    test('upper', () => {
        expect(curlytag.parse('{{ name | upper }}', { name: 'alice' })).toBe('ALICE');
    });

    test('lower', () => {
        expect(curlytag.parse('{{ name | lower }}', { name: 'ALICE' })).toBe('alice');
    });

    describe('sprintf', () => {
        test('replaces string placeholders', () => {
            expect(
                curlytag.parse("{{ value | sprintf: 'world' }}", { value: 'Hello %s' })
            ).toBe('Hello world');
        });

        test('truncates numbers for integer placeholders', () => {
            expect(curlytag.parse('{{ value | sprintf: 12.8 }}', { value: 'Items: %d' })).toBe(
                'Items: 12'
            );
        });

        test('replaces context values in order', () => {
            expect(
                curlytag.parse('{{ value | sprintf: product.quantity, product.name }}', {
                    value: 'OpenCart has %d %s in stock',
                    product: { name: 'products', quantity: 3 }
                })
            ).toBe('OpenCart has 3 products in stock');
        });

        test('renders escaped percent signs without consuming an argument', () => {
            expect(
                curlytag.parse("{{ value | sprintf: 'complete' }}", {
                    value: '100%% %s'
                })
            ).toBe('100% complete');
        });

        test('preserves zero and false string values', () => {
            expect(
                curlytag.parse('{{ value | sprintf: quantity, enabled }}', {
                    enabled: false,
                    quantity: 0,
                    value: '%s:%s'
                })
            ).toBe('0:false');
        });

        test('renders nothing for missing arguments', () => {
            expect(curlytag.parse('{{ value | sprintf }}', { value: '%s:%d' })).toBe(':');
        });

        test('renders nothing for a non numeric integer placeholder', () => {
            expect(
                curlytag.parse("{{ value | sprintf: 'invalid' }}", { value: '%d' })
            ).toBe('');
        });
    });

    test('replace', () => {
        expect(
            curlytag.parse('{{ greeting | replace: "world", "earth" }}', {
                greeting: 'hello world'
            })
        ).toBe('hello earth');
    });

    test('trim', () => {
        expect(curlytag.parse('{{ text | trim }}', { text: '  hi  ' })).toBe('hi');
    });

    test('truncate', () => {
        expect(curlytag.parse('{{ name | upper | truncate: 3, "." }}', { name: 'alice' })).toBe(
            'AL.'
        );
    });

    test('striptag removes basic html tags', () => {
        expect(curlytag.parse('{{ html | striptag }}', { html: '<p>hello</p>' })).toBe('hello');
    });

    test('striptag removes script block including contents', () => {
        expect(
            curlytag.parse('{{ html | striptag }}', { html: "<script>alert('xss')</script>hello" })
        ).toBe('hello');
    });

    test('striptag removes style block including contents', () => {
        expect(
            curlytag.parse('{{ html | striptag }}', {
                html: '<style>body{color:red}</style>hello'
            })
        ).toBe('hello');
    });

    test('striptag removes html comments', () => {
        expect(curlytag.parse('{{ html | striptag }}', { html: '<!-- comment -->hello' })).toBe(
            'hello'
        );
    });

    test('striptag leaves plain text untouched', () => {
        expect(curlytag.parse('{{ html | striptag }}', { html: 'just text' })).toBe('just text');
    });

    test('striptag removes multiline script with attributes including contents', () => {
        const html = '<script type="module">\nconsole.log("hi");\n</script>hello';
        expect(curlytag.parse('{{ html | striptag }}', { html })).toBe('hello');
    });

    test('striptag removes multiline style with attributes including contents', () => {
        const html = '<style type="text/css">\nbody { color: red; }\n</style>hello';
        expect(curlytag.parse('{{ html | striptag }}', { html })).toBe('hello');
    });
});
