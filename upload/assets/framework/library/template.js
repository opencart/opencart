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

        let stack = [];
        let output = '';
        let i = 0;

        while (i < tokens.length) {
            let token = tokens[i];
            let top = stack[stack.length - 1];

            //console.log(token);

            // ─── Normal rendering ─────────────────────────────────────────────
            if (token.type === 'text') {
                //if (isCapturing) {
                //    top.content += token.value;
                //} else if (!this.isSkip(stack)) {
                    output += token.value;
                //}

                i++;

                continue;
            }

            if (token.type === 'output') {
                let match = token.value.match(/^(\w+)\s?\|?\s?(.*)?$/i);

                if (!match) {
                    console.log('Template {{ variable }} expression `' + token.value + '` malformed.');

                    return;
                }

                let [, name, filter] = match;

                if (!name in ctx) {
                    console.log('Error: Could not find template key ' + name + ' in data!');
                }

                let value = this.evaluate(name, ctx);

                // Apply Filters
                if (filter !== undefined) {
                    value = this.applyFilters(value, filter.indexOf(' | ') !== -1 ? filter.split(' | ') : [filter]);
                }

                //if (isCapturing) {
                //    top.content += rendered;
                //} else {
                    output += this.filter.escape(value ?? '');
                //}

                i++;

                continue;
            }

            if (token.type === 'tag') {
                let tag = token.value.replace(/^([^\s]+).+/, '$1');

                if (!tag || !tag in this.tag) {
                    console.log('Template tag ' + tag + ' could not be found!');

                    return;
                }

                let handler = this.tag[tag].bind(this);

                if (handler) {
                    let jump = handler(token, stack, ctx, i);

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
            let [, output, tag] = match;

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
                    type: 'tag',
                    value: tag
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
    handleEcho() {

    }

    /**
     * Process assign statements
     *
     * assign var = expression | filter1 | filter2
     */
    handleAssign(token, stack, ctx, index) {
        let match = token.value.match(/^assign\s(\w+)\s=\s["']?([^"']+)["']?\s?\|?\s?(.*)?$/i);

        if (!match) {
            console.log('Template assign expression `' + token.value + '` malformed.');

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
        let match = token.value.match(/^if\s(.+)$/);

        if (!match) return;

        // Check if contains | so the method can handle multiple filters.
        stack.push({
            type: 'if',
            entered: !!this.evaluate(match[1], ctx)
        });
    }

    handleEndif(token, stack, ctx, index) {
        if (stack[stack.length - 1]?.type === 'if') stack.pop();
    }

    handleElsif(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || (top.type !== 'if' && top.type !== 'unless') || top.entered) return;

        let match = token.value.match(/^elsif\s(.+)$/);

        if (!match) return;

        top.entered = !!this.evaluate(match[1], ctx);
    }

    handleElse(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || (top.type !== 'if' && top.type !== 'unless') || top.entered) return;

        top.entered = true;
    }

    handleUnless(token, stack, ctx, index) {
        let match = token.value.match(/^unless\s(.+)$/);

        if (!match) return;

        stack.push({
            type: 'unless',
            entered: !this.evaluate(match[1], ctx)
        });
    }

    handleEndunless(token, stack, ctx, index) {
        if (stack[stack.length - 1]?.type === 'unless') stack.pop();
    }

    handleFor(token, stack, ctx, index) {
        let match = token.value.match(/^for\s(\w+)\sin\s([^\s]+)(?:\s(reversed))?(?:\slimit:\s(?<limit>.+))?(?:\soffset:\s([^\s]+))?/);

        let [, key, array, reversed, limit, offset] = match;

        console.log(token);
        console.log(stack);

        // 2. Parse optional limit & offset parameters
        if (limit !== undefined) {
            limit = Infinity;
        }

        if (offset !== undefined) {
            offset = 0;
        }

        // Remove limit/offset clauses (very naive but usually enough)
        // Evaluate the collection
        const collection = this.evaluate(expression, ctx) || [];

        // Apply offset & limit safely
        const start = Math.max(0, offset);
        const end = limit === Infinity ? undefined : start + limit;
        const sliced = fullArray.slice(start, end);

        stack.push({
            type: 'for',
            name,
            array: sliced,
            originalArrayLength: fullArray.length,
            index: -1,
            bodyStart: index + 1,
            parentCtx: { ...ctx }
        });
    }

    handleEndfor(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        //console.log('handleEndfor');
        //console.log(token);

        if (top?.type !== 'for') return null;

        top.index++;

        if (top.index < top.array.length) {
            Object.assign(ctx, top.parentCtx);

            ctx[top.name] = top.array[top.index];  // ← top.name (not top.name)

            ctx.forloop = {
                index: top.index + 1,
                index0: top.index,
                first: top.index === 0,
                last: top.index === top.array.length - 1,
                length: top.array.length,              // ← length of the *sliced* array
                rindex: top.array.length - top.index,
                rindex0: top.array.length - top.index - 1,
                // Optional: if you want original total length too
                original_length: top.originalArrayLength || top.array.length
            };

            return top.bodyStart;
        }

        Object.assign(ctx, top.parentCtx);

        stack.pop();

        delete ctx[top.name];
        delete ctx.forloop;

        return null;
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
        let words = token.value.trim().split(/\s+/);

        if (words.length < 2) return;

        stack.push({
            type: 'capture',
            name: words[1],
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

        if (!match) return;

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

        if (!match) return;

        let values = code.split(', ').map(value => value.replace(/^["'](.+)["']$/, '$1'));

        console.log(values);

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


//test.push(`{% assign my_var = "Hello, World"%} {{ my_var | downcase | upcase | replace: "O", "X" }}`);


let number = 3;

await test.splice(number, 1).map(async value => {
    console.log('TEMPLATE');
    console.log(value);

    let output = await template.parse(value, {});

    console.log('OUTPUT');
    console.log(output);
});

// Loop
let html_2 = `
{% for user in users %}
  Name: {{ user.name }}
  Favorite colors:
  {% for color in user.colors %}
    • {{ color }} {{ forloop.first ? '(primary)' : '' }}
  {% endfor %}
  ---
{% endfor %}
`;

const data = {
    users: [
        { name: "Alice", colors: ["red", "blue", "green"] },
        { name: "Bob",   colors: ["yellow"] },
        { name: "Carol", colors: ["purple", "pink"] }
    ]
};

//let output_2 = template.parse(html_2, data);

//console.log(output_2);



