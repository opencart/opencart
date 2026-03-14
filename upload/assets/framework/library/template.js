/* OpenCart Twig replacement. Based on Django, Nunjucks template syntax. */
class CurlyTag {
    static instance = null;

    constructor() {
        this.directory = '';
        this.path = new Map();
        this.cache = new Map();

        this.handler = {
            set: this.handleSet.bind(this),
            if: this.handleIf.bind(this),
            endif: this.handleEndif.bind(this),
            elseif: this.handleElseif.bind(this),
            else: this.handleElse.bind(this),
            unless: this.handleUnless.bind(this),
            endunless: this.handleEndunless.bind(this),
            for: this.handleFor.bind(this),
            endfor: this.handleEndfor.bind(this),
            continue: this.handleContinue.bind(this),
            break: this.handleBreak.bind(this),
            switch: this.handleSwitch.bind(this),
            case: this.handleCase.bind(this),
            endswitch: this.handleEndswitch.bind(this),
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
                'elseif',
                'else',
                'endif',
            ],
            elseif: [
                'elseif',
                'else',
                'endif',
            ],
            else: [
                'endif',
                'endswitch'
            ],
            case: [
                'case',
                'else',
                'endswitch'
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

        this.filter = {
            // Tools
            default: (value, alternative, bool) => {
                if (bool) {
                    return value || alternative;
                } else {
                    return (value !== undefined) ? value : alternative;
                }
            },
            dump: (value) => {
                return JSON.stringify(value);
            },
            safe: (value) => {
                return (value === null || value === undefined) ? '' : value;
            },
            // HTML
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
            nl2br: (value) => {
                return String(value).replace(/\n/g, '<br/>');
            },
            striptag: (value) => {
                // Remove all tags, including <style>, <script>, comments, etc.
                return value.replace(/<[^>]*>/g, '').replace(/<!--[\s\S]*?-->/g, '').replace(/<\s*script[^>]*>[\s\S]*?<\/script>/gi, '').replace(/<\s*style[^>]*>[\s\S]*?<\/style>/gi, '').trim();
            },
            // String
            lower: (value) => {
                return value.toLowerCase();
            },
            upper: (value) => {
                return value.toUpperCase();
            },
            replace: (value, search, replace = '') => {
                return value.replaceAll(search, replace);
            },
            replace_first: (value, search, replace = '') => {
                return value.replace(search, replace);
            },
            split: (value, separator) => {
                return value.split(separator);
            },
            append: (value, suffix) => {
                return value + suffix;
            },
            prepend: (value, prefix) => {
                return prefix + value;
            },
            trim: (value) => {
                return value.trim();
            },
            ltrim: (value) => {
                return value.trimStart();
            },
            rtrim: (value) => {
                return value.trimEnd();
            },
            truncate: (value, length = 255, end = '...') => {
                if (value.length <= length) return value;

                return value.substring(0, length - end.length) + end;
            },
            wordcount: (value) => {
                let string = String(value).trim();

                if (!string) return 0;

                return string.split(/\s+/).length;
            },
            // Array
            batch: (value, length, fill = null) => {
                let result = [];

                for (let i = 0; i < value.length; i += length) {
                    let chunk = value.slice(i, i + length);

                    // Fill last chunk with fillWith value if needed
                    if (fill !== null && chunk.length < length) {
                        while (chunk.length < length) {
                            chunk.push(fill);
                        }
                    }

                    result.push(chunk);
                }

                return result;
            },
            concat: (value, ...args) => {
                return value.concat(...args);
            },
            groupby: (value, type) => {
                return Object.groupBy(value, ({type}) => type);
            },
            sort: (value, key = null, direction = 'asc') => {
                const dir = direction === 'desc' ? -1 : 1;

                return [...value].sort((a, b) => {
                    let va = key ? a?.[key] : a;
                    let vb = key ? b?.[key] : b;

                    return String(va ?? '').toLowerCase().localeCompare(String(vb ?? '').toLowerCase()) * dir;
                });
            },
            length: (value) => {
                return typeof value === 'array' || typeof value === 'string' ? value.length : 0;
            },
            offset: (value, offset) => {
                return value.slice(offset);
            },
            limit: (value, limit) => {
                return value.slice(0, limit);
            },
            sum: (value, amount) => {
                return numbers.reduce((accumulator, currentValue) => accumulator + currentValue, 0);
            },
            push: (value, item) => {
                value.push(item);
            },
            pop: (value) => {
                value.pop();
            },
            shift: (value) => {
                value.shift();
            },
            unshift: (value, item) => {
                value.unshift(item);
            },
            slice: (value, start, end) => {
                return length !== undefined ? value.slice(start, end) : value.slice(start);
            },
            join: (value, seperator = ' ') => {
                return value.join(seperator);
            },
            reverse: (value) => {
                if (Array.isArray(value)) {
                    return [...value].reverse();
                }

                if (typeof value === 'string') {
                    return value.split('').reverse().join('');
                }

                return value; // fallback: return unchanged
            },
            select: (value, key) => {
                value.filter((item) => item[key]);
            },
            reject: (value, key) => {
                value.filter((item) => !item[key]);
            },
            first: (value) => {
                return value[0] !== undefined ? value[0] : [];
            },
            last: (value) => {
                let last = value.length - 1;

                return value[last] !== undefined ? value[last] : [];
            },
            random: (value) => {
                return value[Math.floor(Math.random() * value.length)];
            },
            // Math
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
            round: (value, decimal = 0) => {
                return Number(value).toFixed(decimal);
            },
            ceil: (value) => {
                return Math.ceil(value);
            },
            floor: (value) => {
                return Math.floor(value);
            },
            abs: (value) => {
                return Math.abs(value);
            },
            modulo: (value, amount) => {
                return value % amount;
            },
            // URL
            urlencode: (value) => {
                return encodeURIComponent(value);
            },
            urldecode: value => {
                return decodeURIComponent(value);
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
            return await response.text();
        } else {
            console.log(`[Template] Could not load template file ${path}!`);
        }

        return '';
    }

    parse(code, data = {}) {
        return this.process(this.tokenize(code), data);
    }

    /**
     * Render the template with the current context
     * @returns {string} - Rendered template string
     */
    async render(path, data = {}) {
        if (!this.cache.has(path)) {
            this.cache.set(path, this.tokenize(await this.fetch(path)));
        }

        return this.process(this.cache.get(path), data);
    }

    // ─── Main render ────────────────────────────────────────────────────────
    process(tokens, data = {}) {
        let ctx = data;
        let index = 0;
        let stack = [];
        let output = '';

        while (index < tokens.length) {
            let token = tokens[index];
            let top = stack[stack.length - 1];

            let code = '';

            // Stack Capture Raw Syntax
            if (top?.type == 'raw' && index < top.end) {
                output += token.raw;

                index++;

                continue;
            }

            // Stack Output
            if (top?.type == 'output') {
                code = top?.output;

                stack.pop();
            }

            if (token.type == 'text') {
                code += this.handleText(token, stack, ctx, index);
            }

            if (token.type == 'output') {
                code += this.handleOutput(token, stack, ctx, index);
            }

            // Stack Capture Raw Output
            if (top?.type == 'capture') {
                top.value += code;

                code = '';
            }

            if (code) {
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
            let [raw, output, tag, comment] = match;

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

        try {
            let func = new Function('data', `with(data) return (${expression});`);

            return func({...ctx});
        } catch (error) {
            console.log(`[Template] Warning: Evaluate error '${error}'`);

            return undefined;
        }
    }

    /**
     * Apply chain of filters — now supports multiple comma-separated arguments per filter
     */
    parseFilter(value, expression = '', ctx) {
        if (expression == undefined) return value;

        let result = value;

        let filters = (expression.indexOf('|') !== -1) ? expression.split('|').map(value => value.trim()) : [expression];

        for (let filter of filters) {
            let match = filter.match(/^([^:]*):?\s?(.+)?$/);

            if (!match) return;

            let [, name, argument] = match;

            let args = [];

            if (argument) {
                args = this.evaluate('[' + argument + ']', ctx);
            }

            let func = this.filter[name];

            if (!func) {
                console.log(`[Template] Unknown filter: ${name}!`);

                return;
            }

            result = func(result, ...args);
        }

        return result;
    }

    /**
     * Handle Text
     */
    handleText(token, stack, ctx, index) {
        return token.raw;
    }

    /**
     * Handle Output
     *
     * {{ my_var | filter_1 | filter_2 }}
     */
    handleOutput(token, stack, ctx, index) {
        let match = token.value.match(/([^\|]+?)\s*(?:\s*\|\s*(.+))?$/);

        if (!match) {
            console.log(`[Template] Invalid output line ${token.line} column ${token.column}`);
        }

        let [, name, filter] = match;

        let value = this.evaluate(name, ctx);

        // Apply Filters
        if (filter !== undefined) {
            value = this.parseFilter(value, filter, ctx);
        }

        value = this.filter.escape(value ?? '');

        // Trim whitespace
        if (token.raw[3] == '-') {
            value = value.trimStart();
        }

        if (token.raw[-3] == '-') {
            value = value.trimEnd();
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
            value = this.parseFilter(value, filter, ctx);
        }

        // Add key value
        ctx[name] = value;
    }

    async handleInclude(token, stack, ctx, index) {
        let match = token.value.match(/^include\s(.+)$/);

        if (!match) {
            console.warn(`[Template] Invalid 'capture' syntax line ${token.line} column ${token.column}`);

            return;
        }

        let output = await this.render(match[1], ctx);

        stack.push({
            type: 'output',
            output: output
        });
    }

    handleCycle(token, stack, ctx, index) {
        let match = token.value.match(/^cycle\s(.*)/);

        if (!match) {
            console.warn(`[Template] Invalid 'cycle' syntax line ${token.line} column ${token.column}`);

            return;
        }

        let args = this.evaluate('[' + match[1] + ']', ctx);

        if (args.length === 0) return;


        const pos = i % arr.length;

        // Optional group name
        // Get or initialize cycle state for this group
        if (!ctx._cycle) ctx._cycle = {};

        if (!this.ctx._cycle[group]) this.ctx._cycle[group] = {
            index: -1,

            values
        };

        const state = this.ctx._cycle[group];

        state.index = (state.index + 1) % state.values.length;

        this.append(state.values[state.index]);

        return;
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

    handleElseif(token, stack, ctx, index) {
        let match = token.value.match(/^elseif\s(.+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'elseif' syntax line ${token.line} column ${token.column}`);

            return;
        }

        let top = stack[stack.length - 1];

        if (!top || top.type !== 'if') {
            console.log(`[Template] Unexpected 'elseif' tag line ${token.line} column ${token.column}`);

            return;
        }

        if (top.active) return token.end;

        // If any previous not active tags then set current tag to false;
        top.active = this.evaluate(match[1], ctx);
    }

    handleElse(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || (top.type !== 'if' && top.type !== 'unless' && top.type !== 'switch' && top.type !== 'for')) {
            console.log(`[Template] Unexpected 'else' tag line ${token.line} column ${token.column}`);

            return;
        }

        // If any previous not active tags then set current tag to false;
        if (top.active) return token.end;

        top.active = true;
    }

    handleFor(token, stack, ctx, index) {
        let match = token.value.match(/^for\s(.*)\sin\s([^\|]+?)\s*(?:\s*\|\s*(.+))?$/);

        if (!match) {
            console.log(`[Template] Invalid 'for' syntax line ${token.line} column ${token.column}`);

            return;
        }

        let [, name, key, filter] = match;

        // Match any global function
        let items = this.evaluate(key, ctx);

        if (typeof items !== 'object') {
            items = [];
        }

        // Apply Filters
        if (filter !== undefined) {
            items = this.parseFilter(items, filter, ctx);
        }

        stack.push({
            type: 'for',
            name: name,
            items: items,
            index: -1,
            start: index + 1,
            end: token.end,
            parent: {...ctx}
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
        for (let i = stack.length - 1; i >= 0; i--) {
            if (stack[i].type == 'for') {
                return stack[i].end;
            }

            // Remove all tags before endfor loop.
            stack.pop();
        }
    }

    handleBreak(token, stack, ctx, index) {
        let top = {};

        for (let i = stack.length - 1; i >= 0; i--) {
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

        return top.end + 1;
    }

    handleSwitch(token, stack, ctx, index) {
        let match = token.value.match(/^switch\s(\w+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'switch' syntax line ${token.line} column ${token.column}`);

            return;
        }

        stack.push({
            type: 'switch',
            value: match[1],
            active: false
        });
    }

    handleCase(token, stack, ctx, index) {
        let match = token.value.match(/^case\s(.+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'case' syntax line ${token.line} column ${token.column}`);

            return;
        }

        let top = stack[stack.length - 1];

        if (!top || top.type !== 'switch') {
            console.log(`[Template] Unexpected 'switch' tag line ${token.line} column ${token.column}`);

            return;
        }

        // Split if more than one item to compare
        if (!this.evaluate(`[${match[1]}].includes(${top.value})`, ctx)) return token.end;

        top.active = true;
    }

    handleEndswitch(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'switch') {
            console.log(`[Template] Unexpected 'switch' tag line ${token.line} column ${token.column}`);

            return;
        }

        stack.pop();
    }

    handleFilter(token, stack, ctx, index) {
        let match = token.value.match(/^filter\s(\w+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'filter' syntax line ${token.line} column ${token.column}`);

            return;
        }

        stack.push({
            type: 'capture',
            filter: match[1],
            output: ''
        });
    }

    handleEndfilter(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'capture') {
            console.log(`[Template] Unexpected 'endfilter' tag line ${token.line} column ${token.column}`);

            return;
        }

        stack.pop();

        stack.push({
            type: 'output',
            output: this.parseFilter(top.value, top.filter, ctx)
        });
    }

    handleBlock(token, stack, ctx, index) {
        let match = token.value.match(/^block\s(.+)$/);

        if (!match) {
            console.warn(`[Template] Invalid 'block' syntax line ${token.line} column ${token.column}`);

            return;
        }

        stack.push({
            type: 'capture',
            name: match[1],
            value: ''
        });
    }

    handleEndblock(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'capture') {
            console.log(`[Template] Unexpected 'endblock' tag line ${token.line} column ${token.column}`);

            return;
        }

        ctx[top.name] = top.value;

        stack.pop();
    }

    handleRaw(token, stack, ctx, index) {
        stack.push({
            type: 'raw',
            end: token.end
        });
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
        stack.push({type: 'comment'});

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
            this.instance = new CurlyTag();
        }

        return this.instance;
    }
}

const template = CurlyTag.getInstance();

export {template};