/*
* CurlyTwig
*
* @description Template engine
*
* @author Daniel Kerr
*
* OpenCart Twig replacement. Based on Django, Nunjucks, Twig template syntax.
*/
export class CurlyTag {
    static instance = null;

    constructor() {
        this.directory = '';
        this.path = new Map();
        this.cache = new Map();
        this.macros = new Map(); // name → { params, tokens, fn }
        this._imported = {}; // alias → Map of macros (JS importMacros)

        this.handler = {
            assign: this.handleAssign.bind(this),
            set: this.handleAssign.bind(this),
            capture: this.handleCapture.bind(this),
            endcapture: this.handleEndCapture.bind(this),
            if: this.handleIf.bind(this),
            endif: this.handleEndIf.bind(this),
            else: this.handleElse.bind(this),
            elseif: this.handleElseIf.bind(this),
            unless: this.handleUnless.bind(this),
            endunless: this.handleEndUnless.bind(this),
            case: this.handleCase.bind(this),
            endcase: this.handleEndCase.bind(this),
            when: this.handleWhen.bind(this),
            for: this.handleFor.bind(this),
            endfor: this.handleEndFor.bind(this),
            continue: this.handleContinue.bind(this),
            break: this.handleBreak.bind(this),
            cycle: this.handleCycle.bind(this),
            echo: this.handleEcho.bind(this),
            include: this.handleInclude.bind(this),
            filter: this.handleFilter.bind(this),
            endfilter: this.handleEndFilter.bind(this),
            raw: this.handleRaw.bind(this),
            endraw: this.handleEndRaw.bind(this),
            comment: this.handleComment.bind(this),
            endcomment: this.handleEndComment.bind(this),
            macro: this.handleMacro.bind(this),
            endmacro: this.handleEndMacro.bind(this),
            import: this.handleImport.bind(this),
            from: this.handleFrom.bind(this)
        };

        this.openclose = {
            if: [
                'elseif',
                'else',
                'endif'
            ],
            elseif: [
                'elseif',
                'else',
                'endif'
            ],
            else: [
                'endif',
                'endcase',
                'endfor',
                'endunless'
            ],
            when: [
                'when',
                'else',
                'endcase'
            ],
            for: [
                'else',
                'endfor'
            ],
            capture: [
                'endcapture'
            ],
            filter: [
                'endfilter'
            ],
            raw: [
                'endraw'
            ],
            comment: [
                'endcomment'
            ],
            unless: [
                'else',
                'endunless'
            ],
        };

        this.operator = {
            not: ' !',
            and: ' && ',
            or: ' || ',
        };

        this.filter = {
            // Tools
            default: (value, alternative, bool) => {
                if (bool) {
                    return value || alternative;
                } else {
                    return value !== undefined ? value : alternative;
                }
            },
            dump: (value) => {
                return JSON.stringify(value);
            },
            safe: (value) => {
                return value === null || value === undefined ? '' : value;
            },
            // HTML
            e: (value) => {
                return this.filter.escape(value);
            },
            escape: (value) => {
                let callback = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#39;',
                };

                return String(value ?? '').replace(/[&<>"']/g, (matched) => (callback)[matched] || matched);
            },
            unescape: (value) => {
                let unescaped = {
                    '&amp;': '&',
                    '&#38;': '&',
                    '&lt;': '<',
                    '&#60;': '<',
                    '&gt;': '>',
                    '&#62;': '>',
                    '&apos;': "'",
                    '&#39;': "'",
                    '&quot;': '"',
                    '&#34;': '"',
                };

                let regex = new RegExp(Object.keys(unescaped).join('|'), 'g');

                return String(value ?? '').replace(regex, (matched) => {
                    return unescaped[matched];
                });
            },
            nl2br: (value) => {
                return String(value).replace(/\n/g, '<br/>');
            },
            strip_newlines: (value) => {
                return String(value ?? '').replace(/\r\n|\r|\n/g, '');
            },
            normalize_whitespace: (value) => {
                return String(value ?? '').replace(/\s+/g, ' ').trim();
            },
            striptag: (value) => {
                // Remove all tags, including <style>, <script>, comments, etc.
                return value.replace(/<\s*script[^>]*>[\s\S]*?<\/script>/gi, '').replace(/<\s*style[^>]*>[\s\S]*?<\/style>/gi, '').replace(/<!--[\s\S]*?-->/g, '').replace(/<[^>]*>/g, '').trim();
            },
            // String
            format: (value, ...args) => {
                let index = 0;

                return String(value ?? '').replace(/%[%sd]/g, (matched) => {
                    if (matched === '%%') {
                        return '%';
                    }

                    let replacement = args[index++];

                    if (matched === '%d') {
                        let number = Number.parseInt(replacement, 10);

                        return Number.isNaN(number) ? '' : String(number);
                    }

                    return String(replacement ?? '');
                });
            },
            capitalize: (value) => {
                let chars = [...String(value ?? '')];

                if (!chars.length) {
                    return '';
                }

                return chars[0].toUpperCase() + chars.slice(1).join('').toLowerCase();
            },
            lower: (value) => {
                return value.toLowerCase();
            },
            upper: (value) => {
                return value.toUpperCase();
            },
            replace: (value, search, replace = '') => {
                const string = String(value ?? '');
                const searchString = String(search);
                const replaceString = String(replace);

                if (!searchString) {
                    return string;
                }

                return string.split(searchString).join(replaceString);
            },
            replace_first: (value, search, replace = '') => {
                const string = String(value ?? '');
                const searchString = String(search);
                const replaceString = String(replace);
                const index = string.indexOf(searchString);

                if (index === -1) {
                    return string;
                }

                return string.slice(0, index) + replaceString + string.slice(index + searchString.length);
            },
            replace_last: (value, search, replace = '') => {
                const string = String(value ?? '');
                const searchString = String(search);
                const replaceString = String(replace);
                const index = string.lastIndexOf(searchString);

                if (index === -1) {
                    return string;
                }

                return string.slice(0, index) + replaceString + string.slice(index + searchString.length);
            },
            remove: (value, search) => {
                const string = String(value ?? '');
                const searchString = String(search);

                if (!searchString) {
                    return string;
                }

                return string.split(searchString).join('');
            },
            remove_first: (value, search) => {
                const string = String(value ?? '');
                const searchString = String(search);
                const index = string.indexOf(searchString);

                if (index === -1) {
                    return string;
                }

                return string.slice(0, index) + string.slice(index + searchString.length);
            },
            remove_last: (value, search) => {
                const string = String(value ?? '');
                const searchString = String(search);
                const index = string.lastIndexOf(searchString);

                if (index === -1) {
                    return string;
                }

                return string.slice(0, index) + string.slice(index + searchString.length);
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
            truncatewords: (value, count = 15, end = '...') => {
                const string = String(value ?? '').trim();
                const limit = Number(count);

                if (!string || !Number.isFinite(limit) || limit <= 0) {
                    return '';
                }

                const words = string.split(/\s+/);

                if (words.length <= limit) {
                    return string;
                }

                return words.slice(0, limit).join(' ') + String(end ?? '');
            },
            wordcount: (value) => {
                let string = String(value).trim();

                if (!string) return 0;

                return string.split(/\s+/).length;
            },
            split: (value, separator) => {
                return value.split(separator);
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
                return Object.groupBy(value, (item) => item[type]);
            },
            sort: (value, key = null, direction = 'asc') => {
                const dir = direction === 'desc' ? -1 : 1;

                return [...value].sort((a, b) => {
                    let va = key ? a?.[key] : a;
                    let vb = key ? b?.[key] : b;

                    return (String(va ?? '').toLowerCase().localeCompare(String(vb ?? '').toLowerCase()) * dir);
                });
            },
            size: (value) => {
                return Array.isArray(value) || typeof value === 'string' ? value.length : 0;
            },
            // alias for size (Twig compat)
            length: (value) => {
                return this.filter.size(value);
            },
            offset: (value, offset) => {
                return value.slice(offset);
            },
            limit: (value, limit) => {
                return value.slice(0, limit);
            },
            sum: (value, amount = 0) => {
                return value.reduce((accumulator, currentValue) => accumulator + currentValue, amount);
            },
            push: (value, item) => {
                let copy = [...value];

                copy.push(item);

                return copy;
            },
            pop: (value) => {
                let copy = [...value];

                copy.pop();

                return copy;
            },
            shift: (value) => {
                let copy = [...value];

                copy.shift();

                return copy;
            },
            unshift: (value, item) => {
                let copy = [...value];

                copy.unshift(item);

                return copy;
            },
            slice: (value, start, end) => {
                if (typeof value !== 'string' && !Array.isArray(value)) return;

                return end !== undefined ? value.slice(start, end) : value.slice(start);
            },
            join: (value, seperator = ' ') => {
                return value.join(seperator);
            },
            array_to_sentence_string: (value, connector = 'and') => {
                let items = Array.isArray(value) ? value.map((item) => String(item ?? '')).filter((item) => item !== '') : [];

                let word = String(connector ?? 'and');

                if (items.length === 0) {
                    return '';
                }

                if (items.length === 1) {
                    return items[0];
                }

                if (items.length === 2) {
                    return `${items[0]} ${word} ${items[1]}`;
                }

                return `${items.slice(0, -1).join(', ')}, ${word} ${items[items.length - 1]}`;
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
                return value.filter((item) => item[key]);
            },
            reject: (value, key) => {
                return value.filter((item) => !item[key]);
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
            compact: (value) => {
                return value.filter((item) => item != null);
            },
            uniq: (value) => {
                return [...new Set(value)];
            },
            map: (value, property) => {
                return value.map((item) => item?.[property]);
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
            to_integer: (value) => {
                return parseInt(value, 10);
            },
            at_least: (value, minimum) => {
                const number = Number(value);

                return isNaN(number) ? minimum : Math.max(number, minimum);
            },
            at_most: (value, maximum) => {
                let number = Number(value);

                return isNaN(number) ? maximum : Math.min(number, maximum);
            },
            // URL
            urlencode: (value) => {
                return encodeURIComponent(value);
            },
            urldecode: (value) => {
                return decodeURIComponent(value);
            },
            base64_encode: (value) => {
                let bytes = new TextEncoder().encode(String(value ?? ''));
                let binary = Array.from(bytes, (byte) => String.fromCharCode(byte)).join('');

                return btoa(binary);
            },
            base64_decode: (value) => {
                try {
                    let binary = atob(String(value ?? ''));
                    let bytes = Uint8Array.from(binary, (char) => char.charCodeAt(0));

                    return new TextDecoder().decode(bytes);
                } catch {
                    return '';
                }
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
                file = this.path.get(namespace) + path.substr(namespace.length) + '.html';
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

            if (code != null && code !== '') {
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

        // Flush output produced by the final tag when there is no next token to consume it.
        let pending = stack[stack.length - 1];

        if (pending?.type === 'output' && pending.output != null) {
            output += pending.output;
        }

        return output;
    }

    tokenize(template) {
        let token = [];
        let index = 0;
        let match;

        let stack = [];

        let regex = /\{\{-?\s([\s\S]*?)\s-?\}\}|\{%-?\s([\s\S]*?)\s-?%}|\{#\s([\s\S]*?)\s#\}/g;

        while ((match = regex.exec(template)) !== null) {
            let [raw, output, tag, comment] = match;

            let line = template.substring(0, match.index).split(/\r\n|\r|\n/).length;

            // Grabs all the Text before the matched index.
            if (match.index > index) {
                token.push({
                    type: 'text',
                    raw: template.slice(index, match.index),
                });
            }

            // Capture output tag {{ my_var }}
            if (output !== undefined) {
                token.push({
                    type: 'output',
                    value: output,
                    raw: raw,
                    line: line,
                    column: index,
                });
            }

            // Capture statement tag {% if my_var %}
            if (tag !== undefined) {
                let command = tag.match(/^\S+/)[0].toLowerCase();

                // Handle Close Tags
                let top = stack[stack.length - 1];
                let forRef;

                if (top && this.openclose[top.type].includes(command)) {
                    token[top.index].end = token.length;

                    let popped = stack.pop();

                    if (command === 'else' && popped.type === 'for') {
                        forRef = popped.index;
                    } else if (
                        command === 'endfor'
                        && popped.type === 'else'
                        && popped.forRef !== undefined
                    ) {
                        token[popped.forRef].loopEnd = token.length;
                    }
                }

                // Handle Open Tags
                if (command in this.openclose) {
                    let entry = {
                        type: command,
                        index: token.length,
                    };

                    if (forRef !== undefined) {
                        entry.forRef = forRef;
                    }

                    stack.push(entry);
                }

                token.push({
                    type: 'tag',
                    tag: command,
                    value: tag,
                    raw: raw,
                    line: line,
                    column: index,
                });
            }

            // Records the last match
            index = regex.lastIndex;
        }

        // Grab any remaining template code since the last tag.
        if (index < template.length) {
            token.push({
                type: 'text',
                raw: template.slice(index),
            });
        }

        // Warning for unclosed blocks
        if (stack.length) {
            console.log(`[Template] Warning: ${stack.length} unclosed block(s): ${stack.map((value) => value.type)}`);
        }

        return token;
    }

    evaluate(expression, ctx) {
        if (!expression) return undefined;

        expression = expression.replaceAll(/"(?:[^"\\]|\\.)*"|'(?:[^'\\]|\\.)*'|\b(not)\s+|\b(and)\b|\b(or)\b/g, (match, not, and, or) => {
            if (not) {
                return '!';
            }

            if (and) {
                return '&&';
            }

            if (or) {
                return '||';
            }

            return match;
        });

        try {
            let func = new Function('data', `with(data) return (${expression});`);

            return func({ ...ctx });
        } catch (error) {
            console.log(`[Template] Warning: Evaluate error '${error}' in expression '${expression}'`);

            return undefined;
        }
    }

    truthy(value) {
        switch (typeof value) {
            case 'undefined':
                return false;
                break;
            case 'string':
                return value !== '';
                break;
            case 'number':
                return value >= 0;
                break;
            case 'boolean':
                return value;
                break;
            case 'object':
                if (value === null) {
                    return false;
                }

                if (Array.isArray(value)) {
                    return value.length > 0;
                }

                return Object.keys(value).length > 0 && value.constructor === Object;
                break;
        }

        return false;
    }

    describeToken(token) {
        let location = `line ${token.line} column ${token.column}`;

        return token.raw ? `${location}: ${token.raw}` : location;
    }

    /**
     * Apply chain of filters — now supports multiple comma-separated arguments per filter
     */
    parseFilter(value, expression = '', ctx) {
        if (expression == undefined) return value;

        let result = value;

        let filters = expression.indexOf('|') !== -1 ? expression.split('|').map((value) => value.trim()) : [expression];

        for (let filter of filters) {
            let match = filter.match(/^([^:]*):?\s?(.+)?$/);

            if (!match) return;

            let [, name, argument] = match;

            let args = [];

            if (argument) {
                let evaluatedArgs = this.evaluate('[' + argument + ']', ctx);

                if (Array.isArray(evaluatedArgs)) {
                    args = evaluatedArgs;
                }
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
        let match = token.value.match(/([^|]+?)\s*(?:\s*\|\s*(.+))?$/);

        if (!match) {
            console.log(`[Template] Invalid output ${this.describeToken(token)}`);
        }

        let [, name, filter] = match;

        let value = this.evaluate(name, ctx);

        // Apply Filters
        if (filter !== undefined) {
            value = this.parseFilter(value, filter, ctx);
        }

        value = this.filter.escape(value ?? '');

        // Trim whitespace
        if (token.raw[2] == '-') {
            value = value.trimStart();
        }

        if (token.raw.at(-3) == '-') {
            value = value.trimEnd();
        }

        return value;
    }

    /**
     * Handle assign and set statements
     *
     * assign var = expression | filter1 | filter2
     * set var = expression | filter1 | filter2
     */
    handleAssign(token, stack, ctx, index) {
        let match = token.value.match(/^(?:assign|set)\s(\w+)\s=\s([^|]+?)\s*(?:\s*\|\s*(.+))?$/);

        if (!match) {
            console.log(`[Template] Invalid '${token.tag}' syntax ${this.describeToken(token)}`);

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
            console.warn(`[Template] Invalid 'include' syntax ${this.describeToken(token)}`);

            return;
        }

        let output = await this.render(match[1], ctx);

        stack.push({
            type: 'output',
            output: output,
        });
    }

    handleEcho(token, stack, ctx, index) {
        let match = token.value.match(/^echo\s([^|]+?)\s*(?:\s*\|\s*(.+))?$/);

        if (!match) {
            console.log(`[Template] Invalid echo ${this.describeToken(token)}`);

            return;
        }

        let [, name, filter] = match;

        let value = this.evaluate(name, ctx);

        // Apply Filters
        if (filter !== undefined) {
            value = this.parseFilter(value, filter, ctx);
        }

        stack.push({
            type: 'output',
            output: value,
        });
    }

    /**
     * Handle If statement
     *
     * If var = expression | filter1 | filter2
     */
    handleIf(token, stack, ctx, index) {
        let match = token.value.match(/^if\s(.+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'if' syntax ${this.describeToken(token)}`);

            stack.push({
                type: 'if',
                active: true,
            });

            return token.end;
        }

        // Check to see if a previous tag is inactive.
        let active = this.evaluate(match[1], ctx);

        // Convert the output into bool.
        active = this.truthy(active);

        stack.push({
            type: 'if',
            active: active,
        });

        if (!active) return token.end;
    }

    /**
     * Handle If statement
     *
     * End If var == expression
     */
    handleEndIf(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'if') {
            console.log(`[Template] Unexpected 'if' tag ${this.describeToken(token)}`);

            return;
        }

        stack.pop();
    }

    handleElseIf(token, stack, ctx, index) {
        let match = token.value.match(/^elseif\s(.+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'elseif' syntax ${this.describeToken(token)}`);

            return;
        }

        let top = stack[stack.length - 1];

        if (!top || top.type !== 'if') {
            console.log(`[Template] Unexpected 'elseif' tag ${this.describeToken(token)}`);

            return;
        }

        if (top.active) return token.end;

        // If any previous not active tags then set current tag to false;
        let active = this.evaluate(match[1], ctx);

        // Convert the output into bool
        top.active = this.truthy(active);

        if (!top.active) {
            return token.end;
        }
    }

    handleElse(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || (top.type !== 'if' && top.type !== 'unless' && top.type !== 'case' && top.type !== 'for')) {
            console.log(`[Template] Unexpected 'else' tag ${this.describeToken(token)}`);

            return;
        }

        // If any previous not active tags then set current tag to false;
        if (top.active) return token.end;

        top.active = true;
    }

    /**
     * Handle Unless statement
     *
     * End If var = expression | filter1 | filter2
     */
    handleUnless(token, stack, ctx, index) {
        let match = token.value.match(/^unless\s(.+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'unless' syntax ${this.describeToken(token)}`);

            return;
        }

        // Check to see if a previous tag is inactive
        let active = this.evaluate(match[1], ctx);

        // Convert the output into bool
        active = !this.truthy(active);

        stack.push({
            type: 'unless',
            active: active,
        });

        if (!active) return token.end;
    }

    handleEndUnless(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'unless') {
            console.log(`[Template] Unexpected 'endunless' tag ${this.describeToken(token)}`);

            return;
        }

        stack.pop();
    }

    handleFor(token, stack, ctx, index) {
        let match = token.value.match(/^for\s(.*)\sin\s([^|]+?)\s*(?:\s*\|\s*(.+))?$/);

        if (!match) {
            console.log(`[Template] Invalid 'for' syntax ${this.describeToken(token)}`);

            return;
        }

        let [, name, key, filter] = match;

        // Match any global function
        let items = this.evaluate(key, ctx);

        if (items === null || typeof items !== 'object') {
            items = [];
        }

        // Apply Filters
        if (filter !== undefined) {
            items = this.parseFilter(items, filter, ctx);
        }

        let endIndex = token.loopEnd ?? token.end;

        stack.push({
            type: 'for',
            name: name,
            items: items,
            index: -1,
            start: index + 1,
            end: endIndex,
            active: items.length > 0,
            parent: { ...ctx },
        });

        return items.length > 0 ? endIndex : token.end;
    }

    handleEndFor(token, stack, ctx, index) {
        // If skip we don't want to run evaluate method.
        let top = stack[stack.length - 1];

        if (top == undefined || top.type !== 'for') {
            console.log(`[Template] Unexpected 'endfor' ${this.describeToken(token)}`);

            return;
        }

        top.index++;

        if (top.index < top.items.length) {
            // Restore parent context (prevents leakage)
            Object.assign(ctx, top.parent);

            let pos = top.name.indexOf(',');



            //console.log('ctx');
            //console.log('pos', pos);

            //console.log('top.index', top.items[top.index]);

            if (pos === -1) {
                ctx[top.name] = top.items[top.index]; // ← top.name (not top.name)
            } else {

                //let test = { ...top.items[top.index] }

                //ctx = Object.assign(ctx, top.items[top.index]); // ← top.name (not top.name)



                //console.log(ctx);

                //this.evaluate('[' + top.name]', ctx);

                //let keys = top.name.split(',');

                //for (let key of keys) {

                //}
            }



            ctx.loop = {
                index: top.index + 1,
                index0: top.index,
                first: top.index === 0,
                last: top.index === top.items.length - 1,
                length: top.items.length,
                rindex: top.items.length - top.index,
                rindex0: top.items.length - top.index - 1,
            };

            return top.start;
        }

        // Loop finished → cleanup
        stack.pop();

        delete ctx[top.name];
        delete ctx.loop;
    }

    handleContinue(token, stack, ctx, index) {
        // Ignore continue when not inside a loop to avoid corrupting the if/else stack.
        if (!stack.some((frame) => frame.type === 'for')) {
            return;
        }

        for (let i = stack.length - 1; i >= 0; i--) {
            if (stack[i].type == 'for') {
                return stack[i].end;
            }

            // Remove all tags before endfor loop.
            stack.pop();
        }
    }

    handleBreak(token, stack, ctx, index) {
        // Ignore break when not inside a loop to avoid corrupting the if/else stack.
        if (!stack.some((frame) => frame.type === 'for')) {
            return;
        }

        let top = {};

        for (let i = stack.length - 1; i >= 0; i--) {
            top = stack[i];

            if (top.type == 'for') break;

            // Remove all tags before endfor loop.
            stack.pop();
        }

        // Loop finished → cleanup
        stack.pop();

        delete ctx[top.name];
        delete ctx.loop;

        return top.end + 1;
    }

    handleCase(token, stack, ctx, index) {
        let match = token.value.match(/^case\s([\w.]+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'case' syntax ${this.describeToken(token)}`);

            return;
        }

        stack.push({
            type: 'case',
            value: match[1],
            active: false,
        });
    }

    handleWhen(token, stack, ctx, index) {
        let match = token.value.match(/^when\s(.+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'when' syntax ${this.describeToken(token)}`);

            return;
        }

        let top = stack[stack.length - 1];

        if (!top || top.type !== 'case') {
            console.log(`[Template] Unexpected 'when' tag ${this.describeToken(token)}`);

            return;
        }

        // Split if more than one item to compare
        if (!this.evaluate(`[${match[1]}].includes(${top.value})`, ctx)) return token.end;

        top.active = true;
    }

    handleEndCase(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'case') {
            console.log(`[Template] Unexpected 'case' tag ${this.describeToken(token)}`);

            return;
        }

        stack.pop();
    }

    handleCapture(token, stack, ctx, index) {
        let match = token.value.match(/^capture\s(.+)$/);

        if (!match) {
            console.warn(`[Template] Invalid 'capture' syntax ${this.describeToken(token)}`);

            return;
        }

        stack.push({
            type: 'capture',
            name: match[1],
            value: '',
        });
    }

    handleEndCapture(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'capture') {
            console.log(`[Template] Unexpected 'endcapture' tag ${this.describeToken(token)}`);

            return;
        }

        ctx[top.name] = top.value;

        stack.pop();
    }

    handleRaw(token, stack, ctx, index) {
        stack.push({
            type: 'raw',
            end: token.end,
        });
    }

    handleEndRaw(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'raw') {
            console.log(`[Template] Unexpected 'raw' tag ${this.describeToken(token)}`);

            return;
        }

        stack.pop();
    }

    handleComment(token, stack, ctx, index) {
        stack.push({ type: 'comment' });

        return token.end;
    }

    handleEndComment(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'comment') {
            console.log(`[Template] Unexpected 'comment' tag ${this.describeToken(token)}`);

            return;
        }

        stack.pop();
    }

    handleFilter(token, stack, ctx, index) {
        let match = token.value.match(/^filter\s(\w+)$/);

        if (!match) {
            console.log(`[Template] Invalid 'filter' syntax ${this.describeToken(token)}`);

            return;
        }

        stack.push({
            type: 'capture',
            filter: match[1],
            value: '',
        });
    }

    handleEndFilter(token, stack, ctx, index) {
        let top = stack[stack.length - 1];

        if (!top || top.type !== 'capture') {
            console.log(`[Template] Unexpected 'endfilter' tag ${this.describeToken(token)}`);

            return;
        }

        stack.pop();

        stack.push({
            type: 'output',
            output: this.parseFilter(top.value, top.filter, ctx),
        });
    }

    handleMacro(token, stack, ctx, index) {

    }

    handleEndMacro(token, stack, ctx, index) {

    }

    handleImport(token, stack, ctx, index) {

    }

    handleFrom(token, stack, ctx, index) {

    }

    handleCycle(token, stack, ctx, index) {
        let match = token.value.match(/^cycle\s(.*)/);

        if (!match) {
            console.warn(`[Template] Invalid 'cycle' syntax ${this.describeToken(token)}`);

            return;
        }

        let raw = match[1].trim();
        let group;
        let values;

        // Named group: {% cycle 'group': val1, val2 %}
        // \1 back reference ensures matching quotes around group name
        const groupMatch = raw.match(/^(['"])(.*?)\1\s*:(.*)/s);

        if (groupMatch) {
            group = groupMatch[2];
            values = this.evaluate('[' + groupMatch[3] + ']', ctx);
        } else {
            // Unnamed: raw text becomes the group key
            values = this.evaluate('[' + raw + ']', ctx);
            group = raw;
        }

        // Nothing to cycle
        if (!Array.isArray(values) || values.length === 0) {
            return;
        }

        // Init cycle state
        if (!ctx._cycle) {
            ctx._cycle = {};
        }

        // Start at index -1 so first increment lands on 0
        if (!ctx._cycle[group]) {
            ctx._cycle[group] = { index: -1 };
        }

        const state = ctx._cycle[group];

        state.index = (state.index + 1) % values.length;

        stack.push({
            type: 'output',
            output: values[state.index],
        });
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new CurlyTag();
        }

        return this.instance;
    }
}

const curlytag = CurlyTag.getInstance();

export { curlytag };
