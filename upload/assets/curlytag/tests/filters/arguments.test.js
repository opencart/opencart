import { afterEach, describe, expect, test, vi } from 'vite-plus/test';
import { curlytag } from '#curlytag';

afterEach(() => {
    vi.restoreAllMocks();
});

describe('filter arguments', () => {
    test('degrades without throwing when an argument is undefined', () => {
        const log = vi.spyOn(console, 'log').mockImplementation(() => {});

        expect(
            curlytag.parse("{{ s | replace: '%s', missing }}", { s: 'x %s' })
        ).toBe('x %s');
        expect(log).toHaveBeenCalledWith(expect.stringContaining('[Template] Warning: Evaluate error'));
    });
});
