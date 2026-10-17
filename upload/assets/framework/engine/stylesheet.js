// @import "a.css";   @import 'a.css';   @import url(a.css);   @import url("a.css");
// Groups: 1-3 are the three url() forms, 4-5 the two string forms, 6 anything after
// the target (a media query, layer(), supports()...) up to the semicolon.
const IMPORT = /@import\s+(?:url\(\s*(?:"([^"]*)"|'([^']*)'|([^)\s]*))\s*\)|"([^"]*)"|'([^']*)')\s*([^;]*);/gi;

// url(a.png)   url("a.png")   url('a.png')
const URL_FUNCTION = /url\(\s*(?:"([^"]*)"|'([^']*)'|([^)\s"'][^)]*?))\s*\)/gi;

// Left as written: data: and other schemes, fragments (#id), and //protocol-relative.
const KEEP = /^(?:#|\/\/|[a-z][a-z0-9+.-]*:)/i;

// Not relative to the directory: a scheme, or a leading /
const ABSOLUTE = /^(?:[a-z][a-z0-9+.-]*:|\/)/i;

// A font declaration has no nested braces, so the first } ends it.
const FONT_FACE = /@font-face\s*\{[^}]*\}/gi;

/**
 * Stylesheet
 * ----------
 * Loads CSS files (or raw CSS text) as constructable stylesheets and adopts them
 * into a shadow root or the document. A file is fetched and parsed once, and the
 * same CSSStyleSheet is shared by every root that needs it: shared, deduplicated
 * rules instead of a <style> block per instance. Where constructable stylesheets
 * are not available, a <style> element is used instead.
 *
 *   import { stylesheet } from './stylesheet.js';
 *
 *   stylesheet.addPath('app/stylesheet/');                              // where plain names live
 *   stylesheet.addPath('fontawesome/css/', 'assets/fontawesome/css/');  // a prefix that lives elsewhere
 *
 *   await stylesheet.adopt(this.shadow, ['cms.css', 'fontawesome/css/all.css']);
 *   stylesheet.release(this.shadow, ['cms.css']);                      // and take one off again
 *
 *   await stylesheet.adoptText(this.shadow, ['.x { color: red; }']);   // CSS you have as a string
 *
 * What it does with a file, because a constructable stylesheet has no address of its own:
 *
 *   - relative url(...) references are made absolute against the CSS file, so fonts and
 *     images still resolve. (Left alone: data:, #fragment, http(s):, //host.)
 *   - plain @import rules are fetched and put in front of the file's own rules, because the
 *     browser would drop them. Imports inside imports work, and a cycle is cut. An @import
 *     with a condition (a media query, layer(), supports()) cannot be inlined, so it is
 *     skipped with a warning.
 *   - @font-face rules are also put on the document. Browsers register web fonts from the
 *     document's own styles, not from a sheet in a shadow root, so an icon font would
 *     otherwise never load. Turn it off with `stylesheet.hoist_fonts = false`.
 *
 * Loading is cached by URL and shared by every instance (the static `cache`), and
 * simultaneous requests share one fetch. A file that cannot be loaded (a 404, a network
 * error) is reported and skipped, the others are still applied, and it is not cached, so
 * asking again tries again. `adopt()` tells you what loaded and what did not.
 *
 * Only the cache and the single instance are static, because they have to be shared;
 * everything else is an instance method. What is only for the class's own use (reading and
 * rewriting a file, tracking what is on a root, the fonts) is #private.
 */
class Stylesheet {
    static #instance = null;

    // absolute URL, or 'text:' + key -> Promise<CSSStyleSheet | string>. Shared by every instance.
    static cache = new Map();

    // absolute URL -> the @font-face rules found in that file, as text.
    static #fonts = new Map();

    // root -> Map<key, CSSStyleSheet | <style> element>: what this instance put there.
    #applied = new WeakMap();

    constructor() {
        this.directory = '';
        this.path = new Map();

        // Whether the @font-face rules of what is adopted into a shadow root also go on the document.
        this.hoist_fonts = true;
    }

    get cache() {
        return Stylesheet.cache;
    }

    get supported() {
        return typeof CSSStyleSheet !== 'undefined' && 'replaceSync' in CSSStyleSheet.prototype && 'adoptedStyleSheets' in Document.prototype;
    }

    /**
     * addPath('app/stylesheet/')                                plain names are looked up here
     * addPath('fontawesome/css/', 'assets/fontawesome/css/')    names starting with the first live under the second
     */
    addPath(namespace, path = '') {
        if (!path) {
            this.directory = namespace;
        } else {
            this.path.set(namespace, path);
        }
    }

    /** The absolute URL a name stands for. A URL or a /path is used as it is. */
    resolve(path) {
        let file = path;

        if (!ABSOLUTE.test(path)) {
            // The longest namespace that the name starts with wins.
            const namespace = [...this.path.keys()]
                .filter(key => path.startsWith(key))
                .sort((a, b) => b.length - a.length)[0];

            file = namespace === undefined ? this.directory + path : this.path.get(namespace) + path.slice(namespace.length);
        }

        return new URL(file, document.baseURI).href;
    }

    /**
     * Fetches and compiles one file: a CSSStyleSheet, or the CSS text where constructable
     * stylesheets are not available. The in-flight promise is cached, so duplicate requests
     * share one fetch. Rejects if the file cannot be loaded.
     */
    fetch(path) {
        const url = this.resolve(path);

        if (!Stylesheet.cache.has(url)) {
            const promise = this.#read(url).then(css => {
                this.#remember(url, css);

                return this.compile(css);
            });

            // Not kept when it failed, so that the next call tries again.
            promise.catch(() => Stylesheet.cache.delete(url));

            Stylesheet.cache.set(url, promise);
        }

        return Stylesheet.cache.get(url);
    }

    /** Loads files ahead of time so that adopt() finds them ready. Never rejects. */
    preload(paths) {
        return Promise.allSettled(paths.map(path => this.fetch(path)));
    }

    // The file's CSS with its @imports inlined and its url()s made absolute.
    async #read(url, chain = [], memo = new Map()) {
        if (chain.includes(url)) return '';

        if (chain.length >= 10) {
            throw new Error(`Stylesheet: @import chain is too deep at "${url}"`);
        }

        // The same file reached twice (a diamond of imports) is fetched once.
        if (!memo.has(url)) {
            memo.set(url, fetch(url).then(response => {
                if (!response.ok) {
                    throw new Error(`Stylesheet: could not load "${url}" (${response.status})`);
                }

                return response.text();
            }));
        }

        let css = await memo.get(url);

        const imports = [];

        css = css.replace(IMPORT, (match, quoted_url, single_url, bare_url, quoted, single, condition) => {
            if (condition.trim()) {
                console.warn(`Stylesheet: "${match.trim()}" in "${url}" has a condition, which cannot be inlined, so it is skipped`);

                return '';
            }

            imports.push(new URL(quoted_url ?? single_url ?? bare_url ?? quoted ?? single, url).href);

            return '';
        });

        const imported = await Promise.all(imports.map(href => this.#read(href, [...chain, url], memo)));

        return imported.join('\n') + this.#absolutize(css, url);
    }

    // Makes every relative url(...) absolute, against the file the CSS came from.
    #absolutize(css, base) {
        return css.replace(URL_FUNCTION, (match, quoted, single, bare) => {
            const target = (quoted ?? single ?? bare ?? '').trim();

            if (!target || KEEP.test(target)) return match;

            return `url("${new URL(target, base).href}")`;
        });
    }

    // Keeps the @font-face rules of a file, for putting on the document.
    #remember(url, css) {
        const fonts = css.match(FONT_FACE);

        if (fonts) {
            Stylesheet.#fonts.set(url, fonts.join('\n'));
        } else {
            Stylesheet.#fonts.delete(url);
        }
    }

    /**
     * Compiles CSS text into a CSSStyleSheet, or returns the text unchanged where
     * constructable stylesheets are not available.
     */
    compile(text) {
        if (!this.supported) return text;

        const sheet = new CSSStyleSheet();

        sheet.replaceSync(text);

        return sheet;
    }

    /**
     * Loads the files and adopts them into a root (a ShadowRoot, or the document), in the
     * order given and after anything already there. A file that fails to load is reported
     * on the console and skipped; the others are still applied. A file already on the root
     * is not added again.
     *
     * @returns {Promise<{ loaded: string[], failed: string[] }>}
     */
    async adopt(root, hrefs = []) {
        const loaded = [];
        const failed = [];

        if (!hrefs.length) return { loaded, failed };

        const results = await Promise.allSettled(hrefs.map(href => this.fetch(href)));

        const entries = [];

        results.forEach((result, index) => {
            if (result.status === 'rejected') {
                console.error(result.reason);

                failed.push(hrefs[index]);

                return;
            }

            loaded.push(hrefs[index]);

            entries.push([this.resolve(hrefs[index]), result.value]);
        });

        this.apply(root, entries);
        this.#hoist(root, entries.map(([url]) => url));

        return { loaded, failed };
    }

    /**
     * Compiles and caches CSS text under a key, so repeated calls with the same key reuse
     * the same sheet, as fetch() does for files.
     */
    addText(key, text) {
        const name = 'text:' + key;

        if (!Stylesheet.cache.has(name)) {
            Stylesheet.cache.set(name, Promise.resolve(this.compile(text)));
        }

        return Stylesheet.cache.get(name);
    }

    /**
     * Like adopt(), for CSS you already have as text. An entry is a string (keyed by its own
     * content) or `{ key, css }` to say what counts as the same sheet.
     */
    async adoptText(root, entries = []) {
        if (!entries.length) return;

        const keyed = entries.map(entry => {
            const object = typeof entry === 'object' && entry !== null;

            return ['text:' + (object ? entry.key : entry), this.addText(object ? entry.key : entry, object ? entry.css : entry)];
        });

        const compiled = await Promise.all(keyed.map(([, promise]) => promise));

        this.apply(root, keyed.map(([key], index) => [key, compiled[index]]));
    }

    /**
     * Puts [key, sheet-or-text] pairs on a root. What is already there under the same key is
     * left alone, and so is every sheet the root already had.
     */
    apply(root, entries) {
        const applied = this.#applied.get(root) ?? new Map();

        this.#applied.set(root, applied);

        const sheets = [];

        for (const [key, compiled] of entries) {
            if (applied.has(key)) continue;

            if (typeof compiled === 'string') {
                // No constructable stylesheets: a <style> element does the same job.
                const style = document.createElement('style');

                style.textContent = compiled;

                style.setAttribute('data-stylesheet', key.slice(0, 80));

                (root.nodeType === 9 ? document.head : root).append(style);

                applied.set(key, style);
            } else {
                applied.set(key, compiled);

                sheets.push(compiled);
            }
        }

        if (sheets.length) {
            const existing = root.adoptedStyleSheets ?? [];

            root.adoptedStyleSheets = [...existing, ...sheets.filter(sheet => !existing.includes(sheet))];
        }
    }

    // Web fonts are registered from the document's own styles, so the @font-face rules of what
    // went into a shadow root go on the document too, once.
    #hoist(root, urls) {
        if (!this.hoist_fonts || root.nodeType === 9) return;

        const entries = [];

        for (const url of urls) {
            const css = Stylesheet.#fonts.get(url);
            const key = 'fonts:' + url;

            if (!css || this.#applied.get(document)?.has(key)) continue;

            entries.push([key, this.compile(css)]);
        }

        if (entries.length) this.apply(document, entries);
    }

    /** Takes files this instance put on a root off again. With no list, everything it put there. */
    release(root, hrefs = null) {
        const applied = this.#applied.get(root);

        if (!applied) return;

        this.#remove(root, applied, hrefs ? hrefs.map(href => this.resolve(href)) : [...applied.keys()]);
    }

    /** Like release(), for text entries (strings, or { key, css }). With no list, all of them. */
    releaseText(root, entries = null) {
        const applied = this.#applied.get(root);

        if (!applied) return;

        const keys = entries
            ? entries.map(entry => 'text:' + (typeof entry === 'object' && entry !== null ? entry.key : entry))
            : [...applied.keys()].filter(key => key.startsWith('text:'));

        this.#remove(root, applied, keys);
    }

    #remove(root, applied, keys) {
        for (const key of keys) {
            const item = applied.get(key);

            if (!item) continue;

            if (item.nodeType === 1) {
                item.remove();
            } else {
                root.adoptedStyleSheets = root.adoptedStyleSheets.filter(sheet => sheet !== item);
            }

            applied.delete(key);
        }
    }

    /**
     * Fetches a file again and updates it where it is already in use, which is what
     * hot reload needs: every root that adopted the sheet shows the new rules at once,
     * and the document's copy of its fonts is updated too. Where <style> elements are used
     * instead, the ones already on the page are not updated, only what is adopted from now on.
     * Rejects, and changes nothing, if the file cannot be loaded.
     */
    async reload(path) {
        const url = this.resolve(path);
        const css = await this.#read(url);

        this.#remember(url, css);

        const current = Stylesheet.cache.has(url) ? await Stylesheet.cache.get(url).catch(() => null) : null;

        if (current && typeof current !== 'string') {
            current.replaceSync(css);
        } else {
            Stylesheet.cache.set(url, Promise.resolve(this.compile(css)));
        }

        const fonts = this.#applied.get(document)?.get('fonts:' + url);

        if (fonts && typeof fonts !== 'string' && fonts.nodeType !== 1) fonts.replaceSync(Stylesheet.#fonts.get(url) ?? '');
    }

    /** Forgets everything loaded, for every instance. Mainly for tests. */
    clear() {
        Stylesheet.cache.clear();
        Stylesheet.#fonts.clear();
    }

    static getInstance() {
        if (!Stylesheet.#instance) {
            Stylesheet.#instance = new Stylesheet();
        }

        return Stylesheet.#instance;
    }
}

const stylesheet = Stylesheet.getInstance();

export { stylesheet };