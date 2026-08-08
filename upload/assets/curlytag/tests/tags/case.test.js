import { describe, expect, test } from 'vite-plus/test';
import { template } from '#curlytag';

describe('case / when / endcase', () => {
    test('matches correct when branch', () => {
        const tpl =
            '{% case color %}{% when "red" %}R{% when "green" %}G{% when "blue" %}B{% endcase %}';
        expect(template.parse(tpl, { color: 'green' })).toBe('G');
    });

    test('non-matching produces no output', () => {
        const tpl = '{% case color %}{% when "red" %}R{% when "green" %}G{% endcase %}';
        expect(template.parse(tpl, { color: 'blue' })).toBe('');
    });

    test('else acts as default branch', () => {
        const tpl = '{% case color %}{% when "red" %}R{% else %}?{% endcase %}';
        expect(template.parse(tpl, { color: 'purple' })).toBe('?');
    });

    test('when accepts multiple values', () => {
        const tpl = '{% case color %}{% when "red", "crimson" %}R{% endcase %}';
        expect(template.parse(tpl, { color: 'crimson' })).toBe('R');
    });

    test('one level dotted path matches correct branch', () => {
        const tpl = '{% case user.role %}{% when "admin" %}A{% when "guest" %}G{% endcase %}';
        expect(template.parse(tpl, { user: { role: 'admin' } })).toBe('A');
    });

    test('one level dotted path non-matching branch produces no output', () => {
        const tpl = '{% case user.role %}{% when "admin" %}A{% when "guest" %}G{% endcase %}';
        expect(template.parse(tpl, { user: { role: 'moderator' } })).toBe('');
    });

    test('one level dotted path uses else as default', () => {
        const tpl = '{% case user.role %}{% when "admin" %}A{% else %}?{% endcase %}';
        expect(template.parse(tpl, { user: { role: 'guest' } })).toBe('?');
    });

    test('two level dotted path resolves correctly', () => {
        const tpl =
            '{% case order.status.code %}{% when "paid" %}P{% when "pending" %}W{% endcase %}';
        expect(template.parse(tpl, { order: { status: { code: 'pending' } } })).toBe('W');
    });

    test('three level dotted path resolves correctly', () => {
        const tpl = '{% case a.b.c.value %}{% when "x" %}X{% when "y" %}Y{% endcase %}';
        expect(template.parse(tpl, { a: { b: { c: { value: 'y' } } } })).toBe('Y');
    });

    test('deeply nested path with multiple when values', () => {
        const tpl =
            '{% case user.profile.settings.theme %}{% when "dark", "black" %}D{% when "light" %}L{% endcase %}';
        expect(template.parse(tpl, { user: { profile: { settings: { theme: 'black' } } } })).toBe(
            'D',
        );
    });

    test('dotted path with missing intermediate key produces no output', () => {
        const tpl = '{% case user.role %}{% when "admin" %}A{% endcase %}';
        expect(template.parse(tpl, { user: {} })).toBe('');
    });

    test('flat variable still works after the fix', () => {
        const tpl = '{% case status %}{% when "ok" %}OK{% when "err" %}ERR{% endcase %}';
        expect(template.parse(tpl, { status: 'ok' })).toBe('OK');
    });
});
