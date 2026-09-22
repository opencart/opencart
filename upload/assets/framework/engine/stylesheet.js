/**
 * StylesheetImporter
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
class Stylesheet {
    // href -> Promise<CSSStyleSheet>  (or Promise<string> css text on fallback)
    static cache = new Map();

    static get supportsConstructableStylesheets() {
        return (typeof CSSStyleSheet !== 'undefined' && 'replaceSync' in CSSStyleSheet.prototype && 'adoptedStyleSheets' in Document.prototype);
    }

    /**
     * Fetches + compiles a stylesheet from a URL, caching the in-flight
     * promise so concurrent/duplicate requests share one fetch.
     * @param {string} href
     * @returns {Promise<CSSStyleSheet|string>}
     */
    static load(href) {
        if (this.cache.has(href)) {
            return this.cache.get(href);
        }

        const promise = fetch(href)
        .then((res) => {
            if (!res.ok) {
                throw new Error(`Stylesheet: failed to fetch "${href}" (${res.status})`);
            }
            return res.text();
        })
        .then((cssText) => this._compile(cssText));

        this._cache.set(href, promise);
        return promise;
    }

    /**
     * Compiles raw CSS text into a (cached, deduped) CSSStyleSheet.
     * Useful for inline/shared CSS strings, not just fetched files.
     * @param {string} cssText
     * @returns {CSSStyleSheet|string}
     */
    static _compile(cssText) {
        if (this.supportsConstructableStylesheets) {
            const sheet = new CSSStyleSheet();
            sheet.replaceSync(cssText);
            return sheet;
        }
        // Fallback: caller will inject a <style> tag with this raw text.
        return cssText;
    }

    /**
     * Compiles and caches raw CSS text by a cache key (so repeated calls
     * with the same key reuse the same sheet, just like `load()` does for URLs).
     * @param {string} key - a unique identifier for this CSS text
     * @param {string} cssText
     */
    static loadText(key, cssText) {
        if (!this._cache.has(key)) {
            this._cache.set(key, Promise.resolve(this._compile(cssText)));
        }
        return this._cache.get(key);
    }

    /**
     * Loads a list of stylesheet URLs and adopts them into a shadow root,
     * preserving any stylesheets already adopted.
     * @param {ShadowRoot} root
     * @param {string[]} hrefs
     */
    static async adopt(root, hrefs = []) {
        if (!hrefs.length) return;

        const results = await Promise.all(hrefs.map((href) => this.load(href)));

        this._apply(root, results);
    }

    /**
     * Same as `adopt`, but for raw CSS text entries instead of URLs.
     * Each entry may be a string (auto-keyed by its own content) or
     * `{ key, css }` to control cache/dedup identity explicitly.
     * @param {ShadowRoot} root
     * @param {(string|{key: string, css: string})[]} entries
     */
    static async adoptText(root, entries = []) {
        if (!entries.length) return;

        const results = await Promise.all(
            entries.map((entry) => {
                const isObj = typeof entry === 'object' && entry !== null;
                const key = isObj ? entry.key : entry;
                const css = isObj ? entry.css : entry;
                return this.loadText(key, css);
            })
        );
        this._apply(root, results);
    }

    static _apply(root, compiled) {
        const sheets = compiled.filter((c) => c instanceof CSSStyleSheet);
        const rawTexts = compiled.filter((c) => typeof c === 'string');

        if (sheets.length) {
            const existing = root.adoptedStyleSheets || [];
            // Avoid re-adding a sheet that's already adopted.
            const merged = [...existing, ...sheets.filter((s) => !existing.includes(s))];

            root.adoptedStyleSheets = merged;
        }

        rawTexts.forEach((cssText) => {
            const style = document.createElement('style');

            style.textContent = cssText;

            root.appendChild(style);
        });
    }

    /** Clears the cache — mainly useful for tests or hot-reload scenarios. */
    static clearCache() {
        this.cache.clear();
    }
}

export default StylesheetImporter;
// If not using ES modules: module.exports = StylesheetImporter;