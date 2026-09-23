/**
 * Component
 * ---------------
 * Registers a mapping of custom element tag names to the files that
 * define them, then loads (dynamically imports) and registers each
 * component only when it's actually needed — on demand, in a batch, or
 * automatically as matching tags show up in the DOM.
 *
 * Works with plain `customElements.define` classes as well as
 * BaseComponent subclasses (it calls the class's own static `define()`
 * when present, so BaseComponent's double-registration guard still
 * applies).
 *
 * Usage:
 *
 *   ComponentLoader.registerAll({
 *     'my-counter': '/components/my-counter.js',
 *     'my-card': '/components/my-card.js',
 *   });
 *
 *   // Load one component explicitly (e.g. before using it programmatically):
 *   await ComponentLoader.load('my-counter');
 *
 *   // Load several up front:
 *   await ComponentLoader.loadAll(['my-counter', 'my-card']);
 *
 *   // Or scan the DOM once for tags that are already present:
 *   await ComponentLoader.scan();
 *
 *   // Or watch the DOM and auto-load registered tags as they're inserted
 *   // (covers content added later, e.g. by routing or innerHTML swaps):
 *   ComponentLoader.observe();
 *
 * Each registered file's default export is the component class:
 *
 *   // /components/my-counter.js
 *   export default class MyCounter extends BaseComponent { ... }
 *
 * No `BaseComponent.define(...)` call is needed in the file itself —
 * the loader registers the tag for you once the module resolves.
 */
class Component {
    instance;

    // tagName -> { path, promise, loaded }
    static data = new Map();
    static _observer = null;
    directory = '';

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

    async load(path) {
        if (this.cache.has(path)) {
            return this.cache.get(path);
        }

        let file = this.directory + path;
        let namespace = '';
        let parts = path.split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (this.path.has(namespace)) {
                file = this.path.get(namespace) + path.substr(namespace.length);
            }
        }

        let component = await import(config.config_path + path);


        if (typeof component.defualt !== 'function') {

            let object = await response.json();

            this.cache.set(path, object);

            return this.cache.get(path);
        } else {
            console.log('Could not load component file ' + path);
        }

        return undefined;
    }

    /** Register a single tag → file mapping. No-op if already registered. */
    static register(tag, path) {
        if (this.data.has(tag)) return;

        this.data.set(tag, { path, promise: null, loaded: false });
    }

    /** Register several tag → file mappings at once: `{ tagName: path }`. */
    static registerAll(data) {
        Object.entries(data).forEach(([tag, path]) => this.register(tag, path));
    }

    /** Whether a tag has already been defined as a custom element. */
    static isLoaded(tag) {
        return !!customElements.get(tag);
    }

    /** Resolves once the given tag has been defined (loaded or not by this loader). */
    static whenLoaded(tag) {
        return customElements.whenDefined(tag);
    }

    /**
     * Loads and registers a single tag's component file. Safe to call
     * repeatedly — concurrent calls share the same in-flight import, and
     * an already-defined element resolves immediately without re-importing.
     * @param {string} tag
     *
     * @returns {Promise<CustomElementConstructor>}
     */
    static async load(tag) {
        const existing = customElements.get(tag);
        if (existing) return existing;

        const entry = this.data.get(tag);

        if (!entry) {
            throw new Error(`ComponentLoader: no file registered for <${tag}>`);
        }
        if (entry.promise) return entry.promise;

        entry.promise = import(/* @vite-ignore */ entry.path).then((module) => {
            const ComponentClass = module.default;

            if (typeof ComponentClass !== 'function') {
                throw new Error(`ComponentLoader: "${entry.path}" has no default class export`);
            }

            if (!customElements.get(tag)) {
                if (typeof ComponentClass.define === 'function') {
                    ComponentClass.define(tag, ComponentClass); // BaseComponent-style
                } else {
                    customElements.define(tag, ComponentClass);
                }
            }

            entry.loaded = true;
            return ComponentClass;
        })
        .catch((err) => {
            entry.promise = null; // allow a retry on next load() call
            throw err;
        });

        return entry.promise;
    }

    /** Loads multiple tags in parallel. */
    static loadAll(tags) {
        return Promise.all(tags.map((tag) => this.load(tag)));
    }

    /**
     * Scans a root for elements whose tags are registered but not yet
     * defined, and loads them. Useful for a one-time pass over
     * server-rendered or already-present markup.
     * @param {Document|Element|ShadowRoot} [root]
     */
    static scan(root = document) {
        const pending = new Set();
        root.querySelectorAll('*').forEach((el) => {
            const tag = el.tagName.toLowerCase();
            if (this.data.has(tag) && !customElements.get(tag)) {
                pending.add(tag);
            }
        });
        return this.loadAll([...pending]);
    }

    /**
     * Watches `root` for inserted elements matching a registered tag and
     * loads them automatically as they appear (covers dynamically added
     * content, not just what's present at call time — `scan()` runs once
     * up front to also catch existing markup).
     * @param {Element|Document} [root]
     */
    static observe(root = document.body) {
        if (this._observer) return;

        this.scan(root);

        this._observer = new MutationObserver((mutations) => {
            for (const mutation of mutations) {
                mutation.addedNodes.forEach((node) => {
                    if (node.nodeType !== Node.ELEMENT_NODE) return;
                    this._checkNode(node);
                    node.querySelectorAll?.('*').forEach((child) => this._checkNode(child));
                });
            }
        });

        this._observer.observe(root, { childList: true, subtree: true });
    }

    static _checkNode(el) {
        const tag = el.tagName.toLowerCase();
        if (this._registry.has(tag) && !customElements.get(tag)) {
            this.load(tag);
        }
    }

    /** Stops the MutationObserver started by `observe()`. */
    static disconnect() {
        this._observer?.disconnect();
        this._observer = null;
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Component();
        }

        return this.instance;
    }
}

const component = Component.getInstance();

export { component };
