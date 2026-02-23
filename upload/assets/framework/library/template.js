/* OpenCart Twig replacement. Based on Django template syntax. */
class Template {
    static instance = null;

    constructor() {
        this.directory = '';
        this.path = new Map();
        this.cache = new Map();

        this.filter = {
            // Core filters
            escape: (value) => {
                return String(value ?? '').replace(/[&<>"']/g, m => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                }[m] || m));
            },
            upcase: (value) => {
                return value.toUpperCase();
            },
            downcase: (value) => {
                return value.toLowerCase();
            },
            trim: (value) => {
                return value.trim();
            },
            ltrim: (value) => {
                return value.replace(/^\s+/, '');
            },
            rtrim: (value) => {
                return value.replace(/\s+$/, '');
            },
            truncate: (value, max) => {

            },
            prepend: (value, prefix) => {
                return prefix + value;
            },
            append: (value, suffix) => {
                return value + suffix;
            },
            size: (value) => {
                return typeof value === 'array' || typeof value === 'string' ? value.length : 0;
            },
            join: (value, seperator = ' ') => {
                return value.join(seperator);
            },
            // Multi-argument capable filters
            replace: (value, search, replace = '') => {
                return value.replaceAll(search, replace);
            },
            slice: (value, start, length) => {
                return length !== undefined ? value.slice(start, start + length) : value.slice(start);
            },
            // Math filters
            plus: (value, amount) => {
                return value + amount;
            },
            minus: (value, amount) => {
                return value - amount;
            },
            times: (value, amount) => {
                return value * amount;
            },
            divide: (value, amount) => {
                return value / amount;
            },
            modulo: (value, amount) => {
                return value % amount;
            },
            abs: (value) => {
                return Math.abs(value);
            },
            ceil: (value) => {
                return Math.ceil(value);
            },
            floor: (value) => {
                return Math.floor(value);
            },
            round: (value, decimal = 0) => {
                return Number(value).toFixed(decimal);
            }
        };
    }

    addPath(namespace, path = '') {
        if (!path) {
            this.directory = namespace;
        } else {
            this.path.set(namespace, path);
        }
    }

    addFilter(name, filter) {
        this.filter[name] = filter;
    }

    async fetch(path) {
        let file = this.directory + path + '.html';
        let namespace = '';
        let parts = path.split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (this.path.has(namespace)) {
                file = this.path.get(namespace) + path.substr(path, namespace.length) + '.html';
            }
        }

        let response = await fetch(file);

        if (response.status == 200) {
            let object = await response.text();

            this.cache.set(path, object);

            return this.cache.get(path);
        } else {
            console.log('Could not load template file ' + path + '!');
        }

        return '';
    }

    parse(code, data = {}) {
        return this.process(code, data);
    }

    /**
     * Render the template with the current context
     * @returns {string} - Rendered template string
     */
    async render(path, data = {}) {
        return this.parse(await this.fetch(path), data);
    }

    // ─── Main render ────────────────────────────────────────────────────────
    process(template, data = {}) {
        let ctx = data;
        let tokens = this.tokenize(template);

        console.log(tokens);

        let i = 0;
        let stack = [];
        let output = '';

        while (i < tokens.length) {
            let token = tokens[i];
            let top = stack[stack - 1];

            // Handle Tags
            let handle = this['handle' + token.type[0].toUpperCase() + token.type.slice(1)];

            if (handle !== undefined) {
                let jump = handle.bind(this)(token, stack, ctx, i);

                if (typeof jump === 'number' && jump >= 0) {
                    i = jump;

                    continue;
                }

                i++;

                continue;
            }

            if (top && !top.active) {
                console.log('skip ' + token.type + ' ' + token.value);

                i++;

                continue;
            }

            // Handle Text
            if (token.type == 'text') {
                output += token.value;
            }

            // Handle Output
            if (token.type == 'output') {
                let match = token.value.match(/^([^\|]+)\s?\|?\s?(.*)?$/i);

                if (!match) {
                    console.log('[Template] Invalid output: ' + token.value);
                }

                let [full, name, filter] = match;

                let value = this.evaluate(name, ctx);

                // Apply Filters
                if (filter !== undefined) {
                    value = this.applyFilter(value, filter.indexOf(' | ') !== -1 ? filter.split(' | ') : [filter]);
                }

                output += this.filter.escape(value ?? '');
            }

            i++;
        }

        return output;
    }

    tokenize(template) {
        let token = [];
        let index = 0;
        let match;

        let openclose = {
            if: [
                'elsif',
                'else',
                'endif',
            ],
            elsif: [
                'elsif',
                'else',
                'endif',
            ],
            else: [
                'endif',
                'endcase'
            ],
            when: [
                'when',
                'else',
                'endcase'
            ],
            for: [
                'endfor'
            ]
        };

        let stack = [];

        const regex = /\{\{\s([\s\S]*?)\s\}\}|\{%-?\s([\s\S]*?)\s-?%}/g;

        while ((match = regex.exec(template)) !== null) {
            let [full, output, tag] = match;

            // Grabs all the Text before the matched index.
            if (match.index > index) {
                token.push({
                    type: 'text',
                    value: template.slice(index, match.index)
                });
            }

            // Capture output tag {{ my_var }}
            if (output !== undefined) {
                token.push({
                    type: 'output',
                    value: output
                });
            }

            // Capture statement tag {% if my_var %}
            if (tag !== undefined) {
                let command = tag.match(/^\S+/)[0].toLowerCase();

                // Handle Close Tags
                let top = stack[stack.length - 1];

                if (top && openclose[top.type].includes(command)) {
                    token[top.index].end = token.length;

                    stack.pop();
                }

                // Handle Open Tags
                if (command in openclose) {
                    // Remember this opening tag's token index
                    stack.push({
                        type: command,
                        index: token.length   // current length = index before push
                    });
                }

                token.push({
                    type: command,
                    value: tag,
                    index: token.length
                });
            }

            // Records the last match
            index = regex.lastIndex;
        }

        // Grab any remaining template code since the last tag.
        if (index < template.length) {
            token.push({
                type: 'text',
                value: template.slice(index)
            });
        }

        // Warning for unclosed blocks
        if (stack.length) {
            console.log('[Template] Warning: ' + stack.length + ' unclosed block(s): ' +  stack.map(value => value.type));
        }

        return token;
    }

    /**
     * Quick check if current position is inside a raw block
     */
    isRaw(stack) {
        return stack.some(frame => frame.type === 'raw');
    }

    /**
     * Quick check if current position is inside a comment block
     */
    isComment(stack) {
        return stack.some(frame => frame.type === 'comment');
    }

    evaluate(expression, ctx) {
        if (!expression) return undefined;

        try {
            const safe = expression;

            console.log(`return (${safe});`);

            let func = new Function('data', `with(data) { return (${safe}); }`);

            return func(ctx);
        } catch {
            return undefined;
        }
    }

    /**
     * Apply chain of filters — now supports multiple comma-separated arguments per filter
     */
    applyFilter(value, filters = {}) {
        if (!filters.length) return value;

        let result = value;

        console.log(result);

        for (let filter of filters) {
            let name = filter;
            let data = [];

            let index = filter.indexOf(': ');

            if (index !== -1) {
                name = filter.substr(0, index);

                // Extract the arguments
                data = filter.substr(index + 2).split(', ').map(value => value.trim().replace(/^["']?(.*?)["']?$/, '$1'));

                console.log(data);
            }

            let func = this.filter[name];

            if (!func) continue;

            console.log(result);

            result = func(result, ...data);
        }

        return result;
    }

    // ─── Tag handlers ────────────────────────────────────────────────────────
    // Unified handler map — all handlers get the same 4 arguments

    /**
     * Handle assign statement
     *
     * assign var = expression | filter1 | filter2
     */
    handleAssign(token, stack, ctx, index, output) {
        let match = token.value.match(/^assign\s(\w+)\s=\s([^\|]+)?\s?\|?\s?(.*)?$/i);

        if (!match) {
            console.log('[Template] Invalid assign syntax: ' + token.value);

            return;
        }

        let [, name, value, filter] = match;

        value = this.evaluate(value, ctx);

        // Apply Filters
        if (filter !== undefined) {
            value = this.applyFilter(value, filter.indexOf(' | ') !== -1 ? filter.split(' | ') : [filter]);
        }

        // Add key value
        ctx[name] = value;
    }

    /**
     * Handle assign statement
     *
     * assign var = expression | filter1 | filter2
     */
    handleIf(token, stack, ctx, index) {
        let match = token.value.match(/^if\s(.+)$/i);

        if (!match) {
            console.log('[Template] Invalid if syntax: ' + token.value);

            return;
        }

        // Check to see if a previous tag is inactive
        let active = this.evaluate(match[1], ctx);

        stack.push({
            type: 'if',
            active: active
        });

        if (!active) return token.end;
    }

    handleEndif(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'if') return;

        stack.pop();
    }

    handleUnless(token, stack, ctx, index) {
        let match = token.value.match(/^unless\s(.+)$/);

        if (!match) {
            console.log('[Template] Invalid unless syntax: ' + token.value);

            return;
        }

        // Check to see if a previous tag is inactive
        let active = !this.evaluate(match[1], ctx);

        stack.push({
            type: 'unless',
            active: active
        });

        if (!active) return token.end;
    }

    handleEndunless(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'unless') return;

        stack.pop();
    }

    handleElsif(token, stack, ctx, index) {
        let match = token.value.match(/^elsif\s(.+)$/);

        if (!match) {
            console.log('[Template] Invalid elsif syntax: ' + token.value);

            return;
        }

        let top = stack[stack.length - 1];

        if (!top || top.type !== 'if') return;

        if (top.active) return token.end;

        // If any previous not active tags then set current tag to false;
        top.active = this.evaluate(match[1], ctx);
    }

    handleElse(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || (top.type !== 'if' && top.type !== 'unless' && top.type !== 'case')) return;

        // If any previous not active tags then set current tag to false;
        if (top.active) return token.end;

        top.active = true;
    }

    handleFor(token, stack, ctx, index) {
        let match = token.value.match(/^for\s(\w*)\sin\s([^\s]+)\s?(.*)$/);

        if (!match) {
            console.log('[Template] Invalid for syntax: ' + token.value);

            return;
        }

        let [, name, key, filter] = match;

        let items = [];

        if (key.indexOf('..') == -1) {
            items = this.evaluate(key, ctx);
        } else {
            items = this.getRange(key, ctx);
        }

        if (typeof items !== 'object') {
            items = [];
        }

        // Parse optional offset argument
        let offset = this.getOffset(filter, items, ctx);

        // Parse optional limit argument
        let limit = this.getLimit(filter, items, ctx);

        // Reversed
        items = this.getReversed(filter, items, ctx);

        // Now safe to slice
        items = items.slice(offset, (offset + limit));

        stack.push({
            type: 'for',
            name: name,
            items: items,
            index: -1,
            start: index + 1,
            end: token.end,
            parent: { ...ctx }
        });

        return token.end;
    }

    handleEndfor(token, stack, ctx, index) {
        // If skip we don't want to run evaluate method.
        let top = stack[stack.length - 1];

        if (top == undefined || top.type !== 'for') return;

        top.index++;

        if (top.index < top.items.length) {
            // Restore parent context (prevents leakage)
            Object.assign(ctx, top.parent);

            ctx[top.name] = top.items[top.index];  // ← top.name (not top.name)

            ctx.loop = {
                index: top.index + 1,
                index0: top.index,
                first: top.index === 0,
                last: top.index === top.items.length - 1,
                length: top.items.length,
                rindex: top.items.length - top.index,
                rindex0: top.items.length - top.index - 1
            };

            return top.start;
        }

        delete ctx.loop;

        // Loop finished → cleanup
        stack.pop();
    }

    handleContinue(token, stack, ctx, index) {
        for (let i = stack.length -1; i >= 0; i--) {
            if (stack[i].type == 'for') {
                return stack[i].end;
            }

            // Remove all tags before endfor loop.
            stack.pop();
        }
    }

    handleBreak(token, stack, ctx, index) {
        let next = 0;

        for (let i = stack.length -1; i >= 0; i--) {
            if (stack[i].type == 'for') {
                next = stack[i].end + 1;

                break;
            }

            // Remove all tags before endfor loop.
            stack.pop();
        }

        // Loop finished → cleanup
        stack.pop();

        if (next) return next;
    }

    getRange(filter, ctx) {
        let match = filter.match(/\(([^\.]+)\.\.([^\.]+)\)/i);

        if (!match) return;

        let start = match[1];
        let end = match[2];

        let is_var = /[a-z]/i;

        if (is_var.test(start)) {
            start = this.evaluate(start, ctx);
        }

        if (is_var.test(end)) {
            end = this.evaluate(end, ctx);
        }

        let range = [];

        for (let i = start; i <= end; i++) {
            range.push(Number(i));
        }

        return range;
    }

    getOffset(filter, items, ctx) {
        let offset = 0;

        let match = filter.match(/offset:\s([0-9]+)|offset:\s([^\s]+)/i);

        if (!match) return offset;

        // If match is in 0-9
        if (match[1]) {
            return Number(match[1]);
        }

        // If match variable
        if (match[2]) {
            return Number(this.evaluate(match[2], ctx));
        }
    }

    getLimit(filter, items, ctx) {
        let limit = items.length;

        let match = filter.match(/limit:\s([0-9]+)|limit:\s([^\s]+)/i);

        if (!match) return limit;

        // If match is in 0-9
        if (match[1]) {
            return Number(match[1]);
        }

        // If match is not in 0-9 it should be a variable
        if (match[2]) {
            return Number(this.evaluate(match[2], ctx));
        }
    }

    getReversed(filter, items, ctx) {
        let reversed = false;

        if (filter.match(/(reversed)/i) !== null) {
            return items.reverse();
        }

        return items;
    }

    handleCase(token, stack, ctx, index) {
        let match = token.value.match(/^case\s(\w+)$/);

        if (!match) {
            console.log('[Template] Invalid case syntax: ' + token.value);

            return;
        }

        stack.push({
            type: 'case',
            value: match[1],
            active: false
        });
    }

    handleWhen(token, stack, ctx, index) {
        let match = token.value.match(/^when\s(.+)$/);

        if (!match) {
            console.log('[Template] Invalid when syntax: ' + token.value);

            return;
        }

        let top = stack[stack.length - 1];

        if (!top || top.type !== 'case') return;

        // Split if more than one item to compare
        if (!this.evaluate('[' + match[1] + '].includes(' + top.value + ')', ctx)) return token.end;

        top.active = true;
    }

    handleEndcase(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || (top.type !== 'case' && top.type !== 'else')) return;

        stack.pop();
    }

    getArg(value, ctx) {
        // Match String
        let string = value.match(/^["'](.*)["']$/i);

        if (string) return string[1];

        // Match Digit
        let number = value.match(/^(-?\d+)$/i);

        if (number) return Number(number[1]);

        // Match Float
        let float = value.match(/^(-?\d+.\d+)$/i);

        if (float) return parseFloat(float[1]);

        // Match Boolean
        let boolean = value.match(/^(true|false)$/i);

        if (boolean) return boolean[1] == 'true' ? true : false;

        // If match variable
        return this.evaluate(value, ctx);
    }

    handleCapture(token, stack, ctx, index) {
        let match = token.value.match(/^capture\s(.+)$/);

        if (!match) {
            console.warn('[Template] Invalid capture syntax: ' + token.value);

            return;
        }

        stack.push({
            type: 'capture',
            name: match[1],
            content: ''
        });
    }

    handleEndcapture(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (top?.type === 'capture') {
            ctx[top.name] = top.content;

            stack.pop();
        }
    }

    handleRaw(token, stack, ctx, index) {
        stack.push({ type: 'raw' });
    }

    handleEndraw(token, stack, ctx, index) {
        while (stack.length && stack[stack.length - 1].type !== 'raw') stack.pop();

        if (stack.length) stack.pop();
    }

    handleComment(token, stack, ctx, index) {
        stack.push({ type: 'comment' });
    }

    handleEndcomment(token, stack, ctx, index) {
        while (stack.length && stack[stack.length - 1].type !== 'comment') stack.pop();

        if (stack.length) stack.pop();
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Template();
        }

        return this.instance;
    }
}

const template = Template.getInstance();

export { template };

// Assign
let test = [];

// 0
test.push(`--- assign string ---
{% assign my_var = "Hello test" %}
{{ my_var }}, world!
`);

// 1
test.push(`--- assign number ---
{% assign my_var = 121213 %}
{{ my_var }}, world!
`);

// 2
test.push(`--- assign decimal ---
{% assign my_var = 1212.13 %}
{{ my_var }}, world!
`);

// 3
test.push(`--- assign replace ---
{% assign my_var = "Hello world" | replace: 'e', 'A' %}
{{ my_var }}, world!
`);

// 4
test.push(`--- ucase ---
{% assign my_var = "Hello world" %}
{{ my_var | upcase }}
`);

// 5
test.push(`--- replace --- 
{% assign my_var = "Hello world" %}
{{ my_var | replace: "e", "Q" }}, world!
`);

// 6
test.push(`--- assign test ---
{% assign year = 2025 %}
Current year: {{ year }}
`);

// 7
test.push(`{% assign my_var = "Hello, World" | downcase | upcase | replace: "O", "X" %}{{ my_var }}`);

// 8
test.push(`{% assign my_var = "Hello" %}{% if my_var == "Hello" %}WORKS!{% endif %}`);

// 9
test.push(`{% assign my_var = "red" %}{% if my_var == "red" %}red!{% else %}blue{% endif %}`);

// 10
test.push(`{% assign my_var = "blue" %}{% if my_var == "red" %}red!{% else %}blue{% endif %}`);

// 11
test.push(`{% assign my_var = "green" %}{% if my_var == "red" %}red!{% elsif my_var == "green" %}green{% else %}blue{% endif %}`);

// 12
test.push(`{% assign my_var = "red" %}
{% if my_var == "red" %}
red!
{% elsif my_var == "green" %}
green!
{% if my_var != "blue" %}
blue
{% else %}
yellow
{% endif %}
{% else %}
black
{% if my_var != "blue" %}
blue
{% else %}
yellow
{% endif %}
{% endif %}`);

// 13
test.push(`
{% for user in users %}
  Name: {{ user.name }}
  Favorite colors:
  {% for color in user.colors %}
    • {{ color }}
  {% endfor %}
  ---
{% endfor %}
`);

// 14
test.push(`
{% for user in users offset: 0 limit: 4 %}
  Name: {{ user.name }}  {{ loop.index }}
  Favorite colors:
  {% for color in user.colors %}
    • {{ color }} {{ loop.index }}
  {% endfor %}
  ---
{% endfor %}
`);

// 15
test.push(`
{% for i in (1..5) %}

  {% if i == 4 %}
      skip
    {% continue %}
  {% endif %}
  
  {{ i }}
  
{% endfor %}
`);

// 16
test.push(`
{% assign handle = 100 %}
{% case handle %}
  {% when "yellow" %}
     yellow
  {% when "pink", "red" %}
     pink | red
{% when 100 %}
     100
{% when 100.00, 200 %}
     100.00
{% when true %}
     true
{% when false %}
     true
{% when 'red' %}
     red
{% else %}
     none
{% endcase %}
`);

// 17
test.push(`
{% assign my_var = 100 %}
{% case my_var %}
  {% when "yellow" %}
     yellow
  {% when "pink", "red" %}
     pink | red
{% when 100 %}
     100
{% when 100.00, 200 %}
     100.00
{% when true %}
     true
{% when false %}
     true
{% when 'red', "test 3456" %}
     red
{% else %}
     none
{% endcase %}
`);
let number = 17;

await test.splice(number, 1).map(async value => {
    console.log('TEMPLATE');
    console.log(value);

    // Loop
    const data = {
        users: [
            {
                name: "Alice",
                colors: [
                    "red",
                    "blue",
                    "green"
                ]
            },
            {
                name: "Bob",
                colors: [
                    "yellow"
                ]
            },
            {
                name: "Carol",
                colors: [
                    "purple",
                    "pink"
                ]
            }
        ]
    };

    let output = await template.parse(value, data);

    console.log('OUTPUT');
    console.log(output);
});