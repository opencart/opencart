import { template } from '../curlytag.js';

template.addPath('playground/');

let passed = 0;
let failed = 0;

const assert = (condition, message) => {
    if (condition) {
        passed++;
        console.log(`  ✓ ${message}`);
    } else {
        failed++;
        console.error(`  ✗ ${message}`);
    }
};

const eq = (actual, expected, message) => {
    if (actual === expected) {
        passed++;
        console.log(`  ✓ ${message}`);
    } else {
        failed++;
        console.error(`  ✗ ${message}`);
        console.error(`    expected: ${JSON.stringify(expected)}`);
        console.error(`    actual:   ${JSON.stringify(actual)}`);
    }
};

// text output
console.log('\ntext output');
eq(template.parse('hello world'), 'hello world', 'plain text passes through');
eq(template.parse(''), '', 'empty string returns empty');
eq(
    template.parse('Price: $100 & 50% off'),
    'Price: $100 & 50% off',
    'special characters pass through',
);

// variables
console.log('\nvariables');
eq(template.parse('{{ name }}', { name: 'Alice' }), 'Alice', 'simple variable');
eq(
    template.parse('{{ html }}', { html: '<b>bold</b>' }),
    '&lt;b&gt;bold&lt;/b&gt;',
    'escapes HTML by default',
);
eq(template.parse('{{ user.name }}', { user: { name: 'Bob' } }), 'Bob', 'dot notation');
eq(template.parse('{{ a.b.c }}', { a: { b: { c: 'deep' } } }), 'deep', 'deep nesting');
eq(template.parse('{{ missing }}'), '', 'undefined variable renders empty');
eq(template.parse('{{ v }}', { v: null }), '', 'null renders empty');
eq(template.parse('{{ n }}', { n: 0 }), '0', 'numeric zero renders "0"');
eq(template.parse('{{ v }}', { v: false }), 'false', 'boolean false renders "false"');

// filters
console.log('\nfilters');
eq(template.parse('{{ name | upper }}', { name: 'alice' }), 'ALICE', 'upper');
eq(template.parse('{{ name | lower }}', { name: 'ALICE' }), 'alice', 'lower');
eq(template.parse('{{ text | trim }}', { text: '  hi  ' }), 'hi', 'trim');
eq(
    template.parse('{{ greeting | replace: "world", "earth" }}', { greeting: 'hello world' }),
    'hello earth',
    'replace',
);
eq(
    template.parse('{{ name | upper | truncate: 3, "." }}', { name: 'alice' }),
    'AL.',
    'truncate + chained',
);
eq(template.parse('{{ items | join: ", " }}', { items: ['a', 'b', 'c'] }), 'a, b, c', 'join');
eq(
    template.parse('{{ items | reverse | join: "" }}', { items: ['a', 'b', 'c'] }),
    'cba',
    'reverse',
);
eq(template.parse('{{ items | first }}', { items: [10, 20, 30] }), '10', 'first');
eq(template.parse('{{ items | last }}', { items: [10, 20, 30] }), '30', 'last');
eq(template.parse('{{ items | length }}', { items: [1, 2, 3] }), '3', 'length');
eq(template.parse('{{ n | plus: 5 }}', { n: 10 }), '15', 'plus');
eq(template.parse('{{ n | minus: 3 }}', { n: 10 }), '7', 'minus');
eq(template.parse('{{ n | round: 2 }}', { n: 3.14159 }), '3.14', 'round');
eq(template.parse('{{ n | abs }}', { n: -42 }), '42', 'abs');
eq(template.parse('{{ q | urlencode }}', { q: 'hello world' }), 'hello%20world', 'urlencode');
eq(template.parse('{{ missing | default: "none" }}'), 'none', 'default');
eq(
    template.parse('{{ name | nonexistent_filter }}', { name: 'test' }),
    '',
    'unknown filter returns empty',
);

// assign
console.log('\nassign');
eq(template.parse('{% assign x = 42 %}{{ x }}'), '42', 'assign sets variable');
eq(template.parse('{% assign name = "alice" | upper %}{{ name }}'), 'ALICE', 'assign with filter');

// if / elseif / else
console.log('\nconditions');
eq(template.parse('{% if show %}yes{% endif %}', { show: true }), 'yes', 'truthy branch');
eq(template.parse('{% if show %}yes{% endif %}', { show: false }), '', 'falsy branch');
eq(template.parse('{% if show %}yes{% else %}no{% endif %}', { show: false }), 'no', 'else branch');
eq(
    template.parse('{% if a %}A{% elseif b %}B{% else %}C{% endif %}', { a: false, b: true }),
    'B',
    'elseif',
);

// unless
console.log('\nunless');
eq(
    template.parse('{% unless hidden %}visible{% endunless %}', { hidden: false }),
    'visible',
    'renders when falsy',
);
eq(
    template.parse('{% unless hidden %}visible{% endunless %}', { hidden: true }),
    '',
    'skips when truthy',
);

// for loops
console.log('\nfor loops');
eq(
    template.parse('{% for item in items %}{{ item }} {% endfor %}', { items: ['a', 'b', 'c'] }),
    'a b c ',
    'iterates array',
);
eq(
    template.parse('{% for x in items %}{{ loop.index }}{% endfor %}', { items: ['a', 'b'] }),
    '12',
    'loop.index',
);
eq(
    template.parse(
        '{% for x in items %}{% if loop.first %}[{% endif %}{{ x }}{% if loop.last %}]{% endif %}{% endfor %}',
        { items: ['a', 'b', 'c'] },
    ),
    '[abc]',
    'loop.first / loop.last',
);
eq(template.parse('{% for x in items %}{{ x }}{% endfor %}', { items: [] }), '', 'empty array');
eq(
    template.parse(
        '{% for n in nums %}{% if n == 2 %}{% continue %}{% endif %}{{ n }}{% endfor %}',
        { nums: [1, 2, 3] },
    ),
    '13',
    'continue',
);
eq(
    template.parse('{% for n in nums %}{% if n == 3 %}{% break %}{% endif %}{{ n }}{% endfor %}', {
        nums: [1, 2, 3, 4],
    }),
    '12',
    'break',
);
eq(
    template.parse(
        '{% for row in matrix %}{% for cell in row %}{{ cell }}{% endfor %}-{% endfor %}',
        {
            matrix: [
                [1, 2],
                [3, 4],
            ],
        },
    ),
    '12-34-',
    'nested loops',
);
eq(
    template.parse('{% for x in items | sort %}{{ x }}{% endfor %}', { items: ['c', 'a', 'b'] }),
    'abc',
    'for with filter',
);

// comments
console.log('\ncomments');
eq(template.parse('A{% comment %}hidden{% endcomment %}B'), 'AB', 'block comment stripped');
eq(template.parse('A{# this is a comment #}B'), 'AB', 'twig-style comment stripped');

// raw
console.log('\nraw');
eq(
    template.parse('{% raw %}{{ not_a_var }}{% endraw %}'),
    '{{ not_a_var }}',
    'preserves syntax literally',
);

// case / when
console.log('\ncase / when');
eq(
    template.parse(
        '{% case color %}{% when "red" %}R{% when "green" %}G{% when "blue" %}B{% endcase %}',
        { color: 'green' },
    ),
    'G',
    'matches correct branch',
);
eq(
    template.parse('{% case color %}{% when "red" %}R{% when "green" %}G{% endcase %}', {
        color: 'blue',
    }),
    '',
    'non-matching produces empty',
);
eq(
    template.parse('{% case color %}{% when "red" %}R{% else %}?{% endcase %}', {
        color: 'purple',
    }),
    '?',
    'else as default',
);
eq(
    template.parse('{% case color %}{% when "red", "crimson" %}R{% endcase %}', {
        color: 'crimson',
    }),
    'R',
    'when with multiple values',
);

// capture
console.log('\ncapture');
eq(
    template.parse('{% capture msg %}Hello!{% endcapture %}{{ msg }}'),
    'Hello!',
    'stores block content',
);

// whitespace control
console.log('\nwhitespace control');
eq(template.parse('{{- name }}', { name: '  hello' }), 'hello', 'leading dash trims leading');
eq(template.parse('{{ name -}}', { name: 'hello  ' }), 'hello', 'trailing dash trims trailing');
eq(template.parse('{{- name -}}', { name: '  hello  ' }), 'hello', 'both dashes trim both');

// custom filters
console.log('\ncustom filters');
template.addFilter('shout', (v) => v + '!!!');
eq(template.parse('{{ msg | shout }}', { msg: 'hello' }), 'hello!!!', 'custom filter');
template.addFilter('repeat', (v, n) => v.repeat(n));
eq(template.parse('{{ char | repeat: 3 }}', { char: 'ha' }), 'hahaha', 'custom filter with arg');
template.addFilter('exclaim', (v) => v + '!');
eq(template.parse('{{ msg | upper | exclaim }}', { msg: 'hi' }), 'HI!', 'custom + built-in chain');

// filter block
console.log('\nfilter block');
eq(
    template.parse('{% filter upper %}hello{% endfilter %}text'),
    'HELLOtext',
    'filter block applies upper',
);
eq(
    template.parse('{% filter lower %}HELLO{% endfilter %} after'),
    'hello after',
    'filter block applies lower',
);

// echo
console.log('\necho');
eq(
    template.parse('{% echo greeting %}!', { greeting: 'hello' }),
    'hello!',
    'echo outputs variable',
);
eq(template.parse('say: {% echo "world" %}!', {}), 'say: world!', 'echo outputs literal');
eq(template.parse('{% echo name | upper %}!', { name: 'alice' }), 'ALICE!', 'echo with filter');

// render() file loading
console.log('\nrender (file loading)');

const loop = await template.render('examples/loop/template', { team: ['Alice', 'Bob', 'Carol'] });
assert(loop.includes('Alice'), 'loop template contains Alice');
assert(loop.includes('Bob'), 'loop template contains Bob');

const adminTpl = await template.render('examples/conditions/template', { role: 'admin' });
assert(adminTpl.includes('Admin access'), 'conditions: admin branch');
assert(!adminTpl.includes('Editor access'), 'conditions: no editor in admin branch');

const editorTpl = await template.render('examples/conditions/template', { role: 'editor' });
assert(editorTpl.includes('Editor access'), 'conditions: editor branch');

const guestTpl = await template.render('examples/conditions/template', { role: 'guest' });
assert(guestTpl.includes('Viewer access'), 'conditions: else branch');

const filtersTpl = await template.render('examples/filters/template', {
    title: 'hello',
    greeting: 'Hello world',
    price: 9.999,
    tags: ['js', 'html'],
});
assert(filtersTpl.includes('HELLO'), 'filters: upper applied');
assert(filtersTpl.includes('Hey world'), 'filters: replace applied');
assert(filtersTpl.includes('10.00'), 'filters: round applied');

const nestedTpl = await template.render('examples/nested/template', {
    title: 'Team',
    users: [
        { name: 'Alice', active: true, roles: ['admin'] },
        { name: 'Bob', active: false, roles: ['editor'] },
    ],
});
assert(nestedTpl.includes('Alice'), 'nested: contains Alice');
assert(nestedTpl.includes('ADMIN'), 'nested: contains ADMIN');
assert(nestedTpl.includes('EDITOR'), 'nested: contains EDITOR');

template.cache.clear();
await template.render('examples/loop/template', { team: ['X'] });
assert(template.cache.has('examples/loop/template'), 'template is cached after first render');

const missing = await template.render('examples/does-not-exist/template', {});
eq(missing, '', 'non-existent file returns empty string');

// summary
console.log(`\n${passed} passed, ${failed} failed`);
process.exit(failed > 0 ? 1 : 0);
