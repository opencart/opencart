import { global } from './global.js';
/**
 * Binder
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
 * Prefixing the attribute name with `:` instead of `@` binds against a
 * static registry on the ElementBinder class itself — shared across
 * every component instance — instead of the local host/refs:
 *
 *   `:ref="appHeader"`    → stored in ElementBinder.globalRefs.appHeader,
 *                           readable anywhere via ElementBinder.getGlobalRef('appHeader')
 *   `:click="trackClick"` → calls a function registered with
 *                           ElementBinder.registerListener('trackClick', fn)
 *                           instead of looking for the method on the host
 *
 * Register global listeners once, e.g. at app startup:
 *
 *   ElementBinder.registerListener('trackClick', (event) => {
 *     analytics.log('click', event.target.id);
 *   });
 *   // or register several at once:
 *   ElementBinder.registerListener({ trackClick, openModal });
 *
 * Then any component's template can use it without defining a local method:
 *
 *   template() {
 *     return `<button :click="trackClick">Buy</button>`;
 *   }
 *
 * `@`- and `:`-prefixed attribute names are valid HTML and parse
 * natively in the browser — no template compiler needed. Traversal uses
 * a TreeWalker (filtered to elements that actually carry one of these
 * attributes) rather than `querySelectorAll('*')`, so only qualifying
 * nodes are visited.
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
    constructor(root, host) {
        this.root = root;
        this.host = host || root.host || root;
        this.refs = new Map();
        this.listeners = []; // track for clean teardown
        this.global = [];

        // Attach events based on elements that have data-bind attributes
        this.walk(this.root);
    }

    walk(root) {
        const walker = document.createTreeWalker(this.root, NodeFilter.SHOW_ELEMENT, {
            acceptNode: node => node.getAttributeNames().some(name => name.startsWith('@') || name.startsWith(':')) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_SKIP // skip this node, still walk its children
        });

        let node = walker.nextNode();

        while (node) {
            this.bind(node);

            node = walker.nextNode();
        }
    }

    bind(element) {
        let names = element.getAttributeNames().filter(name => name.startsWith('@') || name.startsWith(':'));

        names.forEach(name => {
            let is_global = name.startsWith(':');
            let key = name.slice(1);
            let value = element.getAttribute(name);

            if (key === 'ref') {
                this.ref(element, value, is_global);
            } else {
                this.event(element, key, value, is_global);
            }
        });
    }

    ref(element, name, is_global) {
        if (!name) return;

        if (is_global) {
            global.refs.set(name, element);

            this.global.push({ name, element });

            return;
        }

        this.refs.set(name, element);

        // Attach the getter property to the web component
        if (this.host && !(name in this.host)) {
            Object.defineProperty(this.host, name, {
                get: () => this.refs.get(name),
                configurable: true,
            });
        }
    }

    event(element, event, method, is_global) {
        let handler = is_global ? global.getListeners(method) : this.host[method];

        if (typeof handler !== 'function') {
            let scope = is_global ? 'Global' : 'host';
            let prefix = is_global ? ':' : '@';

            console.warn(`ElementBinder: no method "${method}" found in ${scope} for event "${prefix}${event}"`);

            return;
        }

        // Global handlers run as plain functions (no implicit `this`);
        // host handlers are bound to the component instance as before.
        let listener = is_global ? handler : handler.bind(this.host);

        element.addEventListener(event, listener);

        this.listeners.push({ element, event, listener });
    }

    /** Get a bound element by its data-ref name. */
    get(name) {
        return this.refs.get(name);
    }

    has(key) {
        return this.refs.has(key);
    }

    refresh() {
        this.destroy();
        this.refs = new Map();
        this.global = new Map();
        this.listeners = [];
        this.walk(this.root);
    }

    /** Remove all attached event listeners (call in disconnectedCallback). */
    destroy() {
        this.listeners.forEach(({ element, event, listener }) => {
            element.removeEventListener(event, listener);
        });

        this.listeners = [];

        // Only clear a global ref if it still points at the element *this*
        // instance set — avoids wiping out a ref another instance re-registered.
        this.global.forEach(({ name, element }) => {
            global.clearRef(name, element);
        });

        this.global = [];
    }
}