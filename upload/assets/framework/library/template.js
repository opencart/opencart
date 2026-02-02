class Engine {
    constructor() {
        this.filters = {
            // Core filters
            escape: value => String(value ?? '').replace(/[&<>"']/g, m => ({'&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'}[m] || m)),
            upcase: value => String(value).toUpperCase(),
            downcase: value => String(value).toLowerCase(),
            default: (value, fallback = '') => (value == null || value === '') ? fallback : value,
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
            // Example: replace supports multiple pairs (like Ruby/Liquid behavior)

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

        // Unified handler map — all handlers get the same 4 arguments
        this.tagHandlers = {
            if: this.handleIf.bind(this),
            unless: this.handleUnless.bind(this),
            elsif: this.handleElsif.bind(this),
            else: this.handleElse.bind(this),
            endif: this.handleEndif.bind(this),
            endunless: this.handleEndunless.bind(this),
            for: this.handleFor.bind(this),
            endfor: this.handleEndfor.bind(this),
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

      const fn = this.filters[name];
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

    // Remove surrounding quotes if present
    return args.map(a => {
      if ((a[0] === '"' && a[stack.length - 1] === '"') || (a[0] === "'" && a[stack.length - 1] === "'")) {
        return a.slice(1, -1);
      }
      return a;
    });
  }

  tokenize(template) {
    const tokens = [];
    const regex = /\{\{([\s\S]*?)\}\}|\{%\s*([\s\S]*?)\s*%\}/g;
    let last = 0;
    let m;

    while ((m = regex.exec(template))) {
      if (m.index > last) tokens.push({ type: 'text', value: template.slice(last, m.index) });
      if (m[1]) tokens.push({ type: 'output', raw: m[1].trim() });
      else if (m[2]) tokens.push({ type: 'tag', raw: m[2].trim() });
      last = regex.lastIndex;
    }

    if (last < template.length) tokens.push({ type: 'text', value: template.slice(last) });
    return tokens;
  }

    // ─── Unified handler helpers ─────────────────────────────────────────────

  shouldSkipRendering(stack) {
    for (let i = stack.length - 1; i >= 0; i--) {
      const f = stack[i];
      if (f.type === 'if' || f.type === 'unless') return !f.entered;
      if (f.type === 'case') return !f.matched;
    }
    return false;
  }

  // ─── Tag handlers ────────────────────────────────────────────────────────

  handleIf(token, stack, ctx) {
    const cond = token.raw.slice(2).trim();
    stack.push({ type: 'if', entered: !!this.evaluate(cond, ctx) });
  }

  handleUnless(token, stack, ctx) {
    const cond = token.raw.slice(6).trim();
    stack.push({ type: 'unless', entered: !this.evaluate(cond, ctx) });
  }

  handleElsif(token, stack, ctx) {
    const top = stack[stack.length - 1];
    if (!top || (top.type !== 'if' && top.type !== 'unless') || top.entered) return;
    const cond = token.raw.slice(5).trim();
    top.entered = !!this.evaluate(cond, ctx);
  }

  handleElse(token, stack) {
    const top = stack[stack.length - 1];
    if (!top || (top.type !== 'if' && top.type !== 'unless') || top.entered) return;
    top.entered = true;
  }

  handleEndif(stack) {
    if (stack[stack.length - 1]?.type === 'if') stack.pop();
  }

  handleEndunless(stack) {
    if (stack[stack.length - 1]?.type === 'unless') stack.pop();
  }

  handleFor(token, stack, ctx, i) {
    const words = token.raw.split(/\s+/);
    if (words.length < 4 || words[2].toLowerCase() !== 'in') return;

    const itemName = words[1];
    const expr = words.slice(3).join(' ');
    const array = this.evaluate(expr, ctx) || [];

    stack.push({
      type: 'for',
      itemName,
      array,
      index: -1,
      bodyStart: i + 1,
      parentCtx: { ...ctx }
    });
  }

  handleEndfor(token, stack, ctx) {
    const top = stack[stack.length - 1]
    if (top?.type !== 'for') return null;

    top.index++;

    if (top.index < top.array.length) {
      Object.assign(ctx, top.parentCtx);
      ctx[top.itemName] = top.array[top.index];
      ctx.forloop = {
        first: top.index === 0,
        last: top.index === top.array.length - 1,
        index: top.index + 1,
        index0: top.index,
        rindex: top.array.length - top.index,
        rindex0: top.array.length - top.index - 1,
        length: top.array.length
      };
      return top.bodyStart;
    }

    Object.assign(ctx, top.parentCtx);
    stack.pop();
    delete ctx[top.itemName];
    delete ctx.forloop;
    return null;
  }

  handleAssign(token, stack, ctx) {
    const raw = token.raw.trim();
    const eq = raw.indexOf('=');
    if (eq <= 0) return;

    const varName = raw.slice(0, eq).trim();
    let valuePart = raw.slice(eq + 1).trim();

    const pipe = valuePart.indexOf('|');
    const filters = pipe >= 0 ? valuePart.slice(pipe) : '';
    if (pipe >= 0) valuePart = valuePart.slice(0, pipe).trim();

    let value = this.evaluate(valuePart, ctx);
    if (filters) value = this.applyFilters(value, filters);

    if (varName) ctx[varName] = value;
  }

  handleCapture(token, stack) {
    const words = token.raw.trim().split(/\s+/);
    if (words.length < 2) return;
    stack.push({ type: 'capture', varName: words[1], content: '' });
  }

  handleEndcapture(token, stack, ctx) {
    const top = stack[stack.length - 1];
    if (top?.type === 'capture') {
      ctx[top.varName] = top.content;
      stack.pop();
    }
  }

  handleRaw(token, stack) {
    stack.push({ type: 'raw' });
  }

  handleEndraw(token, stack) {
    while (stack.length && stack[stack.length - 1]?.type !== 'raw') stack.pop();
    stack.pop();
  }

  handleCase(token, stack, ctx) {
    const expr = token.raw.slice(4).trim();
    stack.push({ type: 'case', value: this.evaluate(expr, ctx), matched: false });
  }

  handleWhen(token, stack) {
    const top = stack[stack.length - 1];
    if (!top || top.type !== 'case' || top.matched) return;

    const valuesStr = token.raw.slice(4).trim();
    const values = this.#parseFilterArgs(valuesStr); // reuse parser
    top.matched = values.some(v => top.value == v);
  }

  handleEndcase(stack) {
    if (stack[stack.length - 1]?.type === 'case') stack.pop();
  }

  handleComment(token, stack) {
    stack.push({ type: 'comment' });
  }

  handleEndcomment(stack) {
    while (stack.length && stack[stack.length - 1]?.type !== 'comment') stack.pop();
    if (stack.length) stack.pop();
  }

    // ─── Main render method ─────────────────────────────────────────────────

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
      const inComment = stack.some(s => s.type === 'comment');
      const capturing = top?.type === 'capture';

      if (inComment) {
        if (token.type === 'tag' && token.raw.split(/\s+/)[0].toLowerCase() === 'endcomment') {
          this.handleEndcomment(token, stack, ctx);
        }
        i++;
        continue;
      }

            // ─── Raw handling ─────────────────────────────────────────────────
            if (inRaw) {

        if (token.type === 'tag' && token.raw.split(/\s+/)[0].toLowerCase() === 'endraw') {
          this.handleEndraw(token, stack, ctx);
          i++;
          continue;
        }
        if (token.type === 'text') output += token.value;
        else if (token.type === 'output') output += `{{${token.raw}}}`;
        else if (token.type === 'tag') output += `{% ${token.raw} %}`;
        i++;
        continue;
      }

      if (token.type === 'text') {
        if (capturing) top.content += token.value;
        else if (!this.shouldSkipRendering(stack)) output += token.value;
        i++;
        continue;
      }

      if (token.type === 'output') {
        if (this.shouldSkipRendering(stack)) { i++; continue; }

        const [expr, ...filters] = token.raw.split('|').map(s => s.trim());
        let val = this.evaluate(expr, ctx);
        val = this.applyFilters(val, filters.join('|'));
        const out = this.filters.escape(val ?? '');
        if (capturing) top.content += out;
        else output += out;
        i++;
        continue;
      }

      if (token.type === 'tag') {
        const cmd = token.raw.split(/\s+/)[0].toLowerCase();
        const handler = this.tagHandlers[cmd];
        if (handler) {
          const jump = handler(token, stack, ctx, i);
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

export {template};


// ───────────────────────────────────────────────────────────────
// Test suite for your Engine class
// Run this code after defining class Engine { ... }
// ───────────────────────────────────────────────────────────────

function runTests() {
    const engine = new Engine();

    console.log("Starting tests...\n");

    // ─── Test 1: Basic variable + filter ─────────────────────────────
    test("Basic output + upcase + escape", () => {
        const tmpl = "Hello {{ name | upcase | escape }}!";
        const data = {name: "Daniel & Co"};
        const expected = "Hello DANIEL &amp; CO!";
        const result = engine.render(tmpl, data);
        assertEqual(result, expected);
    });

    // ─── Test 2: If / else ───────────────────────────────────────────
    test("If / else logic", () => {
        const tmpl = `
{% if age >= 18 %}
  Adult
{% else %}
  Minor
{% endif %}
    `.trim();

        let result;

        result = engine.render(tmpl, {age: 20});
        assertEqual(result.trim(), "Adult");

        result = engine.render(tmpl, {age: 15});
        assertEqual(result.trim(), "Minor");
    });

    // ─── Test 3: For loop + forloop variables ────────────────────────
    test("For loop + forloop.index / first / last", () => {
        const tmpl = `
{% for fruit in fruits %}
  {{ forloop.index }}. {{ fruit }}{% if forloop.last %} (last){% endif %}
{% endfor %}
    `.trim();

        const data = {fruits: ["apple", "banana", "cherry"]};
        const result = engine.render(tmpl, data).trim();

        const expected = `
1. apple
2. banana
3. cherry (last)
    `.trim();

        assertEqual(result, expected);
    });

    // ─── Test 4: Assign + math filters ───────────────────────────────
    test("Assign + times + plus", () => {
        const tmpl = `
{% assign total = price | times: qty %}
Total: {{ total | plus: 5.5 }}
    `.trim();

        const data = {price: 12, qty: 3};
        const result = engine.render(tmpl, data).trim();

        assertEqual(result, "Total: 41.5");
    });

    // ─── Test 5: Capture ─────────────────────────────────────────────
    test("Capture block", () => {
        const tmpl = `
{% capture message %}
Hello {{ name }},
welcome!
{% endcapture %}

{{ message | upcase }}
    `.trim();

        const result = engine.render(tmpl, {name: "Daniel"}).trim();
        const expected = "HELLO DANIEL,\nWELCOME!".trim();

        assertEqual(result, expected);
    });

    // ─── Test 6: Raw + comment ───────────────────────────────────────
    test("Raw and comment blocks", () => {
        const tmpl = `
Normal: {{ name }}

{% raw %}
  Raw: {{ name }} {% if true %}yes{% endif %}
{% endraw %}

{% comment %}
  Ignored content
  {{ secret }}
{% endcomment %}

End.
    `.trim();

        const result = engine.render(tmpl, {name: "test"}).trim();

        const expected = `
Normal: test

  Raw: {{ name }} {% if true %}yes{% endif %}

End.
    `.trim();

        assertEqual(result, expected);
    });

    // ─── Test 7: Unless ──────────────────────────────────────────────
    test("Unless", () => {
        const tmpl = "{% unless logged_in %}Please log in{% endunless %}";
        assertEqual(engine.render(tmpl, {logged_in: true}).trim(), "");
        assertEqual(engine.render(tmpl, {logged_in: false}).trim(), "Please log in");
    });

    // ─── Test 8: Case / when ─────────────────────────────────────────
    test("Case / when", () => {
        const tmpl = `
{% case color %}
  {% when "red" %}Stop
  {% when "green" "blue" %}Go
  {% else %}Wait
{% endcase %}
    `.trim();

        assertEqual(engine.render(tmpl, {color: "red"}).trim(), "Stop");
        assertEqual(engine.render(tmpl, {color: "green"}).trim(), "Go");
        assertEqual(engine.render(tmpl, {color: "blue"}).trim(), "Go");
        assertEqual(engine.render(tmpl, {color: "yellow"}).trim(), "Wait");
    });

    console.log("\nAll tests finished.");
}

// ─── Helper functions ──────────────────────────────────────────────

function test(name, fn) {
    try {
        fn();
        console.log(`✓ ${name}`);
    } catch (err) {
        console.error(`✗ ${name}`);
        console.error(err);
    }
}

function assertEqual(actual, expected) {
    if (actual !== expected) {
        throw new Error(`Expected:\n${JSON.stringify(expected)}\n\nGot:\n${JSON.stringify(actual)}`);
    }
}

// Run all tests
runTests();