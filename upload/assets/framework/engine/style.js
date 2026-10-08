/**
 * Stylesheet Importer
 * -------------------
 * Loads external CSS files (or raw CSS text) as Constructable Stylesheets
 * and adopts them into a shadow root. Each unique href/text is fetched and
 * parsed only once, then the same CSSStyleSheet object is reused (adopted)
 * across every component instance that needs it — this is the whole point
 * of adoptedStyleSheets: shared, deduped style rules instead of a fresh
 * <style> block per instance.
 *
 * Falls back to injecting a plain <style>/<link> tag into the root for
 * environments without Constructable Stylesheet support.
 *
 * Usage with BaseComponent:
 *
 *   class MyCard extends BaseComponent {
 *     static get stylesheets() {
 *       return ['/styles/tokens.css', '/styles/card.css'];
 *     }
 *
 *     template() {
 *       return `<div data-ref="body">Hello</div>`;
 *     }
 *   }
 *
 * BaseComponent (see integration note below) calls
 * `StylesheetImporter.adopt(this.shadowRoot, this.constructor.stylesheets)`
 * automatically during render().
 *
 * Standalone usage:
 *
 *   await StylesheetImporter.adopt(shadowRoot, ['/styles/base.css']);
 *   await Stylesheet.adoptText(shadowRoot, ['.x { color: red; }']);
 */
export class Style {
    static instance = null;
    static cache = new Map();

    // href -> Promise<CSSStyleSheet>  (or Promise<string> css text on fallback)
    constructor() {
        this.directory = '';
        this.path = new Map();
        this.cache = new Map();
    }

    addPath(namespace, path = '') {
        if (!path) {
            this.directory = namespace;
        } else {
            this.path.set(namespace, path);
        }
    }

    /**
     * Fetches + compiles a stylesheet from a URL, caching the in-flight
     * promise so concurrent/duplicate requests share one fetch.
     * @param {string} href
     * @returns {Promise<CSSStyleSheet|string>}
     */
    fetch(path) {
        if (this.cache.has(path)) return this.cache.get(path);

        let file = this.directory + path;
        let namespace = '';
        let parts = path.replace(/\/+$/, '').split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (this.path.has(namespace + '/')) {
                file = this.path.get(namespace + '/') + path.substr(namespace.length);
            }
        }

        let promise = fetch(file).then(response => {
            if (response.status !== 200) {
                console.log(`Stylesheet: failed to fetch "${path}" (${response.status})`);
            }

            return response.text();
        }).then(this.compile);

        this.cache.set(path, promise);

        return promise;
    }

    /**
     * Compiles raw CSS text into a (cached, deduped) CSSStyleSheet.
     * Useful for inline/shared CSS strings, not just fetched files.
     * @param {string} cssText
     * @returns {CSSStyleSheet|string}
     */
    compile(text) {
        const sheet = new CSSStyleSheet();

        sheet.replaceSync(text);

        return sheet;
    }

    /**
     * Loads a list of stylesheet URLs and adopts them into a shadow root,
     * preserving any stylesheets already adopted.
     * @param {ShadowRoot} root
     * @param {string[]} hrefs
     */
    async adopt(root, hrefs = []) {
        if (!hrefs.length) return;

        const result = await Promise.all(hrefs.map(href => this.fetch(href)));

        this.apply(root, result);
    }

    /**
     * Compiles and caches raw CSS text by a cache key (so repeated calls
     * with the same key reuse the same sheet, just like `load()` does for URLs).
     * @param {string} key - a unique identifier for this CSS text
     * @param {string} cssText
     */
    addText(key, text) {
        if (!this.cache.has(key)) {
            this.cache.set(key, Promise.resolve(this.compile(text)));
        }

        return this.cache.get(key);
    }

    /**
     * Same as `adopt`, but for raw CSS text entries instead of URLs.
     * Each entry may be a string (auto-keyed by its own content) or
     * `{ key, css }` to control cache/dedup identity explicitly.
     * @param {ShadowRoot} root
     * @param {(string|{key: string, css: string})[]} entries
     */
    async adoptText(root, entries = []) {
        if (!entries.length) return;

        let promise = entries.map((entry) => {
            const isObj = typeof entry === 'object' && entry !== null;
            const key = isObj ? entry.key : entry;
            const css = isObj ? entry.css : entry;

            return this.addText(key, css);
        });

        const results = await Promise.all(promise);

        this.apply(root, results);
    }

    apply(root, compiled) {
        const sheets = compiled.filter((c) => c instanceof CSSStyleSheet);
        const raw = compiled.filter((c) => typeof c === 'string');

        if (sheets.length) {
            const existing = root.adoptedStyleSheets || [];
            // Avoid re-adding a sheet that's already adopted.
            root.adoptedStyleSheets = [ ...existing, ...sheets.filter(sheet => !existing.includes(sheet))];
        }

        raw.forEach(text => {
            const style = document.createElement('style');

            style.textContent = text;

            root.appendChild(style);
        });
    }

    /** Clears the cache — mainly useful for tests or hot-reload scenarios. */
    clear() {
        this.cache.clear();
    }

    static getInstance() {
        if (!Style.instance) {
            Style.instance = new Style();
        }

        return Style.instance;
    }
}

const stylesheet = Style.getInstance();

export { stylesheet };