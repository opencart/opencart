import { Global } from './global.js';

export class Binder { 
	#root;
	#refs = new Map();
	#listeners = []; // track for clean teardown
	#bindings = [];

	constructor(root) {
		// Attach Shadow or use light DOM based on the static property
		this.#root = root;
	}

	walk(root) {
		// Bind elements with special attributes (@ or :) within the shadow root
		let carries = (node) => node.getAttributeNames().some(name => name.startsWith('@') || name.startsWith(':'));

		const walker = document.createTreeWalker(root, NodeFilter.SHOW_ELEMENT, {
			acceptNode: node => carries(node) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_SKIP // skip this node, still walk its children
		});

		let node = walker.nextNode();

		while (node) {
			let names = node.getAttributeNames().filter(name => name.startsWith('@') || name.startsWith(':'));

			names.forEach(name => {
				let is_global = name.startsWith(':');
				let [ key, ...options ] = name.slice(1).split('.');
				let value = node.getAttribute(name);

				if (key === 'ref') {
					this.addRef(node, value, is_global);
				} else {
					this.addListener(node, key, value, options, is_global);
				}

				node.removeAttribute(name);
			});

			node = walker.nextNode();
		}
	}

    addRef(element, key, is_global) {
        if (!key) return;

        if (is_global) {
            Global.set(key, element);

            this.#bindings.push({ key, element });

            return;
        }

        if (key in HTMLElement.prototype) {
            throw new Error(`WebComponent: ref "${key}" on ${element.tagName} is the name of a native HTMLElement property, so this.${key} would not be the element; use another name`);
        }

        this.#refs.set(key, element);

        // Attach the getter property to the web component
        if (!(key in this.#root)) {
            Object.defineProperty(this.#root, key, {
                get: () => this.#refs.get(key),
                configurable: true,
            });
        }
	}
	
	addListener(element, event, method, options = [], is_global = false) {
        let handler = is_global ? Global.getListener(method) : this.#root[method];

        if (typeof handler !== 'function') {
            throw new Error(`WebComponent: no method "${method}" found in ${is_global ? 'Global' : 'Host'} for ${element.tagName} event "${event}"`);
        }

        // Bind the handler to the host if it's not global
		let listener = is_global ? handler : handler.bind(this.#root);
		
        let option = {};

        let keys = options.filter(value => ['capture', 'once', 'passive'].includes(value));

        for (let key of keys) option[key] = true;

        element.addEventListener(event, listener, option);

		// Store the listener so it can be removed later
        this.#listeners.push({ element, event, listener, capture: !!option.capture });
	}
	
	/** Remove all attached event listeners (call in disconnectedCallback). */
	destroy() {
		this.#refs = new Map();

		// Remove Listeners
		this.#listeners.forEach(({ element, event, listener, capture }) => element.removeEventListener(event, listener, capture));
		this.#listeners = [];

		// Only clear a global ref if it still points at the element *this*
		// instance set — avoids wiping out a ref another instance re-registered.
		this.#bindings.forEach(({ key, element }) => Global.delete(key, element));
		this.#bindings = [];
	}
}