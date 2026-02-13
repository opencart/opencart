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
        let ctx = { ...data };
        let tokens = this.tokenize(template);

        console.log(ctx);
        console.log(tokens);

        let stack = [];
        let output = '';
        let i = 0;

        while (i < tokens.length) {
            let token = tokens[i];
            let top = stack[stack.length - 1];

            // ─── Normal rendering ─────────────────────────────────────────────
            if (token.type == 'text') {
                const skip = stack.some(s => s.type === 'if' && !s.entered);


                if (!this.isSkip()) {
                    output += token.value;
                }
            }

            // Handle Output
            if (token.type == 'output') {
                let match = token.value.match(/^(\w+)\s?\|?\s?(.*)?$/i);

                if (!match) {
                    console.log('[Template] Invalid output: ' + token.value);
                }

                let [, name, filter] = match;

                let value = this.evaluate(name, ctx);

                // Apply Filters
                if (filter !== undefined) {
                    value = this.applyFilters(value, filter.indexOf(' | ') !== -1 ? filter.split(' | ') : [filter]);
                }

                output += this.filter.escape(value ?? '');
            }

            // Handle Tag
            if (token.type in this.tag) {
                let handler = this.tag[token.type].bind(this);

                if (handler) {
                    let jump = handler(token, stack, ctx, i);

                    //console.log('jump');
                    //console.log(jump);

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
            console.log(match);

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

    isSkip(stack) {
        for (let i = stack.length - 1; i >= 0; i--) {
            const frame = stack[i];

            if (frame.type === 'if' || frame.type === 'unless') {
                return !frame.entered;
            }

            if (frame.type === 'case') {
                return !frame.matched;
            }
        }

        return false;
    }

    // ─── Tag handlers ────────────────────────────────────────────────────────

    /**
     * Process assign statements
     *
     * assign var = expression | filter1 | filter2
     */
    handleAssign(token, stack, ctx, index) {
        let match = token.value.match(/^assign\s(\w+)\s=\s["']?([^"']+)["']?\s?\|?\s?(.*)?$/i);

        if (!match) {
            console.log('[Template] Invalid assign syntax: ' + token.value);

            return;
        }

        let [, name, value, filter] = match;

        // Apply Filters
        if (filter !== undefined) {
            value = this.applyFilters(value, filter.indexOf(' | ') !== -1 ? filter.split(' | ') : [filter]);
        }

        ctx[name] = value;
    }

    // Unified handler map — all handlers get the same 4 arguments
    handleIf(token, stack, ctx, index) {
        console.log('handleIf');
        console.log(token);

        let match = token.value.match(/^if\s(.+)$/);

        if (!match) {
            console.log('[Template] Invalid if syntax: ' + token.value);

            return;
        }

        let evaluate =!!this.evaluate(match[1], ctx);

        let test = !!this.evaluate(match[1], ctx);

        console.log(match);
        console.log(ctx);
        console.log(evaluate);

        stack.push({
            type: 'if',
            entered: test
        });
    }

    handleEndif(token, stack, ctx, index) {
        if (stack.length && stack[stack.length - 1].type === 'if') stack.pop();
    }

    handleElsif(token, stack, ctx, index) {
        let match = token.value.match(/^elsif\s(.+)$/);

        if (!match) {
            console.log('[Template] Invalid elsif syntax: ' + token.value);

            return;
        }

        let top = stack[stack.length - 1];

        if (!top || (top.type !== 'if' && top.type !== 'unless' && top.type !== 'when') || top.entered) return;

        top.entered = !!this.evaluate(match[1], ctx);
    }

    handleElse(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || (top.type !== 'if' && top.type !== 'unless') || top.entered) return;

        top.entered = true;
    }

    handleUnless(token, stack, ctx, index) {
        let match = token.value.match(/^unless\s(.+)$/);

        if (!match) {
            console.warn('[Template] Invalid unless syntax: ' + token.value);

            return;
        }

        stack.push({
            type: 'unless',
            entered: !this.evaluate(match[1], ctx)
        });
    }

    handleEndunless(token, stack, ctx, index) {
        if (stack[stack.length - 1]?.type === 'unless') stack.pop();
    }

    handleFor(token, stack, ctx, index) {
        console.log(stack);

        let match = token.value.match(/^for\s(\w*)\sin\s([^\s]+)\s?(.*)$/);

        if (!match) {
            console.log('[Template] Invalid for syntax: ' + token.value);

            return;
        }

        let [, name, key, filter] = match;

        // Filters
        // Parse optional parameters limit & offset
        let [, limit] = filter.match(/limit\s:\s([^\s]+)/i);

        if (limit == undefined) {
            limit = Infinity;
        }

        let [, offset] = filter.match(/offset\s:\s([^\s]+)/i);

        if (offset == undefined) {
            offset = 0;
        }

        let [, reversed] = filter.match(/(reversed)/i);

        if (reversed == undefined) {
            reversed = false;
        }

        // Remove limit/offset clauses (very naive but usually enough)
        // Evaluate the collection
        const items = this.evaluate(key, ctx) || [];

        // Now safe to slice
        const start = Math.max(0, offset);
        const end = limit === Infinity ? undefined : start + limit;
        const sliced = collection.slice(start, end);

        stack.push({
            type: 'for',
            name: name,
            items: Array.isArray(items) ? items : [],
            length: items.length,
            index: -1,
            start: index + 1,
            parent: { ...ctx }
        });
    }

    handleEndfor(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        //console.log('handleEndfor');
        //console.log(token);

        if (top?.type !== 'for') return;

        top.index++;

        if (top.index < top.ctx.length) {
            ctx[top.name] = top.ctx[top.index];  // ← top.name (not top.name)

            return top.start - 1;
        } else {
            stack.pop();

            delete ctx[top.name];

            return;
        }
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

    handleCase(token, stack, ctx, index) {
        let match = token.value.match(/^case\s(\w+)$/);

        if (!match) {
            console.log('[Template] Invalid case syntax: ' + token.value);

            return;
        }

        stack.push({
            type: 'case',
            value: this.evaluate(match[0], ctx),
            matched: false
        });
    }

    handleWhen(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'case' || top.matched) return;

        let match = token.value.match(/^when\s(.+)$/);

        if (!match) {
            console.log('[Template] Invalid when syntax: ' + token.value);

            return;
        }

        // Apply Filters
        let args = match[1].indexOf(', ') !== -1 ? filter.split(', ') : [filter];

        let values = args.map(value => value.replace(/^["']?(.*)["']?$/, '$1'));

        top.matched = values.some(value => top.value == value);
    }

    handleEndcase(token, stack, ctx, index) {
        if (stack[stack.length - 1]?.type === 'case') stack.pop();
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

test.push(`--- assign string ---
{% assign my_var = "Hello" %}
{{ my_var }}, world!
`);

test.push(`--- assign number ---
{% assign my_var = 121213 %}
{{ my_var }}, world!
`);

test.push(`--- assign decimal ---
{% assign my_var = 1212.13 %}
{{ my_var }}, world!
`);

test.push(`--- assign replace ---
{% assign my_var = "Hello world" | replace: 'e', 'A' %}
{{ my_var }}, world!
`);

test.push(`--- ucase ---
{% assign my_var = "Hello world" %}
{{ my_var | upcase }}
`);

test.push(`--- replace --- 
{% assign my_var = "Hello world" %}
{{ my_var | replace: "e", "Q" }}, world!
`);

test.push(`--- assign test ---
{% assign year = 2025 %}
Current year: {{ year }}
`);

test.push(`{% assign my_var = "Hello, World" | downcase | upcase | replace: "O", "X" %}{{ my_var }}`);


test.push(`
{% assign my_var = "Hello, World" %}
{% if my_var == "Awesome Shoes" %}
  These shoes are awesome!
{% endif %}
`);

test.push(`
{% for user in users %}
  Name: {{ user.name }}
  Favorite colors:
  {% for color in user.colors %}
    • {{ color }} {{ forloop.first ? '(primary)' : '' }}
  {% endfor %}
  ---
{% endfor %}
`);

test.push(`
{% for user in users limit: 1 offset: 2 %}
  Name: {{ user.name }}
  Favorite colors:
  {% for color in user.colors %}
    • {{ color }} {{ forloop.first ? '(primary)' : '' }}
  {% endfor %}
  ---
{% endfor %}
`);

let number = 9;

await test.splice(number, 1).map(async value => {
    console.log('TEMPLATE');
    console.log(value);

    // Loop
    const data = {
        users: [
            { name: "Alice", colors: ["red", "blue", "green"] },
            { name: "Bob",   colors: ["yellow"] },
            { name: "Carol", colors: ["purple", "pink"] }
        ]
    };

    let output = await template.parse(value, data);

    console.log('OUTPUT');
    console.log(output);
});