/* OpenCart Twig replacement. Based on Django template syntax. */
class Template {
    static instance = null;

    constructor() {
        this.directory = '';
        this.path = new Map();
        this.cache = new Map();
        this.skip = false;

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

        let stack = [];
        let output = '';
        let i = 0;

        while (i < tokens.length) {
            let token = tokens[i];
            let top = stack[stack.length - 1];

            //console.log('[process]');
            //console.log('token');
            //console.log(token);
            //console.log(top ? top : 'top empty');

            // Handle Tag
            if (token.type in this.tag) {
                let handler = this.tag[token.type].bind(this);

                if (handler) {
                    let jump = handler(token, stack, ctx, i);

                    if (typeof jump === 'number' && jump >= 0) {
                        i = jump;

                        continue;
                    }
                }
            }

            if (top && !top.active) {
                console.log(token);
                console.log('skip');
                console.log(top);

                i++;

                continue;
            }

            // Handle Output
            if (token.type == 'output' && !this.skip) {
                let match = token.value.match(/^([^\s]+)\s?\|?\s?(.*)?$/i);

                if (!match) {
                    console.log('[Template] Invalid output: ' + token.value);
                }

                let [full, name, filter] = match;

                let value = this.evaluate(name, ctx);


                console.log('[handle] output: ' + name);
                console.log(value);



                // Apply Filters
                if (filter !== undefined) {
                    value = this.applyFilters(value, filter.indexOf(' | ') !== -1 ? filter.split(' | ') : [filter]);
                }

                output += this.filter.escape(value ?? '');

                i++;

                continue;
            }

            // ─── Normal rendering ─────────────────────────────────────────────
            if (token.type == 'text' && !this.skip) {
                console.log('[handle] text: ' + token.value);

                output += token.value;

                i++;

                continue;
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
                //console.log(index + ' ' + tag.match(/^\S+/)[0]);
                //console.log(match);

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

    // ─── Tag handlers ────────────────────────────────────────────────────────
    // Unified handler map — all handlers get the same 4 arguments

    /**
     * Handle assign statement
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

        console.log('[handle] assign: ' + token.value);
    }
    /**
     * Handle assign statement
     *
     * assign var = expression | filter1 | filter2
     */
    handleIf(token, stack, ctx, index) {
        // If skip we don't want to run evaluate method.
        if (this.skip) {
            stack.push({
                type: 'if',
                active: !this.skip
            });

            return;
        }

        let match = token.value.match(/^if\s(.+)$/i);

        if (!match) {
            console.log('[Template] Invalid if syntax: ' + token.value);

            return;
        }

        let active = this.evaluate(match[1], ctx);

        stack.push({
            type: 'if',
            active: active
        });

        // If the result is false skip
        this.skip = !active;

        console.log('[handle] If: ' + match[1] + ' ' + active);
    }

    handleEndif(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (top && top.type === 'if') stack.pop();

        console.log('[handle] Endif');
    }

    handleElsif(token, stack, ctx, index) {
        if (this.skip) return;

        let top = stack[stack.length - 1];

        if (!top || (top.type !== 'if' && top.type !== 'unless')) return;

        let match = token.value.match(/^elsif\s(.+)$/);

        if (!match) {
            console.log('[Template] Invalid elsif syntax: ' + token.value);

            return;
        }

        if (!top.active) {
            top.active = this.evaluate(match[1], ctx);
        }

        console.log('[handle] Elsif: ' + match[1] + ' ' + top.active);
    }

    handleElse(token, stack, ctx, index) {
        if (this.skip) return;

        let top = stack[stack.length - 1];

        if (!top || (top.type !== 'if' && top.type !== 'unless')) return;

        top.active = top.active ? false : true;

        console.log('[handle] Else: ' + top.active);
    }

    handleUnless(token, stack, ctx, index) {
        // If skip we don't want to run evaluate method.
        if (this.skip) {
            stack.push({
                type: 'unless',
                active: !this.skip
            });

            return;
        }

        let match = token.value.match(/^unless\s(.+)$/);

        if (!match) {
            console.log('[Template] Invalid unless syntax: ' + token.value);

            return;
        }

        let active = this.evaluate(match[1], ctx);

        stack.push({
            type: 'unless',
            active: !active
        });

        console.log('[handle] Unless: ' + match[1] + ' ' + active);
    }

    handleEndunless(token, stack, ctx, index) {
        if (stack[stack.length - 1]?.type === 'unless') stack.pop();
    }

    handleFor(token, stack, ctx, index) {
        // If skip we don't want to run evaluate method.
        if (this.skip) {
            stack.push({
                type: 'for',
                items: [],
                active: !this.skip
            });

            return;
        }

        let match = token.value.match(/^for\s(\w*)\sin\s([\w.]+)\s?(.*)$/);

        if (!match) {
            console.log('[Template] Invalid for syntax: ' + token.value);

            return;
        }

        let [, name, key, filter] = match;

        // Parse optional offset argument
        let offset = 0;

        match = filter.match(/offset:\s([^\s]+)/i);

        if (match !== null) {
            offset = match[1];
        }

        // Parse optional limit argument
        let limit = Infinity;

        match = filter.match(/limit:\s([^\s]+)/i);

        if (match !== null) {
            limit = match[1];
        }

        let reversed = false;

        match = filter.match(/(reversed)/i);

        if (match !== null) {
            reversed = true;
        }

        // Key from the data
        let items = this.evaluate(key, ctx);

        items = typeof items == 'object' ? items : [];

        //ctx = Object.assign({}, ctx, { user: items[0] });

        // Now safe to slice
        //const start = Math.max(0, offset);
        //const end = limit === Infinity ? items.length : start + limit;
        //console.log('handleFor');
        //console.log('name ' + name);
        //console.log('key ' + key);
        //console.log(items);
        //console.log(items.length);

        stack.push({
            type: 'for',
            name: name,
            //items: Array.isArray(items) ? items.slice(start, end) : [],
            items: items,
            index: -1,
            start: index + 1,
            //parent: { ...ctx },
            active: false
        });

        console.log('[handle] For: ' + name + ' in ' + key);
    }

    handleEndfor(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (top?.type !== 'for') return;

        top.index++;

        console.log('[handle] Endfor: ' + top.index);

        if (top.index < top.items.length) {
            console.log('looping');

            top.active = true;

            //ctx =  Object.assign({}, ctx, { user: top.items[top.index] });

            //console.log(ctx);

            // Restore parent context (prevents leakage)
            //Object.assign(ctx, top.parent);

            ctx[top.name] = top.items[top.index];  // ← top.name (not top.name)

            console.log(top.name);
            console.log(ctx);

            return top.start;
        }

        // Loop finished → cleanup
       // Object.assign(ctx, top.parent);

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
test.push(`{% assign my_var = "green" %}{% if my_var == "red" %}red!{% elsif my_var == "green" %}green{% if my_var != "blue" %}green{% endif %}{% else %}blue{% endif %}`);'' +

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
{% for user in users limit: 1 offset: 2 %}
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