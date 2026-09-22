/**
 * ElementBinder
 * -------------
 * Scans a web component's shadow root (or any root node) for two kinds
 * of attributes and wires them up automatically:
 *
 *   1. Refs      — `@ref="name"` exposes the element as `this.refs.name`
 *                  and as a getter on the host (e.g. `this.name`).
 *   2. Events    — `@click="handleClick"` (or any `@eventname="method"`)
 *                  attaches a listener that calls the matching method
 *                  on the host. Modifiers can be chained with dots:
 *                  `@click.prevent.stop="handleClick"` calls
 *                  `preventDefault()`/`stopPropagation()` before running
 *                  the handler. `@click.once="handleClick"` removes
 *                  itself after firing once.
 *
 * A `global:` prefix on either kind binds against a static registry on
 * the ElementBinder class itself, shared across every component instance,
 * instead of the local host/refs:
 *
 *   `@ref="global:appHeader"`   → stored in ElementBinder.globalRefs.appHeader,
 *                                 readable anywhere via ElementBinder.getGlobalRef('appHeader')
 *   `@click="global:trackClick"` → calls a function registered with
 *                                 ElementBinder.registerMethod('trackClick', fn)
 *                                 instead of looking for the method on the host
 *
 * Register global methods once, e.g. at app startup:
 *
 *   ElementBinder.registerMethod('trackClick', (event) => {
 *     analytics.log('click', event.target.id);
 *   });
 *   // or register several at once:
 *   ElementBinder.registerMethod({ trackClick, openModal });
 *
 * Then any component's template can use it without defining a local method:
 *
 *   template() {
 *     return `<button @click="global:trackClick">Buy</button>`;
 *   }
 *
 * `@`-prefixed attribute names are valid HTML and parse natively in the
 * browser — no template compiler needed. Traversal uses a TreeWalker
 * (filtered to elements that actually carry an `@` attribute) rather
 * than `querySelectorAll('*')`, so only qualifying nodes are visited
 * and instantiated as match candidates.
 *
 * Usage inside a custom element:
 *
 *   class MyWidget extends HTMLElement {
 *     constructor() {
 *       super();
 *       this.attachShadow({ mode: 'open' });
 *       this.shadowRoot.innerHTML = `
 *         <button @ref="btn" @click="handleClick">Click me</button>
 *         <span @ref="label"></span>
 *       `;
 *     }
 *
 *     connectedCallback() {
 *       this.binder = new ElementBinder(this.shadowRoot, this);
 *       this.label.textContent = 'Ready';
 *     }
 *
 *     handleClick() {
 *       this.label.textContent = 'Clicked!';
 *     }
 *
 *     disconnectedCallback() {
 *       this.binder.destroy();
 *     }
 *   }
 */
export class Binder {
    #root;
    #host;
    #refs;
    #listeners;

    constructor(root, host) {
        this.#root = root;
        this.#host = host || root.host || root;
        this.#refs = new Map();
        this.#listeners = []; // track for clean teardown

        // Attach events based on elements that have data-bind attributes
        this.walk(this.#root);
    }

    walk(root) {
        const walker = document.createTreeWalker(this.#root, NodeFilter.SHOW_ELEMENT, {
            acceptNode: node => node.getAttributeNames().some(name => name.startsWith('@')) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_SKIP // skip this node, still walk its children
        });

        let node = walker.nextNode();

        while (node) {
            this.bind(node);

            node = walker.nextNode();
        }
    }

    bind(element) {
        let names = element.getAttributeNames().filter(name => name.startsWith('@'));

        names.forEach(name => {
            let key = name.slice(1);
            let value = element.getAttribute(name);

            if (key === 'ref') {
                this.ref(element, value);
            } else {
                this.event(element, key, value);
            }
        });
    }

    ref(element, name) {
        if (!name) return;

        this.#refs.set(name, element);

        // Attach the getter property to the web component
        if (this.#host && !(name in this.#host)) {
            Object.defineProperty(this.#host, name, {
                get: () => this.#refs.get(name),
                configurable: true,
            });
        }

        element.removeAttribute('@ref');
    }

    event(element, event, method) {
        const handler = method && this.#host[method];

        if (typeof handler !== 'function') {
            console.warn(`ElementBinder: no method "${method}" found on host for event "@${event}"`);

            return;
        }

        const listener = handler.bind(this.#host);

        element.addEventListener(event, listener);

        this.#listeners.push({ element, event, listener });

        element.removeAttribute(`@${event}`);
    }

    /** Get a bound element by its data-ref name. */
    get(name) {
        return this.#refs.get(name);
    }

    has(key) {
        return this.#refs.has(key);
    }

    refresh() {
        this.destroy();
        this.#refs = new Map();
        this.#listeners = [];
        this.walk(this.#root);
    }

    /** Remove all attached event listeners (call in disconnectedCallback). */
    destroy() {
        this.#listeners.forEach(({ element, event, listener }) => {
            element.removeEventListener(event, listener);
        });

        this.#listeners = [];
    }
}