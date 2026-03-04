/* OpenCart Twig replacement. Based on Django, Nunjucks template syntax. */
class Template {
    static instance = null;

    constructor() {
        this.directory = '';
        this.path = new Map();
        this.cache = new Map();

        this.handler = {
            set: this.handleSet.bind(this),
            if: this.handleIf.bind(this),
            endif: this.handleEndif.bind(this),
            elif: this.handleElif.bind(this),
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
            block: this.handleBlock.bind(this),
            endblock: this.handleEndblock.bind(this),
            include: this.handleInclude.bind(this),
            filter: this.handleFilter.bind(this),
            endfilter: this.handleEndfilter.bind(this),
            raw: this.handleRaw.bind(this),
            endraw: this.handleEndraw.bind(this),
            comment: this.handleComment.bind(this),
            endcomment: this.handleEndcomment.bind(this)
        };

        this.openclose = {
            if: [
                'elif',
                'else',
                'endif',
            ],
            elif: [
                'elif',
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
            ],
            block: [
                'endblock'
            ],
            filter: [
                'endfilter'
            ],
            raw: [
                'endraw'
            ],
            comment: [
                'endcomment'
            ]
        };

        this.global = {
            range: (start, stop, step = 1) => Array.from({ length: (stop - start) / step + 1 }, (value, index) => start + index * step),
            cycler: (item) => {

            },
            joiner: (separator) => {

            }
        }

        this.filter = {
            abs: (value) => {
                return Math.abs(value);
            },
            append: (value, suffix) => {
                return value + suffix;
            },
            batch: (value, offset, limit, step) => {

            },
            capitalize: (value) => {

            },
            ceil: (value) => {
                return Math.ceil(value);
            },
            default: (value, test, bool) => {
                return value != undefined ? value : test;
            },
            divide: (value, amount) => {
                return value / amount;
            },
            e: (value) => {
                return this.filter.escape(value);
            },
            escape: (value) => {
                return String(value ?? '').replace(/[&<>"']/g, m => ({
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;'
                }[m] || m));
            },
            first: () => {

            },
            float: () => {

            },
            floor: (value) => {
                return Math.floor(value);
            },
            groupby: () => {

            },
            indent: () => {

            },
            int: (value) => {
                return Number(value);
            },
            join: (value, seperator = ' ') => {
                return value.join(seperator);
            },
            last: () => {

            },
            length: (value) => {
                return typeof value === 'array' || typeof value === 'string' ? value.length : 0;
            },

            lower: (value) => {
                return value.toLowerCase();
            },
            list: () => {

            },
            ltrim: (value) => {
                return value.trimStart();
            },
            minus: (value, amount) => {
                return value - amount;
            },
            modulo: (value, amount) => {
                return value % amount;
            },
            nl2br: (value) => {

            },
            plus: (value, amount) => {
                return value + amount;
            },
            prepend: (value, prefix) => {
                return prefix + value;
            },
            random: (value) => {

            },
            reject: (value) => {

            },
            rejectattr: (value) => {

            },
            replace: (value, search, replace = '') => {
                return value.replaceAll(search, replace);
            },
            reverse: (value) => {

            },
            round: (value, decimal = 0) => {
                return Number(value).toFixed(decimal);
            },
            safe: (value) => {

            },
            select: (value) => {

            },
            slice: (value, start, length) => {
                return length !== undefined ? value.slice(start, start + length) : value.slice(start);
            },
            sort: (value, max) => {

            },
            striptags: (value, max) => {

            },
            sum: (value, amount) => {
                return value + amount;
            },
            times: (value, amount) => {
                return value * amount;
            },
            title: (value) => {

            },
            trim: (value) => {
                return value.trim();
                //return value.replace(/^\s+/, '');
                //return value.replace(/\s+$/, '');
            },
            truncate: (value, size) => {

            },
            upper: (value) => {
                return value.toUpperCase();
            },
            urlencode: (value) => {
                return value.toUpperCase();
            },
            wordcount: (value) => {
                return value.toUpperCase();
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
            console.log(`[Template] Could not load template file ${path}!`);
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

        let index  = 0;
        let stack = [];
        let output = '';

        while (index < tokens.length) {
            let token = tokens[index];
            let top = stack[stack.length - 1];

            let code = '';

            if (top?.type == 'raw' && token.type !== 'endraw') {
                code += token.raw;

                index++;

                continue;
            }

            if (top?.type == 'include') {
                code = token.output;

                stack.pop();
            }
            
            if (token.type == 'text') {
                code = this.handleText(token, stack, ctx, index);
            }

            if (token.type == 'output') {
                code = this.handleOutput(token, stack, ctx, index);
            }

            if (top?.type == 'capture') {
                top.value += code;
            } else {
                output += code;
            }

            // Handle Tags
            if (token.type == 'tag') {
                let handle = this.handler[token.tag];

                if (handle !== undefined) {
                    // Unified handler map — all handlers get the same 4 arguments
                    let jump = handle(token, stack, ctx, index);

                    if (typeof jump === 'number' && jump >= 0) {
                        index = jump;

                        continue;
                    }
                }
            }

            index++;
        }

        return output;
    }

    tokenize(template) {
        let token = [];
        let index = 0;
        let match;

        let stack = [];

        let regex = /\{\{-?\s([\s\S]*?)\s-?\}\}|\{%-?\s([\s\S]*?)\s-?%}|\{\#\s([\s\S]*?)\s\#\}/g;

        while ((match = regex.exec(template)) !== null) {
            let [raw, output, tag] = match;

            let line = template.substring(0, match.index).split(/\r\n|\r|\n/).length;

            // Grabs all the Text before the matched index.
            if (match.index > index) {
                token.push({
                    type: 'text',
                    raw: template.slice(index, match.index)
                });
            }

            // Capture output tag {{ my_var }}
            if (output !== undefined) {
                token.push({
                    type: 'output',
                    value: output,
                    raw: raw,
                    line: line,
                    column: index
                });
            }

            // Capture statement tag {% if my_var %}
            if (tag !== undefined) {
                let command = tag.match(/^\S+/)[0].toLowerCase();

                // Handle Close Tags
                let top = stack[stack.length - 1];

                if (top && this.openclose[top.type].includes(command)) {
                    token[top.index].end = token.length;

                    stack.pop();
                }

                // Handle Open Tags
                if (command in this.openclose) {
                    // Remember this opening tag's token index
                    stack.push({
                        type: command,
                        index: token.length // current length = index before push
                    });
                }

                token.push({
                    type: 'tag',
                    tag: command,
                    value: tag,
                    raw: raw,
                    line: line,
                    column: index
                });
            }

            // Records the last match
            index = regex.lastIndex;
        }

        // Grab any remaining template code since the last tag.
        if (index < template.length) {
            token.push({
                type: 'text',
                raw: template.slice(index)
            });
        }

        // Warning for unclosed blocks
        if (stack.length) {
            console.log(`[Template] Warning: ${stack.length} unclosed block(s): ${stack.map(value => value.type)}`);
        }

        return token;
    }

    evaluate(expression, ctx) {
        if (!expression) return undefined;

        console.log('evaluate');
        console.log(expression);

        try {
            // Replace .property with ["property"]
            const safe = expression;

            let func = new Function('{ ' + Object.keys(ctx).join(', ') + ' }', `return (${safe});`);

            return func({ ...ctx });
        } catch (error)  {
            console.log('error');
            console.log(error);

            return undefined;
        }
    }

    handleText(token, stack, ctx, index) {
        return token.raw;
    }

    handleOutput(token, stack, ctx, index) {
        let match = token.value.match(/([^\|]+?)\s*(?:\s*\|\s*(.+))?$/);

        if (!match) {
            console.log(`[Template] Invalid output line ${token.line} column ${token.column}`);
        }

        let [, name, filter] = match;

        let value = this.evaluate(name, ctx);

        // Apply Filters
        if (filter !== undefined) {
           value = this.parseFilter(value, filter.indexOf(' | ') !== -1 ? filter.split(' | ') : [filter], ctx);
        }

        value = this.filter.escape(value ?? '');

        // Trim whitespace
        if (token.raw[3] == '-') {
            //value = value.trimStart();
        }

        if (token.raw[-3] == '-') {
            //value = value.trimEnd();
        }

        return value;
    }

    /**
     * Handle set statement
     *
     * set var = expression | filter1 | filter2
     */
    handleSet(token, stack, ctx, index) {
        let match = token.value.match(/^set\s(\w+)\s=\s([^\|]+?)\s*(?:\s*\|\s*(.+))?$/);

        if (!match) {
            console.log(`[Template] Invalid 'set' syntax line ${token.line} column ${token.column}`);

            return;
        }

        let [, name, expression, filter] = match;

        let value = this.evaluate(expression, ctx);

        // Apply Filters
        if (filter !== undefined) {
            value = this.parseFilter(value, filter.indexOf(' | ') !== -1 ? filter.split(' | ') : [filter], ctx);
        }

        // Add key value
        ctx[name] = value;
    }

    handleInclude(token, stack, ctx) {
        let match = token.value.match(/^include\s(.+)$/);

        if (!match) {
            console.warn(`[Template] Invalid 'capture' syntax line ${token.line} column ${token.column}`);

            return;
        }

        stack.push({
            type: 'include',
            output: this.render(match[1], ctx)
        });

    }

    handleCode(token, stack, ctx, index) {

    }

    /**
     * Handle If statement
     *
     * If var = expression | filter1 | filter2
     */
    handleIf(token, stack, ctx, index) {
        let match = token.value.match(/^if\s(.+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'if' syntax line ${token.line} column ${token.column}`);

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

    /**
     * Handle If statement
     *
     * End If var == expression
     */
    handleEndif(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'if') {
            console.log(`[Template] Unexpected 'if' tag line ${token.line} column ${token.column}`);

            return;
        }

        stack.pop();
    }

    /**
     * Handle Unless statement
     *
     * End If var = expression | filter1 | filter2
     */
    handleUnless(token, stack, ctx, index) {
        let match = token.value.match(/^unless\s(.+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'unless' syntax line ${token.line} column ${token.column}`);

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

        if (!top || top.type !== 'unless') {
            console.log(`[Template] Unexpected 'endunless' tag line ${token.line} column ${token.column}`);

            return;
        }

        stack.pop();
    }

    handleElif(token, stack, ctx, index) {
        let match = token.value.match(/^elif\s(.+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'elif' syntax line ${token.line} column ${token.column}`);

            return;
        }

        let top = stack[stack.length - 1];

        if (!top || top.type !== 'if') {
            console.log(`[Template] Unexpected 'elif' tag line ${token.line} column ${token.column}`);

            return;
        }

        if (top.active) return token.end;

        // If any previous not active tags then set current tag to false;
        top.active = this.evaluate(match[1], ctx);
    }

    handleElse(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || (top.type !== 'if' && top.type !== 'unless' && top.type !== 'case')) {
            console.log(`[Template] Unexpected 'else' tag line ${token.line} column ${token.column}`);

            return;
        }

        // If any previous not active tags then set current tag to false;
        if (top.active) return token.end;

        top.active = true;
    }

    /**
     * Apply chain of filters — now supports multiple comma-separated arguments per filter
     */
    parseFilter(value, filters = {}, ctx) {
        if (!filters.length) return value;

        let result = value;

        for (let filter of filters) {
            let match = filter.match(/^([^\(\)]+?)(?:\((.+)\))?$/);

            if (!match) return;

            let [, name, argument] = match;

            let args = [];

            if (argument) {
                args = this.parseArg(argument, ctx);
            }

            let func = this.filter[name];

            if (!func) {
                console.log(`[Template] Unknown filter ${name}!`);

                return;
            }

            result = func(result, ...args);
        }

        return result;
    }

    parseFunction(expression, ctx) {
        let match = expression.match(/^([^\(]+?)(?:\((.+)\))$/);

        if (!match) return;

        let [, name, argument] = match;

        let args = [];

        if (argument) {
            args = this.parseArg(argument, ctx);
        }

        let func = this.global[name];

        if (!func) {
            console.log(`[Template] Unknown function ${name}!`);

            return;
        }

        let result = func(...args);

        return result;
    }

    parseArg(argument, ctx) {
        let args = [];

        // Extract the arguments
        let values = argument.indexOf(', ') !== -1 ? argument.split(', ').map(value => value.trim(argument)) : [];

        for (let value of values) {
            // Match String
            let string = value.match(/^["'](.*)["']$/i);

            if (string) {
                args.push(string[1]);

                continue;
            }

            // Match Boolean
            let boolean = value.match(/^(true|false)$/i);

            if (boolean) {
                args.push(boolean[1] == 'true' ? true : false);

                continue;
            }

            // Match Digit
            let number = value.match(/^(-?\d+)$/i);

            if (number) {
                args.push(Number(number[1]));

                continue;
            }

            // Match Float
            let float = value.match(/^(-?\d+.\d+)$/i);

            if (float) {
                args.push(parseFloat(float[1]));

                continue;
            }

            // If match variable
            let output = this.evaluate(value, ctx);

            args.push(output);
        }

        return args;
    }

    handleFor(token, stack, ctx, index) {
        let match = token.value.match(/^for\s(.*)\sin\s([^\|]+?)\s*(?:\s*\|\s*(.+))?$/);

        if (!match) {
            console.log(`[Template] Invalid 'for' syntax line ${token.line} column ${token.column}`);

            return;
        }

        let [, name, key, filter] = match;

        let items = [];

        // Match any global function
        let output = this.parseFunction(key);

        if (output !== undefined) {
            items = output;
        } else {
            items = this.evaluate(key, ctx);
        }

        if (typeof items !== 'object') {
            items = [];
        }

        // Apply Filters
        if (filter !== undefined) {
            items = this.parseFilter(items, filter.indexOf(' | ') !== -1 ? filter.split(' | ') : [filter], ctx);
        }

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

        if (top == undefined || top.type !== 'for') {
            console.log(`[Template] Unexpected 'endfor' line ${token.line} column ${token.column}`);

            return;
        }

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

        // Loop finished → cleanup
        stack.pop();

        delete ctx[top.name];
        delete ctx.loop;
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

    // fix!!@!
    handleBreak(token, stack, ctx, index) {
        let top = {};

        for (let i = stack.length -1; i >= 0; i--) {
            let top = stack[i];

            if (top.type == 'for') {
                top = stack[i];

                break;
            }

            // Remove all tags before endfor loop.
            stack.pop();
        }

        // Loop finished → cleanup
        stack.pop();

        stack[i].end + 1;

        if (next) return next;
    }

    handleCase(token, stack, ctx, index) {
        let match = token.value.match(/^case\s(\w+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'case' syntax line ${token.line} column ${token.column}`);

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
            console.log(`[Template] Invalid 'when' syntax line ${token.line} column ${token.column}`);

            return;
        }

        let top = stack[stack.length - 1];

        if (!top || top.type !== 'case') {
            console.log(`[Template] Unexpected 'when' tag line ${token.line} column ${token.column}`);

            return;
        }

        // Split if more than one item to compare
        if (!this.evaluate(`[${match[1]}].includes(${top.value})`, ctx)) return token.end;

        top.active = true;
    }

    handleEndcase(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || (top.type !== 'case' && top.type !== 'if')) {
            console.log(`[Template] Unexpected 'endunless' tag line ${token.line} column ${token.column}`);

            return;
        }

        stack.pop();
    }

    handleFilter(token, stack, ctx, index) {

    }


    handleEndfilter(token, stack, ctx, index) {

    }

    handleBlock(token, stack, ctx, index) {
        let match = token.value.match(/^block\s(.+)$/);

        if (!match) {
            console.warn(`[Template] Invalid 'capture' syntax line ${token.line} column ${token.column}`);

            return;
        }

        stack.push({
            type: 'capture',
            name: match.name,
            value: ''
        });
    }

    handleEndblock(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'capture') {
            console.log(`[Template] Unexpected 'endcapture' tag line ${token.line} column ${token.column}`);

            return;
        }

        ctx[top.name] = top.value;

        stack.pop();
    }

    handleRaw(token, stack, ctx, index) {
        stack.push({ type: 'raw' });
    }

    handleEndraw(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'raw') {
            console.log(`[Template] Unexpected 'raw' tag line ${token.line} column ${token.column}`);

            return;
        }

        stack.pop();
    }

    handleComment(token, stack, ctx, index) {
        stack.push({ type: 'comment' });

        return token.end;
    }

    handleEndcomment(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'comment') {
            console.log(`[Template] Unexpected 'comment' tag line ${token.line} column ${token.column}`);

            return;
        }

        stack.pop();
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
test.push(`--- set string ---
{% set my_var = "Hello test" %}
{{ my_var }}, world!

--- set number ---

{% set my_var = 121213 %}
{{ my_var }}, world!

--- set decimal ---

{% set my_var = 1212.13 %}
{{ my_var }}, world!

--- set replace ---

{% set test_1 = "Hello world" | replace('e', 'A') %}
{{ test_1 }}, world!

--- ucase ---

{% set my_var = "Hello world" %}
{{ my_var | upper }}

--- replace --- 

{% set my_var = "Hello world" %}
{{ my_var | replace("e", "Q") }}, world!

--- set test ---
{% set year = 2025 %}
Current year: {{ year }}

--- set multi test ---

{% set test_1 = "Hello, World" | lower | upper | replace("O", "X") %}
{{ test_1 }}
`);

// 12
test.push(`

{% set my_var = "Hello" %}{% if my_var == "Hello" %}WORKS!{% endif %}

{% set my_var = "red" %}{% if my_var == "red" %}red!{% else %}blue{% endif %}

{% set my_var = "blue" %}{% if my_var == "red" %}red!{% else %}blue{% endif %}

{% set my_var = "green" %}{% if my_var == "red" %}red!{% elif my_var == "green" %}green{% else %}blue{% endif %}


{% set my_var = "red" %}
{% if my_var == "red" %}
red!
{% elif my_var == "green" %}
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

{% for i in range(1, 5) %}
  {% if i == 4 %}
      skip
    {% continue %}
  {% endif %}
  
  {{ i }}
  
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
{% set handle = 100 %}
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

// 16
test.push(`
{% set my_var = 100 %}
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

// 17
test.push(`
{% raw %}
{% set my_var = 100 %}
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
{% endraw %}

example code
`);

// 18
test.push(`
{% set name = "test" %}
{% capture greeting %}
  Hello {{ name }}!
{% endcapture %}
{{ greeting }}
`);

let number = 2;

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
                ],
                test: '111'
            },
            {
                name: "Bob",
                colors: [
                    "yellow"
                ],
                test: '222'
            },
            {
                name: "Carol",
                colors: [
                    "purple",
                    "pink"
                ],
                test: '333'
            }
        ]
    };

    let output = await template.parse(value, data);

    console.log('OUTPUT');
    console.log(output);
});