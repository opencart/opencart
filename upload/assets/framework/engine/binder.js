import { Global } from './global.js';

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
 *   3. Attributes — `@[open]="onToggle"` calls the method when the element's
 *                  `open` attribute changes: `<dialog @[open]="onToggle">`.
 *                  Native elements do not announce attribute changes, so the
 *                  Binder watches the attributes that were asked for and
 *                  sends the same `[open]` event that a WebComponent sends
 *                  for its observed attributes, with the old and new value:
 *
 *                    onToggle(e) {
 *                        e.detail.value_old;   // null if it was not set
 *                        e.detail.value_new;   // null if it was removed, '' for a bare attribute
 *                    }
 *
 *                  It works for any attribute (`@[hidden]`, `@[class]`,
 *                  `@[aria-expanded]`), with `:` as well, and a custom
 *                  element that already sends the event for that attribute
 *                  (it is in its observedAttributes) is left to do so, so
 *                  the handler is not called twice. What differs from those
 *                  events is the timing: a native element is watched with a
 *                  MutationObserver, so the handler runs at the end of the
 *                  current task, not at the moment of the change, and
 *                  several changes made together arrive one after another,
 *                  each with its own old and new value. A change that sets
 *                  the value it already had is not reported. Changes made
 *                  while the render is being bound (by @show, say) are not.
 *
 * Prefixing the attribute name with `:` instead of `@` binds against a
 * static registry on the Binder class itself — shared across
 * every component instance — instead of the local host/refs:
 *
 *   `:ref="appHeader"`    → stored in Binder.globalRefs.appHeader,
 *                           readable anywhere via Binder.getGlobalRef('appHeader')
 *   `:click="trackClick"` → calls a function registered with
 *                           Binder.registerListener('trackClick', fn)
 *                           instead of looking for the method on the host
 *
 * Register global listeners once, e.g. at app startup:
 *
 *   Binder.registerListener('trackClick', (event) => {
 *     analytics.log('click', event.target.id);
 *   });
 *   // or register several at once:
 *   Binder.registerListener({ trackClick, openModal });
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
 * class MyWidget extends HTMLElement {
 *     constructor() {
 *         super();
 *         this.attachShadow({ mode: 'open' });
 *         this.shadowRoot.innerHTML = `
 *           <button @ref="btn" @click="handleClick">Click me</button>
 *           <span @ref="label"></span>
 *         `;
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
 * }
 */
// What one render binds: its refs, its listeners (to take them off again), and the global
// refs it registered, { key, element } (to clear them again), and its @show bindings,
// { element, key, values } (to toggle them when the state changes), and the attributes it
// watches for @[name] events: watches are the ones asked for and not watched yet, observed is
// element -> the names being watched, and observer is the MutationObserver doing it.
const blank = () => ({
	refs: new Map(),
	listeners: [],
	bindings: [],
	observed: new Map(),
	observer: null
});

export class Binder {
    #host;
    #refs = new Map();
    #listeners = []; // track for clean teardown
    #bindings = []; // { name, el } set by this instance, for cleanup

    constructor(host) {
        this.#host = host;
    }

    /** Binds a fresh render. The old bindings are removed first, listeners and global refs included. */
    refresh(root) {
        this.destroy();
        this.#refs = new Map();

        this.#walk(root);
	}
	
	#walk(root) {
        let carries = (node) => node.getAttributeNames().some((name) => name.startsWith('@') || name.startsWith(':'));

        const walker = document.createTreeWalker(root, NodeFilter.SHOW_ELEMENT, {
            acceptNode: node => carries(node) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_SKIP // skip this node, still walk its children
        });

        let node = walker.nextNode();

        while (node) {
            this.bind(node);

            node = walker.nextNode();
        }
    }

    bind(element) {
        let names = element.getAttributeNames().filter((name) => name.startsWith('@') || name.startsWith(':'));

        names.forEach((name) => {
            let is_global = name.startsWith(':');
            let [key, ...options] = name.slice(1).split('.');
            let value = element.getAttribute(name);

			if (key === 'ref') {
				this.#ref(element, value, is_global);
			} else {
                this.#addListener(element, key, value, options, is_global);
            }

            element.removeAttribute(name);
        });
    }

    #ref(element, key, is_global) {
        if (!key) return;

        if (is_global) {
            Global.set(key, element);

            this.#bindings.push({ key, element });

            return;
        }

        if (key in HTMLElement.prototype) {
            throw new Error(`Binder: ref "${key}" on ${element.tagName} is the name of a native HTMLElement property, so this.${key} would not be the element; use another name`);
        }

        this.#refs.set(key, element);

        // Attach the getter property to the web component
        if (this.#host && !(key in this.#host)) {
            Object.defineProperty(this.#host, key, {
                get: () => this.#refs.get(key),
                configurable: true,
            });
        }
    }

    #addListener(element, event, method, options, is_global) {
        let handler = is_global ? Global.getListener(method) : this.#host[method];

        if (typeof handler !== 'function') {
            throw new Error(`Binder: no method "${method}" found in ${is_global ? 'Global' : 'Host'} for ${element.tagName} event "${event}"`);
        }

        let option = {};

        let keys = options.filter(value => ['capture', 'once', 'passive'].includes(value));

        for (let key of keys) option[key] = true;

        // Bind the handler to the host if it's not global
        let listener = is_global ? handler : handler.bind(this.#host);

        element.addEventListener(event, listener, option);

        this.#listeners.push({ element, event, listener, capture: !!option.capture });
    }
    
	removeListener(element, event, listener, capture = false) {
        element.removeEventListener(event, listener, capture);
    }

    /** Get a bound element by its data-ref name. */
    get(key) {
        return this.#refs.get(key);
    }

    has(key) {
        return this.#refs.has(key);
    }

    /** Remove all attached event listeners (call in disconnectedCallback). */
    destroy() {
        this.#listeners.forEach(({ element, event, listener, capture }) => element.removeEventListener(event, listener, capture));
        this.#listeners = [];

        // Only clear a global ref if it still points at the element *this*
        // instance set — avoids wiping out a ref another instance re-registered.
        this.#bindings.forEach(({ key, element }) => Global.delete(key, element));
        this.#bindings = [];
    }
}
