import { Binder } from './binder.js';
import { Global } from './global.js';
import { State } from './state.js';
import { stylesheet } from './stylesheet.js';

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
 *       states() {
 *           return { count: 0 };
 *       }
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
const camel = (name) => name.replace(/^[^a-zA-Z0-9]+/, '').split(/[-_:.\s]+/).filter(Boolean).map((p) => p.charAt(0).toUpperCase() + p.slice(1)).join('');

export class WebComponent extends HTMLElement {
	static shadow = true;
    static formAssociated = false;
	
    #renders = 0;
    // Make sure reactive attributes don't work until after render has been called.
    #rendered = false;

    static get observedAttributes() {
        return [];
    }

    constructor() {
        super();

        // Attach Shadow or use light DOM based on the static property
        this.root = this.constructor.shadow ? this.attachShadow({ mode: 'open' }) : this;

        // State
        this.state = new State(this.states(), this.stateChangedCallback.bind(this));
		
		//  Binder
		this.binder = new Binder(this);
		
		// Global
		this.global = Global;
		
        // Attach Internals
        if (this.constructor.formAssociated) this.internal = this.attachInternals();

        this.#style();
    }
	
    states() {
        return {};
    }

    /** Override: CSS files to adopt into the shadow root, as names stylesheet.resolve() knows. */
    stylesheets() {
        return [
            'catalog/stylesheet.css',
            'fontawesome/css/all.css'
        ];
    }

    /** Override: return a CSS string scoped to this component's shadow root. */
    styles() {
        return '';
    }

    // Styles go on the shadow root once, from the constructor. A light DOM component uses the
    // page's CSS, since anything adopted into the document would apply to the whole page.
    // The files go first and styles() after them, so the component's own rules win.
    async #style() {
        if (!this.shadow) return;

        await stylesheet.adopt(this.root, this.stylesheets());

        const css = this.styles();

        if (css) await stylesheet.adoptText(this.root, [css]);
	}
	
	async connectedCallback() {
		if (typeof this.render !== 'function') return;
	
		if (!this.#rendered) {			
			this.root.innerHTML = await this.render();

			this.binder.refresh(this.root);
		}

        if (typeof this.handleConnect === 'function') {
            await this.handleConnect();
		}
		
		this.#rendered = true;
    }

    async update() {
        if (typeof this.render !== 'function') return;
		
		this.root.innerHTML = await this.render();

		this.binder.refresh(this.root);
	}
	
    async disconnectedCallback() {
        this.#rendered = false;

        if (this.state) {
            this.state.destroy();
        }

		if (this.binder) { 
			this.binder.destroy();
		}

        if (typeof this.handleDisconnect === 'function') {
            await this.handleDisconnect();
        }
    }

    async adoptedCallback() {
        await this.update();
    }

    async attributeChangedCallback(name, value_old, value_new) {
        if (!this.#rendered || value_old === value_new) return;

        // Default handler for attribute changes
        if (typeof this.handleAttribute == 'function') await this.handleAttribute(name, value_new, value_old);
    }

    async stateChangedCallback(keys, state) {
        if (!this.#rendered || !keys.length) return;

		// Call the handleState method if it exists to handle state changes.
        if (typeof this.handleState == 'function') await this.handleState(keys, state);
	}
}
