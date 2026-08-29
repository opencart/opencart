import { describe, expect, test } from 'vite-plus/test';
import { curlytag } from '#curlytag';

describe('for', () => {
    describe('continue', () => {
        test('skips current iteration', () => {
            const tpl
                = '{% for n in nums %}{% if n == 2 %}{% continue %}{% endif %}{{ n }}{% endfor %}';
            expect(curlytag.parse(tpl, { nums: [ 1, 2, 3 ] })).toBe('13');
        });

        test('outside loop is a no-op', () => {
            expect(curlytag.parse('before{% continue %}after')).toBe('beforeafter');
        });

        test('outside loop does not corrupt if/else', () => {
            expect(
                curlytag.parse('{% if true %}{% continue %}{% else %}hidden{% endif %}result')
            ).toBe('result');
        });

        test('outside loop does not corrupt elseif', () => {
            expect(
                curlytag.parse(
                    '{% if true %}{% continue %}{% elseif false %}hidden{% endif %}result'
                )
            ).toBe('result');
        });

        test('outside loop does not corrupt case', () => {
            const tpl
                = '{% case status %}{% when "ok" %}{% continue %}OK{% when "err" %}ERR{% endcase %}';
            expect(curlytag.parse(tpl, { status: 'ok' })).toBe('OK');
        });
    });
});
