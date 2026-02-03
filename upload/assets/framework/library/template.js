class Template {
    static instance = null;

    constructor() {
        this.directory = '';
        this.path = new Map();
        this.cache = new Map();

        // Unified handler map — all handlers get the same 4 arguments
        this.tagHandler = {
            if: this.handleIf.bind(this),
            unless: this.handleUnless.bind(this),
            elsif: this.handleElsif.bind(this),
            else: this.handleElse.bind(this),
            endif: this.handleEndif.bind(this),
            endunless: this.handleEndunless.bind(this),
            for: this.handleFor.bind(this),
            endfor: this.handleEndfor.bind(this),
            continue: this.handleContinue.bind(this),
            break: this.handleBreak.bind(this),
            case: this.handleCase.bind(this),
            when: this.handleWhen.bind(this),
            endcase: this.handleEndcase.bind(this),
            assign: this.handleAssign.bind(this),
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
                return value.replace(/[&<>"']/g, m => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[m] || m));
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
                if (typeof value === 'array') {
                    return value.length;
                }

                if (typeof value === 'string') {
                    return value.length;
                }

                return 0;
            },
            join: (value, seperator = ' ') => {
                return value.join(seperator);
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
            divided_by: (value, amount) => {
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
                return value.toFixed(decimal);
            },
            // Multi-argument capable filters
            replace: (value, search, replace = '') => {
                return value.replaceAll(search, replace);
            },
            slice: (value, start, length) => {
                return length !== undefined ? value.slice(start, start + length) : value.slice(start);
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
        this.filter.set(name, filter);
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
            console.log('Could not load template file ' + path);
        }

        return '';
    }

    parse(code, data = {}) {
        return this._render(code, data);
    }

    async render(path, data = {}) {
        return this.parse(await this.fetch(path), data);
    }

    // ─── Main render ────────────────────────────────────────────────────────
    _render(template, data = {}) {
        const ctx = { ...data };
        const tokens = this.tokenize(template);

        const stack = [];
        let output = '';
        let i = 0;

        while (i < tokens.length) {
            const token = tokens[i];
            const top = stack[stack.length - 1];

            const inRaw = stack.some(s => s.type === 'raw');
            const inComment = stack.some(s => s.type === 'comment');
            const isCapturing = top?.type === 'capture';

            // ─── Comment handling ─────────────────────────────────────────────
            if (inComment) {
                if (token.type === 'tag') {
                    const cmd = token.raw.split(/\s+/)[0].toLowerCase();

                    if (cmd === 'endcomment') {
                        this.handleEndcomment(token, stack, ctx, i);
                    }
                }

                i++;

                continue;
            }

            // ─── Raw handling ─────────────────────────────────────────────────
            if (inRaw) {
                if (token.type === 'tag') {
                    const cmd = token.raw.split(/\s+/)[0].toLowerCase();

                    if (cmd === 'endraw') {
                        this.handleEndraw(token, stack, ctx, i);

                        i++;

                        continue;
                    }
                }

                if (token.type === 'text') {
                    output += token.value;
                } else if (token.type === 'output') {
                    output += `{{${token.raw}}}`;
                } else if (token.type === 'tag') {
                    output += `{% ${token.raw} %}`;
                }

                i++;

                continue;
            }

            // ─── Normal rendering ─────────────────────────────────────────────
            if (token.type === 'text') {
                if (isCapturing) {
                    top.content += token.value;
                } else if (!this.isSkip(stack)) {
                    output += token.value;
                }

                i++;

                continue;
            }

            if (token.type === 'output') {
                if (this.isSkip(stack)) {
                    i++;

                    continue;
                }

                const [rawExpr, ...filters] = token.raw.split('|').map(s => s.trim());

                let value = this.evaluate(rawExpr, ctx);

                value = this.applyFilters(value, filters.join('|'));

                const rendered = this.filter.escape(value ?? '');

                if (isCapturing) {
                    top.content += rendered;
                } else {
                    output += rendered;
                }

                i++;

                continue;
            }

            if (token.type === 'tag') {
                const cmd = token.raw.split(/\s+/)[0].toLowerCase();
                const handler = this.tagHandler[cmd].bind(this);

                if (handler) {
                    const jumpTo = handler(token, stack, ctx, i);

                    if (typeof jumpTo === 'number' && jumpTo >= 0) {
                        i = jumpTo;
                        continue;
                    }
                }
            }

            i++;
        }

        return output;
    }

    tokenize(template) {
        const tokens = [];
        const regex = /\{\{([\s\S]*?)\}\}|\{%\s*([\s\S]*?)\s*%\}/g;

        let lastIndex = 0;
        let match;

        while ((match = regex.exec(template)) !== null) {
            if (match.index > lastIndex) {
                tokens.push({
                    type: 'text',
                    value: template.slice(lastIndex, match.index
                )});
            }

            if (match[1] !== undefined) {
                tokens.push({
                    type: 'output',
                    raw: match[1].trim()
                });
            } else if (match[2] !== undefined) {
                tokens.push({
                    type: 'tag',
                    raw: match[2].trim()
                });
            }

            lastIndex = regex.lastIndex;
        }

        if (lastIndex < template.length) {
            tokens.push({
                type: 'text',
                value: template.slice(lastIndex)
            });
        }

        return tokens;
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

    evaluate(expression, ctx) {
        if (!expression?.trim()) return undefined;

        try {
            const safe = expression.trim().replace(/([a-zA-Z_]\w*)\./g, 'data.$1.').replace(/([a-zA-Z_]\w*)\[/g, 'data.$1[');

            let func = new Function('data', `with(data) { return (${safe}); }`);

            return func(ctx);
        } catch {
            return undefined;
        }
    }

    /**
     * Apply chain of filters — now supports multiple comma-separated arguments per filter
     */
    applyFilters(value, filterStr) {
        if (!filterStr) return value;

        let result = value;

        const parts = filterStr.split('|').map(p => p.trim()).filter(Boolean);

        for (const part of parts) {
            const colonIndex = part.indexOf(':');
            const name = colonIndex === -1 ? part.trim() : part.substring(0, colonIndex).trim();
            const argsStr = colonIndex === -1 ? '' : part.substring(colonIndex + 1).trim();

            const fn = this.filter[name];

            if (!fn) continue;

            const args = this.#parseFilterArgs(argsStr);

            result = fn(result, ...args);
        }
        return result;
    }

    /**
     * Parse filter arguments string → array of values
     * Handles quotes and commas inside quotes
     * @private
     */
    #parseFilterArgs(str) {
        if (!str) return [];

        const args = [];

        let current = '';
        let quote = null;
        let i = 0;

        while (i < str.length) {
            const c = str[i];

            if (quote) {
                if (c === quote && str[i - 1] !== '\\') {
                    quote = null;
                    args.push(current);
                    current = '';
                    i++;
                    continue;
                }

                current += c;
            } else {
                if (c === '"' || c === "'") {
                    quote = c;
                } else if (c === ',') {
                    if (current.trim()) {
                        args.push(current.trim());
                    }

                    current = '';
                } else if (!/\s/.test(c)) {
                    current += c;
                }
            }

            i++;
        }

        if (current.trim()) {
            args.push(current.trim());
        }

        return args.map(a => {
            if ((a[0] === '"' && a.at(-1) === '"') || (a[0] === "'" && a.at(-1) === "'")) {
                return a.slice(1, -1);
            }

            return a;
        });
    }

    // ─── Tag handlers ────────────────────────────────────────────────────────
    // Unified handler map — all handlers get the same 4 arguments
    handleIf(token, stack, ctx, index) {
        const condition = token.raw.slice(2).trim();

        stack.push({
            type: 'if',
            entered: !!this.evaluate(condition, ctx)
        });
    }

    handleUnless(token, stack, ctx, index) {
        const condition = token.raw.slice(6).trim();

        stack.push({
            type: 'unless',
            entered: !this.evaluate(condition, ctx)
        });
    }

    handleElsif(token, stack, ctx, index) {
        const top = stack[stack.length - 1];

        if (!top || (top.type !== 'if' && top.type !== 'unless') || top.entered) return;

        const condition = token.raw.slice(5).trim();

        top.entered = !!this.evaluate(condition, ctx);
    }

    handleElse(token, stack, ctx, index) {
        const top = stack[stack.length - 1];

        if (!top || (top.type !== 'if' && top.type !== 'unless') || top.entered) return;

        top.entered = true;
    }

    handleEndif(token, stack, ctx, index) {
        if (stack[stack.length - 1]?.type === 'if') stack.pop();
    }

    handleEndunless(token, stack, ctx, index) {
        if (stack[stack.length - 1]?.type === 'unless') stack.pop();
    }

    handleFor(token, stack, ctx, index) {
        const raw = token.raw.trim();

        console.log('handleFor');
        console.log(token);

        // 1. Split into main part + optional parameters
        const parts = raw.split(/\s+/);

        console.log(parts);

        if (parts.length < 4 || parts[2].toLowerCase() !== 'in') return;

        const name = parts[1];

        let expression = parts.slice(3).join(' ');

        console.log(expression);

        // 2. Parse optional limit & offset parameters
        let limit = Infinity;
        let offset = 0;

        // Look for limit: N and offset: N anywhere after 'in'
        const limitMatch = expression.match(/limit\s*:\s*(\d+)/i);
        const offsetMatch = expression.match(/offset\s*:\s*(\d+)/i);

        if (limitMatch) limit = Number(limitMatch[1]);
        if (offsetMatch) offset = Number(offsetMatch[1]);

        // Remove limit/offset clauses (very naive but usually enough)
        expression = expression.replace(/limit\s*:\s*\d+/i, '').replace(/offset\s*:\s*\d+/i, '').trim();

        // Evaluate the collection
        const collection = this.evaluate(expression, ctx) || [];

        console.log(collection);

        // Force it to be an array — this is the key defensive line
        let fullArray = Array.isArray(collection) ? collection : [];

        // Handle common edge cases explicitly (optional but clearer)
        if (collection != null && !Array.isArray(collection)) {
            console.warn(`[Template] for-loop expected array but got ${typeof collection}:`, expression, collection);
        }

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
        const top = stack[stack.length - 1];

        console.log('handleEndfor');
        console.log(token);



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
        const top = stack[stack.length - 1];

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

    handleAssign(token, stack, ctx, index) {
        const raw = token.raw.replace(/^assign\s+/, '').trim();

        const eq_index = raw.indexOf('=');

        if (eq_index < 0) return;

        let name = raw.substring(0, eq_index).trim();
        let value = raw.substring(eq_index + 1).trim();

        let filter = '';

        // Check if contains | so the method can handle multiple filters.
        const pipe_index = value.indexOf('|');

        if (pipe_index >= 0) {
            filter = value.slice(pipe_index).trim();
            value = value.slice(0, pipe_index).trim();
        }

        let output = this.evaluate(value, ctx);

        if (filter) {
            output = this.applyFilters(output, filter);
        }

        ctx[name] = output;
    }

    handleCapture(token, stack, ctx, index) {
        const words = token.raw.trim().split(/\s+/);

        if (words.length < 2) return;

        stack.push({
            type: 'capture',
            name: words[1],
            content: ''
        });
    }

    handleEndcapture(token, stack, ctx, index) {
        const top = stack[stack.length - 1];

        if (top?.type === 'capture') {
            ctx[top.name] = top.content;

            stack.pop();
        }
    }

    handleRaw(token, stack, ctx, index) {
        stack.push({ type: 'raw' });
    }

    handleEndraw(token, stack, ctx, index) {
        while (stack.length && stack[stack.length - 1].type !== 'raw') {
            stack.pop();
        }

        if (stack.length) stack.pop();
    }

    handleCase(token, stack, ctx, index) {
        const expression = token.raw.slice(4).trim();

        stack.push({
            type: 'case',
            value: this.evaluate(expression, ctx),
            matched: false
        });
    }

    handleWhen(token, stack, ctx, index) {
        const top = stack[stack.length - 1];

        if (!top || top.type !== 'case' || top.matched) return;

        const code = token.raw.slice(4).trim();
        const values = code.split(/\s+/).map(v => v.replace(/^["'](.+)["']$/, '$1'));

        top.matched = values.some(val => top.value == val);
    }

    handleEndcase(token, stack, ctx, index) {
        if (stack[stack.length - 1]?.type === 'case') stack.pop();
    }

    handleComment(token, stack, ctx, index) {
        stack.push({ type: 'comment' });
    }

    handleEndcomment(token, stack, ctx, index) {
        while (stack.length && stack[stack.length - 1].type !== 'comment') {
            stack.pop();
        }

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