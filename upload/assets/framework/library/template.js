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
            endcomment: this.handleEndcomment.bind(this),
        };

        this.filter = {
            // Core filters
            escape: value => String(value ?? '').replace(/[&<>"']/g, m => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[m] || m)),
            upcase: value => String(value).toUpperCase(),
            downcase: value => String(value).toLowerCase(),
            strip: value => String(value ?? '').trim(),
            lstrip: value => String(value ?? '').replace(/^\s+/, ''),
            rstrip: value => String(value ?? '').replace(/\s+$/, ''),
            newline_to_br: value => String(value ?? '').replace(/\n/g, '<br>'),
            prepend: (value, prefix) => String(prefix ?? '') + String(value ?? ''),
            append: (value, suffix) => String(value ?? '') + String(suffix ?? ''),
            size: value => {
                if (Array.isArray(value)) return value.length;
                if (typeof value === 'string') return value.length;
                return 0;
            },
            join: (value, sep = ' ') => Array.isArray(value) ? value.join(String(sep)) : String(value ?? ''),

            // Math filters
            plus: (v, a) => Number(v || 0) + Number(a || 0),
            minus: (v, a) => Number(v || 0) - Number(a || 0),
            times: (v, a) => Number(v || 0) * Number(a || 0),
            divided_by: (v, a) => {
                const d = Number(a || 1);
                return d === 0 ? 0 : Number(v || 0) / d;
            },
            modulo: (v, a) => Number(v || 0) % Number(a || 1),
            abs: v => Math.abs(Number(v) || 0),
            ceil: v => Math.ceil(Number(v) || 0),
            floor: v => Math.floor(Number(v) || 0),
            round: (v, decimals = 0) => {
                const n = Number(v);
                return isNaN(n) ? 0 : Number(n.toFixed(Number(decimals)));
            },
            // Multi-argument capable filters
            replace: (value, search, replaceWith = '', ...pairs) => {
                let str = String(value ?? '');
                str = str.replaceAll(search, replaceWith);
                for (let i = 0; i < pairs.length; i += 2) {
                    const s = pairs[i];
                    const r = pairs[i + 1] ?? '';
                    str = str.replaceAll(s, r);
                }
                return str;
            },
            slice: (value, start, length) => {
                const str = String(value ?? '');
                const s = Number(start) || 0;
                return length !== undefined ? str.slice(s, s + Number(length)) : str.slice(s);
            },
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
        const ctx = {...data};
        const tokens = this.tokenize(template);
        let output = '';
        const stack = [];
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
                if (token.type === 'text') output += token.value;
                else if (token.type === 'output') output += `{{${token.raw}}}`;
                else if (token.type === 'tag') output += `{% ${token.raw} %}`;
                i++;
                continue;
            }

            // ─── Normal rendering ─────────────────────────────────────────────
            if (token.type === 'text') {
                if (isCapturing) {
                    top.content += token.value;
                } else if (!this.shouldSkipRendering(stack)) {
                    output += token.value;
                }

                i++;

                continue;
            }

            if (token.type === 'output') {
                if (this.shouldSkipRendering(stack)) {
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
                tokens.push({type: 'text', value: template.slice(lastIndex, match.index)});
            }
            if (match[1] !== undefined) {
                tokens.push({type: 'output', raw: match[1].trim()});
            } else if (match[2] !== undefined) {
                tokens.push({type: 'tag', raw: match[2].trim()});
            }
            lastIndex = regex.lastIndex;
        }

        if (lastIndex < template.length) {
            tokens.push({type: 'text', value: template.slice(lastIndex)});
        }
        return tokens;
    }

    shouldSkipRendering(stack) {
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

    evaluate(expr, ctx) {
        if (!expr?.trim()) return undefined;

        try {
            const safe = expr.trim().replace(/([a-zA-Z_]\w*)\./g, 'data.$1.').replace(/([a-zA-Z_]\w*)\[/g, 'data.$1[');

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
                    if (current.trim()) args.push(current.trim());

                    current = '';
                } else if (!/\s/.test(c)) {
                    current += c;
                }
            }
            i++;
        }

        if (current.trim()) args.push(current.trim());

        return args.map(a => {
            if ((a[0] === '"' && a.at(-1) === '"') || (a[0] === "'" && a.at(-1) === "'")) {
                return a.slice(1, -1);
            }
            return a;
        });
    }

    // ─── Tag handlers ────────────────────────────────────────────────────────
    // Unified handler map — all handlers get the same 4 arguments
    handleIf(token, stack, ctx, currentIndex) {
        const cond = token.raw.slice(2).trim();
        stack.push({type: 'if', entered: !!this.evaluate(cond, ctx)});
    }

    handleUnless(token, stack, ctx, currentIndex) {
        const cond = token.raw.slice(6).trim();
        stack.push({type: 'unless', entered: !this.evaluate(cond, ctx)});
    }

    handleElsif(token, stack, ctx, currentIndex) {
        const top = stack[stack.length - 1];
        if (!top || (top.type !== 'if' && top.type !== 'unless') || top.entered) return;
        const cond = token.raw.slice(5).trim();
        top.entered = !!this.evaluate(cond, ctx);
    }

    handleElse(token, stack, ctx, currentIndex) {
        const top = stack[stack.length - 1];
        if (!top || (top.type !== 'if' && top.type !== 'unless') || top.entered) return;
        top.entered = true;
    }

    handleEndif(token, stack, ctx, currentIndex) {
        if (stack[stack.length - 1]?.type === 'if') stack.pop();
    }

    handleEndunless(token, stack, ctx, currentIndex) {
        if (stack[stack.length - 1]?.type === 'unless') stack.pop();
    }

    handleFor(token, stack, ctx, currentIndex) {
        const raw = token.raw.trim();

        // 1. Split into main part + optional parameters
        const parts = raw.split(/\s+/);
        if (parts.length < 4 || parts[2].toLowerCase() !== 'in') return;

        const itemName = parts[1];
        let collectionExpr = parts.slice(3).join(' ');

        // 2. Parse optional limit & offset parameters
        let limit = Infinity;
        let offset = 0;

        // Look for limit: N and offset: N anywhere after 'in'
        const paramRegex = /(limit|offset):\s*(\d+)/gi;

        let match;

        while ((match = paramRegex.exec(collectionExpr)) !== null) {
            const key = match[1].toLowerCase();
            const val = Number(match[2]);
            if (!isNaN(val)) {
                if (key === 'limit') limit = val;
                if (key === 'offset') offset = val;
            }

            // Remove the limit/offset clauses from the expression so evaluate works
            collectionExpr = collectionExpr.replace(paramRegex, '').trim();

            const fullArray = this.evaluate(collectionExpr, ctx) || [];

            // Apply offset & limit (safe slicing)
            const start = Math.max(0, offset);
            const end = limit === Infinity ? undefined : start + limit;
            const slicedArray = fullArray.slice(start, end);

            stack.push({
                type: 'for',
                itemName,
                array: slicedArray,           // ← now already sliced
                originalArrayLength: fullArray.length,  // useful if you later want total count
                index: -1,
                bodyStart: currentIndex + 1,
                parentCtx: {...ctx}
            });
        }
    }

    handleEndfor(token, stack, ctx, currentIndex) {
        const top = stack[stack.length - 1];
        if (top?.type !== 'for') return null;

        top.index++;

        if (top.index < top.array.length) {
            Object.assign(ctx, top.parentCtx);

            ctx[top.itemName] = top.array[top.index];
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
        delete ctx[top.itemName];
        delete ctx.forloop;
        return null;
    }

    handleContinue(token, stack, ctx, currentIndex) {
        const top = stack[stack.length - 1];
        if (top?.type !== 'for') return;

        // Signal to skip the rest of this iteration
        top._skipCurrent = true;
    }

    handleBreak(token, stack, ctx, currentIndex) {
        const top = stack[stack.length - 1];
        if (top?.type !== 'for') return;

        // Signal to exit the entire loop
        top._breakLoop = true;
    }

    handleAssign(token, stack, ctx, currentIndex) {
        const raw = token.raw.trim();
        const eqIndex = raw.indexOf('=');
        if (eqIndex <= 0) return;

        const varName = raw.slice(0, eqIndex).trim();
        let valuePart = raw.slice(eqIndex + 1).trim();

        const pipeIndex = valuePart.indexOf('|');
        let filters = '';
        if (pipeIndex >= 0) {
            filters = valuePart.slice(pipeIndex);
            valuePart = valuePart.slice(0, pipeIndex).trim();
        }

        let value = this.evaluate(valuePart, ctx);
        if (filters) value = this.applyFilters(value, filters);

        ctx[varName] = value;
    }

    handleCapture(token, stack, ctx, currentIndex) {
        const words = token.raw.trim().split(/\s+/);
        if (words.length < 2) return;
        stack.push({type: 'capture', varName: words[1], content: ''});
    }

    handleEndcapture(token, stack, ctx, currentIndex) {
        const top = stack[stack.length - 1];
        if (top?.type === 'capture') {
            ctx[top.varName] = top.content;
            stack.pop();
        }
    }

    handleRaw(token, stack, ctx, currentIndex) {
        stack.push({type: 'raw'});
    }

    handleEndraw(token, stack, ctx, currentIndex) {
        while (stack.length && stack[stack.length - 1].type !== 'raw') {
            stack.pop();
        }
        if (stack.length) stack.pop();
    }

    handleCase(token, stack, ctx, currentIndex) {
        const expr = token.raw.slice(4).trim();
        stack.push({
            type: 'case',
            value: this.evaluate(expr, ctx),
            matched: false
        });
    }

    handleWhen(token, stack, ctx, currentIndex) {
        const top = stack[stack.length - 1];
        if (!top || top.type !== 'case' || top.matched) return;

        const valuesStr = token.raw.slice(4).trim();
        const values = valuesStr.split(/\s+/).map(v => v.replace(/^["'](.+)["']$/, '$1'));
        top.matched = values.some(val => top.value == val);
    }

    handleEndcase(token, stack, ctx, currentIndex) {
        if (stack[stack.length - 1]?.type === 'case') stack.pop();
    }

    handleComment(token, stack, ctx, currentIndex) {
        stack.push({type: 'comment'});
    }

    handleEndcomment(token, stack, ctx, currentIndex) {
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

export {template};

const html = `
{% for i in (1..10) %}
{% if i == 3 %}{% continue %}{% endif %}
{% if i == 7 %}{% break %}{% endif %}
Number: {{ i }}
{% endfor %}
`;

console.log(template.parse(html, {
    items: ['A', 'B', 'C']
}));


const html2 = `
  {% assign total = 0 %}
  
  {% for item in cart %}
    {% assign total = total | plus: item.price %}
    {{ item.name }} — {{ item.price }}
  {% endfor %}
  
  Total: {{ total }}
`;

const data = {
    cart: [
        {name: "Coffee", price: 28},
        {name: "Cake", price: 45}
    ]
};

const result = template.parse(html2, data);

console.log(result);