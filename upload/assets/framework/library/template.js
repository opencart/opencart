class Engine {
    constructor() {
        this.filters = {
            escape: value => String(value ?? '').replace(/[&<>"']/g, m => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[m] || m)),
            upcase:   value => String(value).toUpperCase(),
            downcase: value => String(value).toLowerCase(),
            default:  (value, fallback = '') => (value == null || value === '') ? fallback : value,
        };
    }

    evaluate(expr, ctx) {
        if (!expr?.trim()) return undefined;
        try {
            const safe = expr.trim()
            .replace(/([a-zA-Z_]\w*)\./g, 'data.$1.')
            .replace(/([a-zA-Z_]\w*)\[/g, 'data.$1[');

            return new Function('data', `with(data) { return (${safe}); }`)(ctx);
        } catch {
            return undefined;
        }
    }

    applyFilters(value, filterStr) {
        if (!filterStr) return value;
        let result = value;
        const parts = filterStr.split('|').map(p => p.trim()).filter(Boolean);

        for (const part of parts) {
            const [name, ...argParts] = part.split(':').map(s => s.trim());
            const fn = this.filters[name];
            if (!fn) continue;

            const arg = argParts.join(':').trim().replace(/^["'](.+)["']$/, '$1');
            result = fn(result, arg);
        }
        return result;
    }

    tokenize(template) {
        const tokens = [];
        const regex = /\{\{([\s\S]*?)\}\}|\{%\s*([\s\S]*?)\s*%\}/g;
        let lastIndex = 0;
        let match;

        while ((match = regex.exec(template)) !== null) {
            if (match.index > lastIndex) {
                tokens.push({ type: 'text', value: template.slice(lastIndex, match.index) });
            }

            if (match[1] !== undefined) {
                tokens.push({ type: 'output', raw: match[1].trim() });
            } else if (match[2] !== undefined) {
                tokens.push({ type: 'tag', raw: match[2].trim() });
            }

            lastIndex = regex.lastIndex;
        }

        if (lastIndex < template.length) {
            tokens.push({ type: 'text', value: template.slice(lastIndex) });
        }

        return tokens;
    }

    // ─── Tag Handlers ─────────────────────────────────────────────────────

    handleIf(token, stack, ctx) {
        const cond = token.raw.slice(2).trim();
        stack.push({ type: 'if', entered: !!this.evaluate(cond, ctx) });
    }

    handleElsif(token, stack, ctx) {
        const cond = token.raw.slice(5).trim();
        const top = stack[stack.length - 1];
        if (top?.type === 'if' && !top.entered) {
            top.entered = !!this.evaluate(cond, ctx);
        }
    }

    handleElse(token, stack) {
        const top = stack[stack.length - 1];
        if (top?.type === 'if' && !top.entered) {
            top.entered = true;
        }
    }

    handleEndif(stack) {
        if (stack[stack.length - 1]?.type === 'if') stack.pop();
    }

    handleFor(token, stack, ctx, currentIndex) {
        const words = token.raw.split(/\s+/);
        if (words.length < 4 || words[2].toLowerCase() !== 'in') return;

        const itemName = words[1];
        const arrayExpr = words.slice(3).join(' ');
        const arr = this.evaluate(arrayExpr, ctx) || [];

        stack.push({
            type: 'for',
            itemName,
            array: arr,
            index: -1,
            bodyStart: currentIndex + 1,
            parentCtx: { ...ctx } // snapshot to restore after loop
        });
    }

    handleEndfor(stack, ctx, currentIndex) {
        const top = stack[stack.length - 1];
        if (top?.type !== 'for') return null;

        top.index++;

        if (top.index < top.array.length) {
            // Restore context from before this loop + add current item
            Object.assign(ctx, top.parentCtx);
            ctx[top.itemName] = top.array[top.index];
            ctx.forloop = {
                index:   top.index + 1,
                index0:  top.index,
                first:   top.index === 0,
                last:    top.index === top.array.length - 1,
                length:  top.array.length
            };
            // Jump back to the first token of the loop body
            return top.bodyStart;
        }

        // Loop finished → restore parent context and clean up
        Object.assign(ctx, top.parentCtx);
        stack.pop();
        delete ctx[top.itemName];
        delete ctx.forloop;
        return null; // no jump → continue after endfor
    }

    handleAssign(token, ctx) {
        const words = token.raw.split(/\s+/);
        if (words.length < 4 || words[2] !== '=') return;

        const varName = words[1];
        const valueExpr = words.slice(3).join(' ');
        const [rawExpr, ...filters] = valueExpr.split('|').map(s => s.trim());
        let value = this.evaluate(rawExpr, ctx);
        value = this.applyFilters(value, filters.join('|'));
        ctx[varName] = value;
    }

    handleRaw(token, stack) {
        stack.push({ type: 'raw' });
    }

    handleEndraw(stack) {
        while (stack.length && stack[stack.length - 1].type !== 'raw') {
            stack.pop();
        }
        if (stack.length) stack.pop();
    }

    // ─── Main render ──────────────────────────────────────────────────────
    render(template, data = {}) {
        const ctx = { ...data };
        const tokens = this.tokenize(template);

        let output = '';
        const stack = [];
        let i = 0;

        while (i < tokens.length) {
            const token = tokens[i];

            const top = stack[stack.length - 1];
            const inRaw = stack.some(s => s.type === 'raw');

            // ─── Raw mode ───────────────────────────────────────────────────
            if (inRaw) {
                if (token.type === 'tag') {
                    const cmd = token.raw.split(/\s+/)[0].toLowerCase();
                    if (cmd === 'endraw') {
                        this.handleEndraw(stack);
                        i++;
                        continue;
                    }
                }

                // Literal output
                if (token.type === 'text') output += token.value;
                else if (token.type === 'output') output += '{{' + token.raw + '}}';
                else if (token.type === 'tag') output += '{% ' + token.raw + ' %}';

                i++;
                continue;
            }

            // ─── Normal mode ─────────────────────────────────────────────────
            if (token.type === 'text') {
                const shouldRender =
                    !top ||
                    (top.type === 'if' && top.entered) ||
                    (top.type === 'for');

                if (shouldRender) output += token.value;
                i++;
                continue;
            }

            if (token.type === 'output') {
                const skip = top?.type === 'if' && !top.entered;
                if (skip) {
                    i++;
                    continue;
                }

                const [rawExpr, ...filters] = token.raw.split('|').map(s => s.trim());
                let value = this.evaluate(rawExpr, ctx);
                value = this.applyFilters(value, filters.join('|'));
                output += this.filters.escape(value ?? '');
                i++;
                continue;
            }

            if (token.type === 'tag') {
                const words = token.raw.split(/\s+/);
                const cmd = words[0].toLowerCase();

                let jumpTo = null;

                if (cmd === 'if')         this.handleIf(token, stack, ctx);
                else if (cmd === 'elsif')     this.handleElsif(token, stack, ctx);
                else if (cmd === 'else')      this.handleElse(token, stack);
                else if (cmd === 'endif')     this.handleEndif(stack);

                else if (cmd === 'for')       this.handleFor(token, stack, ctx, i);
                else if (cmd === 'endfor')    jumpTo = this.handleEndfor(stack, ctx, i);

                else if (cmd === 'assign')    this.handleAssign(token, ctx);

                else if (cmd === 'raw')       this.handleRaw(token, stack);
                else if (cmd === 'endraw')    this.handleEndraw(stack);

                // Handle loop jump
                if (typeof jumpTo === 'number') {
                    i = jumpTo;
                    continue;
                }
            }

            i++;
        }

        return output;
    }
}


class Template {
    static instance = null;

    constructor() {
        this.engine = new Engine();
        this.directory = '';
        this.path = new Map();
        this.cache = new Map()
    }

    addPath(namespace, path = '') {
        if (!path) {
            this.directory = namespace;
        } else {
            this.path.set(namespace, path);
        }
    }

    addFilter(name, filter) {
        this.engine.filter.set(name, filter);
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
        return this.engine.render(code, data);
    }

    async render(path, data = {}) {
        return this.parse(await this.fetch(path), data);
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

console.log(template);

// Example
let html = 'Hello {{ user.name | escape }}!';

html += '{% if user.age >= 18 %}';
html += '  You are an adult.';
html += '{% else %}';
html += '  You are a minor.';
html += '{% endif %}';

html += '{% for product in cart.items %}';
html += '  • {{ product.name }} × {{ product.qty }} = ${{ product.price * product.qty }}';
html += '{% endfor %}';
html += '{% raw %}';
html += ' This is {{ not }} processed → {{ 1 + 2 }}';
html += '{% endraw %}';

console.log(html);

const data = {
    user: {
        name: 'Daniel <script>',
        age: 29
    },
    cart: {
        items: [
            {
                name: 'Coffee',
                qty: 2,
                price: 28
            },
            {
                name: 'Cake',
                qty: 1,
                price: 45
            }
        ]
    }
};

console.log(template.parse(html, data));