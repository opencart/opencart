import { afterEach, describe, expect, test, vi } from 'vite-plus/test';
import { curlytag } from '#curlytag';

afterEach(() => {
    vi.restoreAllMocks();
});

describe('diagnostics', () => {
    test('records locations for output and tag tokens', () => {
        const output = curlytag.tokenize('text\n{{ value }}').find((token) => token.type === 'output');
        const tag = curlytag.tokenize('text\n{% if value %}{% endif %}').find((token) => token.type === 'tag');

        expect(output).toMatchObject({ raw: '{{ value }}', line: 2, column: 0 });
        expect(tag).toMatchObject({ raw: '{% if value %}', line: 2, column: 0 });
    });

    test('reports the location of invalid tag syntax', () => {
        const log = vi.spyOn(console, 'log').mockImplementation(() => {});

        curlytag.parse('text\n{% if %}');

        expect(log).toHaveBeenCalledWith(
            "[Template] Invalid 'if' syntax line 2 column 0: {% if %}"
        );
    });

    test('reports the error cause and expression for evaluation failures', () => {
        const log = vi.spyOn(console, 'log').mockImplementation(() => {});

        curlytag.parse('{{ missing }}');

        expect(log).toHaveBeenCalledWith(
            expect.stringContaining("[Template] Warning: Evaluate error 'ReferenceError:")
        );
        expect(log).toHaveBeenCalledWith(expect.stringContaining("in expression 'missing'"));
    });
});
