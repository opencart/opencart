/* OpenCart Twig replacement. Based on Django template syntax. */
class Template {
    static instance = null;

    constructor() {
        this.directory = '';
        this.path = new Map();
        this.cache = new Map();

        // Unified handler map — all handlers get the same 4 arguments
        this.tag = {
            assign: this.handleAssign.bind(this),
            if: this.handleIf.bind(this),
            endif: this.handleEndif.bind(this),
            elsif: this.handleElsif.bind(this),
            else: this.handleElse.bind(this),
            unless: this.handleUnless.bind(this),
            endunless: this.handleEndunless.bind(this),
            for: this.handleFor.bind(this),
            endfor: this.handleEndfor.bind(this),
            continue: this.handleContinue.bind(this),
            break: this.handleBreak.bind(this),
            case: this.handleCase.bind(this),
            when: this.handleWhen.bind(this),
            endcase: this.handleEndcase.bind(this),
            capture: this.handleCapture.bind(this),
            endcapture: this.handleEndcapture.bind(this),
            raw: this.handleRaw.bind(this),
            endraw: this.handleEndraw.bind(this),
            comment: this.handleComment.bind(this),
            endcomment: this.handleEndcomment.bind(this)
        };

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
            strip: (value) => {
                return value.trim();
            },
            lstrip: (value) => {
                return value.replace(/^\s+/, '');
            },
            rstrip: (value) => {
                return value.replace(/\s+$/, '');
            },
            nl2br: (value) => {
                return value.replace(/\n/g, '<br/>');
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

        let i = 0;
        let stack = [];
        let output = '';

        while (i < tokens.length) {
            let token = tokens[i];

            // ─── Raw / comment fast path ─────────────────────────────
            if (this.isRaw(stack) || this.isComment(stack)) {
                // ... handle raw/comment output ...
                i++;

                continue;
            }

            // Handle Tag
            if (token.type in this.tag) {
                let handler = this.tag[token.type].bind(this);

                if (handler) {
                    let jump = handler(token, stack, ctx, i, output);

                    if (typeof jump === 'number' && jump >= 0) {
                        i = jump;

                        continue;
                    }
                }
            }

            i++;
        }

        return output;
    }

    tokenize(template) {
        let token = [];
        let index = 0;
        let match;

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
                token.push({
                    type: tag.match(/^\S+/)[0],
                    value: tag
                });
            }

            // Records the last match
            index = regex.lastIndex;
        }

        // Grab any remaining template code since the last tag.
        // If no tags in the template it will grab the whle template.
        if (index < template.length) {
            token.push({
                type: 'text',
                value: template.slice(index)
            });
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

            let func = new Function('data', `with(data) { return (${safe}); }`);

            return func(ctx);
        } catch {
            return undefined;
        }
    }

    /**
     * Apply chain of filters — now supports multiple comma-separated arguments per filter
     */
    applyFilters(value, filters = {}) {
        if (!filters.length) return value;

        let result = value;

        for (let filter of filters) {
            let name = filter;
            let data = [];

            let index = filter.indexOf(': ');

            if (index !== -1) {
                name = filter.substr(0, index);

                // Extract the arguments
                data = filter.substr(index + 2).split(', ').map(value => value.trim().replace(/^["']?(.*?)["']?$/, '$1'));
            }

            let func = this.filter[name];

            if (!func) continue;

            result = func(result, ...data);
        }

        return result;
    }

    handleText(token, stack, ctx, index, output) {
        // Check if the stack is active
        let current = stack[stack.length - 1];

        if (current !== undefined && !current.active) {
            return index + 1;
        }

        output += token.value;
    }

    handleOutput(token, stack, ctx, index, output) {
        // Check if the stack is active
        let current = stack[stack.length - 1];

        if (current !== undefined && !current.active) {
            return index + 1;
        }

        let match = token.value.match(/^([^\s]+)\s?\|?\s?(.*)?$/i);

        if (!match) {
            console.log('[Template] Invalid output: ' + token.value);
        }

        let [full, name, filter] = match;

        let value = this.evaluate(name, ctx);

        // Apply Filters
        if (filter !== undefined) {
            value = this.applyFilters(value, filter.indexOf(' | ') !== -1 ? filter.split(' | ') : [filter]);
        }

        output += this.filter.escape(value ?? '');

        return index + 1;
    }

    // ─── Tag handlers ────────────────────────────────────────────────────────
    // Unified handler map — all handlers get the same 4 arguments

    /**
     * Handle assign statement
     *
     * assign var = expression | filter1 | filter2
     */
    handleAssign(token, stack, ctx, index, output) {
        console.log('[handle] assign: ' + token.value);

        let match = token.value.match(/^assign\s(\w+)\s=\s([^\s?\|]+)?\s?\|?\s?(.*)?$/i);

        if (!match) {
            console.log('[Template] Invalid assign syntax: ' + token.value);

            return;
        }

        let [, name, value, filter] = match;

        value = this.evaluate(value, ctx);

        // Apply Filters
        if (filter !== undefined) {
            value = this.applyFilters(value, filter.indexOf(' | ') !== -1 ? filter.split(' | ') : [filter]);
        }

        // Add key value
        ctx[name] = value;
    }

    /**
     * Handle assign statement
     *
     * assign var = expression | filter1 | filter2
     */
    handleIf(token, stack, ctx, index, output) {
        // If skip we don't want to run evaluate method.
        let match = token.value.match(/^if\s(.+)$/i);

        if (!match) {
            console.log('[Template] Invalid if syntax: ' + token.value);

            return;
        }

        let active = false;

        // Check if the stack is active
        let previous = stack[stack.length - 1];

        if (previous == undefined || previous.active) {
            active = this.evaluate(match[1], ctx);
        }

        stack.push({
            type: 'if',
            active: active
        });
    }

    handleEndif(token, stack, ctx, index, output) {
        // Current stack
        let current = stack[stack.length - 1];

        if (current === undefined || current.type !== 'if') stack.pop();
    }

    handleUnless(token, stack, ctx, index, output) {
        let match = token.value.match(/^unless\s(.+)$/);

        if (!match) {
            console.log('[Template] Invalid unless syntax: ' + token.value);

            return;
        }

        let active = true;

        // Check if the stack is active
        let previous = stack[stack.length - 1];

        if (previous == undefined || previous.active) {
            active = this.evaluate(match[1], ctx);
        }

        stack.push({
            type: 'unless',
            active: !active
        });
    }

    handleEndunless(token, stack, ctx, index, output) {
        // Current stack
        let current = stack[stack.length - 1];

        if (current === undefined || current.type !== 'unless') stack.pop();
    }

    handleElsif(token, stack, ctx, index, output) {
        let match = token.value.match(/^elsif\s(.+)$/);

        if (!match) {
            console.log('[Template] Invalid elsif syntax: ' + token.value);

            return;
        }

        // Previous Stack
        let previous = stack[stack.length - 2];

        if (previous !== undefined && !previous.active) return;

        // Current stack
        let current = stack[stack.length - 1];

        // Check to see if
        if (current == undefined || (current.type !== 'if' && current.type !== 'unless')) return;

        if (!current.active) {
            current.active = this.evaluate(match[1], ctx);
        } else {
            current.active = false;
        }
    }

    handleElse(token, stack, ctx, index, output) {
        // Skip if previous is not active
        let previous = stack[stack.length - 2];

        if (previous !== undefined && !previous.active) return;

        let current = stack[stack.length - 1];

        if (current == undefined || (current.type !== 'if' && current.type !== 'unless')) return;

        current.active = !current.active ? false : true;
    }

    handleFor(token, stack, ctx, index, output) {
        let match = token.value.match(/^for\s(\w*)\sin\s([\w.]+)\s?(.*)$/);

        if (!match) {
            console.log('[Template] Invalid for syntax: ' + token.value);

            return;
        }

        console.log(match);

        let [, name, key, filter] = match;

        let items = [];

        let current = stack[stack.length - 1];

        if (current == undefined || current.active) {
            items = this.evaluate(key, ctx);
        }

        if (typeof items !== 'object') {
            items = [];
        }

        // Parse optional offset argument
        let offset = 0;

        if (match = filter.match(/offset:\s([0-9]+)|([^\s]+)/i)) {
            //offset = this.evaluate(match[1], ctx);

            console.log(match);

            offset = match[1];
        }

        // Parse optional limit argument
        let limit = 0;

        if (match = filter.match(/limit:\s([^\s]+)/i)) {
            limit = match[1];

            console.log(match);
        }

        let reversed = false;

        if (filter.match(/(reversed)/i) !== null) {
            reversed = true;
        }

        // Now safe to slice
        if (offset || limit) {


            console.log('HI');

            console.log(items);

            items = items.slice(offset, offset + limit);
            console.log(items);

        }

        console.log('name ' + name);
        console.log('limit ' + limit);
        console.log('offset ' + offset);
        console.log('length ' + items.length);



        stack.push({
            type: 'for',
            name: name,
            items: items,
            index: -1,
            length: items.length,
            start: index + 1,
            active: false
        });

        console.log('[handle] For: ' + name + ' in ' + key + ' ' + this.skip);
    }

    handleEndfor(token, stack, ctx, index) {
        console.log('[handle] Endfor');

        // Previous Stack
        let previous = stack[stack.length - 2];

        if (previous !== undefined && !previous.active) {
            // Loop finished → cleanup
            stack.pop();

            return;
        }

        // If skip we don't want to run evaluate method.
        let current = stack[stack.length - 1];

        if (current == undefined || current.type !== 'for') return;

        current.index++;

        if (current.index < current.items.length) {
            console.log('looping');

            ctx.loop = {
                index: current.index + 1,
                index0: current.index,
                first: current.index === 0,
                last: current.index === current.items.length - 1,
                length: current.items.length
            };

            current.active = true;

            // Restore parent context (prevents leakage)
            ctx[current.name] = current.items[current.index];  // ← top.name (not top.name)

            return current.start;
        }

        // Loop finished → cleanup
        stack.pop();
    }

    handleContinue(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (top?.type !== 'for') return;

        // Signal to skip the rest of this iteration
        top._skipCurrent = true;
    }

    handleBreak(token, stack, ctx, index) {
        const top = stack[stack.length - 1];

        if (top?.type !== 'for') return;

        // Signal to exit the entire loop
        top._breakLoop = true;
    }

    handleCase(token, stack, ctx, index) {
        let match = token.value.match(/^case\s(\w+)$/);

        if (!match) {
            console.log('[Template] Invalid case syntax: ' + token.value);

            return;
        }

        stack.push({
            type: 'case',
            value: this.evaluate(match[1], ctx),
            matched: false
        });
    }

    handleWhen(token, stack, ctx, index) {
        let match = token.value.match(/^when\s(.+)$/);

        if (!match) {
            console.log('[Template] Invalid when syntax: ' + token.value);

            return;
        }

        let top = stack[stack.length - 1];

        if (!top || top.type !== 'case' || top.matched) return;

        // Apply Filters
        let args = match[1].indexOf(', ') !== -1 ? filter.split(', ') : [filter];

        let values = args.map(value => value.replace(/^["']?(.*)["']?$/, '$1'));

        top.matched = values.some(value => top.value == value);
    }

    handleEndcase(token, stack, ctx, index) {
        if (stack[stack.length - 1]?.type === 'case') stack.pop();
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
{% assign my_var = "Hello" %}
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
{% for user in users offset: 1 limit: 1 %}
  Name: {{ user.name }}
  Favorite colors:
  {% for color in user.colors %}
    • {{ color }} {{ forloop.first ? '(primary)' : '' }}
  {% endfor %}
  ---
{% endfor %}
`);


let number = 14;

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