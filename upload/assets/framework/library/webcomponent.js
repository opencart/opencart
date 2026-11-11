import { global } from './global.js';
import { loader } from './loader.js';

/**
 * WebComponent
 * -------------
 * A minimal base class for building web components with:
 *   - Shadow DOM, or light DOM with `static shadow = false`
 *   - render() + styles() + stylesheets()
 *   - Automatic ref/event binding via Binder (@ref / @click / @submit, etc...)
 *   - Reactive state via `states()` and this.state
 *   - Lifecycle hooks: handleConnect, handleDisconnect, handleAttribute, handleState
 *
 * Named handlers are called when they exist:
 *   - attribute "count" changes  ->  attributeCount(name, value_new, value_old)
 *   - state "count" changes      ->  stateCount(value, state)
 *
 * Usage:
 *
 *   class MyCounter extends WebComponent {
 *       static observedAttributes = ['count'];
 *
 *       styles() {
 *           return `
 *              button { font-size: 1rem; }
 *              span { font-weight: bold; margin-left: 0.5rem; }
 *           `;
 *       }
 *
 *       render() {
 *           return `<button @click="increment">+1</button><span @ref="display">${this.state.get('count')}</span>`;
 *       }
 *
 *       handleConnect() {
 *           this.state.set('count', Number(this.getAttribute('count')) || 0);
 *       }
 *
 *       increment() {
 *           this.state.set('count', this.state.get('count') + 1);
 *       }
 *
 *       stateCount(value) {
 *           this.display.textContent = value;
 *       }
 *   }
 *
 *   customElements.define('my-counter', MyCounter);
 */
export class WebComponent extends HTMLElement {
	static shadow = true;
	static formAssociated = false;
	#refs = new Map();
	#listeners = []; // track for clean teardown
	#global;
	#bindings = [];
	#renders = 0;

	static get observedAttributes() {
		return [];
	}

	constructor() {
		super();

		// Attach Shadow or use light DOM based on the static property
		this.root = this.constructor.shadow ? this.attachShadow({ mode: 'open' }) : this;
		
		// Global
		this.#global = global;
		
		// Attach Internals
		if (this.constructor.formAssociated) this.internal = this.attachInternals();

		// Set attribute change handlers for existing attributes
		for (let attribute of this.attributes) {
			let key = 'handle' + attribute.name.split('-').map(part => part.charAt(0).toUpperCase() + part.slice(1)).join('');

			if (typeof this[key] === 'function') this.addEventListener('[' + attribute.name + ']', this[key].bind(this));
		}
	}
	
	/** Override: CSS files to adopt into the shadow root, as names stylesheet.resolve() knows. */
	stylesheets() {
		return [];
	}

	/** Override: return a CSS string scoped to this component's shadow root. */
	styles() {
		return ``;
	}
	
	template() {
		return ``;
	}

	async connectedCallback() {
		if (this.constructor.shadow) {
			for (let stylesheet of this.stylesheets()) {
				this.root.adoptedStyleSheets.push(await loader.stylesheet(stylesheet));
			}
		}

		if (!this.#renders) {
			await this.update();

			this.#renders++;
		}
		
		if (typeof this.handleConnect === 'function') {
			await this.handleConnect();
		}
	}

	async disconnectedCallback() {
		this.#renders = 0;
		
		this.#refs = new Map();
		this.#removeListeners();
		
		if (typeof this.handleDisconnect === 'function') {
			await this.handleDisconnect();
		}
	}

	async adoptedCallback() {
		await this.update();
	}

	async attributeChangedCallback(name, value_old, value_new) {
		if (!this.#renders || value_old === value_new) return;

		this.emit('[' + name + ']', {
			value_old: value_old,
			value_new: value_new
		});
	}
	
	async update() {
		if (typeof this.render !== 'function') return;
		
		this.#refs = new Map();
		this.#removeListeners();

		this.root.innerHTML = await this.render();
		
		//if (!this.constructor.shadow) return;

		this.root.appendChild(`<style>${this.styles()}</style>`);

		// Bind elements with special attributes (@ or :) within the shadow root
		let carries = (node) => node.getAttributeNames().some((name) => name.startsWith('@') || name.startsWith(':'));

		const walker = document.createTreeWalker(this.root, NodeFilter.SHOW_ELEMENT, {
			acceptNode: node => carries(node) ? NodeFilter.FILTER_ACCEPT : NodeFilter.FILTER_SKIP // skip this node, still walk its children
		});

		let node = walker.nextNode();

		while (node) {
			this.#bind(node);

			node = walker.nextNode();
		}
	}
	
	emit(event, data = {}) {
		// Default handler for attribute changes
		this.dispatchEvent(new CustomEvent(event, {
			detail: { ...data },
			bubbles: true,
			composed: true,
			cancelable: true
		}));
	}
	
    #bind(element) {
        let names = element.getAttributeNames().filter((name) => name.startsWith('@') || name.startsWith(':'));

        names.forEach((name) => {
            let is_global = name.startsWith(':');
            let [ key, ...options ] = name.slice(1).split('.');
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
            this.#global.set(key, element);

            this.#bindings.push({ key, element });

            return;
        }

        if (key in HTMLElement.prototype) {
            throw new Error(`WebComponent: ref "${key}" on ${element.tagName} is the name of a native HTMLElement property, so this.${key} would not be the element; use another name`);
        }

        this.#refs.set(key, element);

        // Attach the getter property to the web component
        if (!(key in this)) {
            Object.defineProperty(this, key, {
                get: () => this.#refs.get(key),
                configurable: true,
            });
        }
	}
	
	#addListener(element, event, method, options, is_global) {
        let handler = is_global ? this.#global.getListener(method) : this[method];

        if (typeof handler !== 'function') {
            throw new Error(`WebComponent: no method "${method}" found in ${is_global ? 'Global' : 'Host'} for ${element.tagName} event "${event}"`);
        }

        // Bind the handler to the host if it's not global
		let listener = is_global ? handler : handler.bind(this);
		
        let option = {};

        let keys = options.filter(value => ['capture', 'once', 'passive'].includes(value));

        for (let key of keys) option[key] = true;

        element.addEventListener(event, listener, option);

		// Store the listener so it can be removed later
        this.#listeners.push({ element, event, listener, capture: !!option.capture });
	}
	
	/** Remove all attached event listeners (call in disconnectedCallback). */
	#removeListeners() {
		this.#listeners.forEach(({ element, event, listener, capture }) => element.removeEventListener(event, listener, capture));
		this.#listeners = [];

		// Only clear a global ref if it still points at the element *this*
		// instance set — avoids wiping out a ref another instance re-registered.
		this.#bindings.forEach(({ key, element }) => this.#global.delete(key, element));
		this.#bindings = [];
	}
}