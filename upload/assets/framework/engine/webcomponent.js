import { Global } from './global.js';
import { Binder } from './binder.js';
import { Stylesheet } from './stylesheet.js';

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
 */
export class WebComponent extends HTMLElement {
	static shadow = true;
	static observedAttributes = [];
	static formAssociated = false;
	#renders = 0;
	#listeners = [];

	constructor() {
		super();

		// Attach Shadow or use light DOM based on the static property
		this.root = this.constructor.shadow ? this.attachShadow({ mode: 'open' }) : this;
	
		// Stylesheet
		this.stylesheet = new Stylesheet(this.root);
		
		// Global
		this.global = Global;
		
		// Binder
		this.binder = new Binder(this);

		// Attach Internals
		if (this.constructor.formAssociated) this.internal = this.attachInternals();
		
		let attributes = this.constructor.observedAttributes.filter(attribute => !attribute.startsWith('@') && !attribute.startsWith(':'));
		
		for (let name of attributes) {
			let key = 'handle' + name.split(/[_-]/).map(part => part.charAt(0).toUpperCase() + part.slice(1)).join(``);

			this.addListener(name, (e) => {	
				if (typeof this[key] === 'function') this[key](e.detail.name, e.detail.value_new, e.detail.value_old);
			});
		}
	}
	
	/** Override: CSS files to adopt into the shadow root, as names stylesheet.resolve() knows. */
	stylesheets() {
		return [
			'stylesheet/stylesheet.css',
			'fontawesome/css/all.css'
		];
	}

	/** Override: return a CSS string scoped to this component's shadow root. */
	styles() {
		return ``;
	}
	
	async connectedCallback() {
		for (let { name, listener } of this.#listeners) {
			this.addEventListener(`[${name}]`, listener.bind(this));
		}
		
		if (this.constructor.shadow) {
			this.stylesheet.addStylesheet(this.stylesheets());
			this.stylesheet.addStyle(this.styles());
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
		
		console.log('disconnected');

		for (let { name, listener } of this.#listeners) { 
			this.removeEventListener(`[${name}]`, listener.bind(this));
		}

		this.binder.destroy();
		
		if (typeof this.handleDisconnect === 'function') {
			await this.handleDisconnect();
		}
	}

	async adoptedCallback() {
		await this.update();
	}

	async attributeChangedCallback(name, value_old, value_new) {
		if (!(this.#renders) || value_old === value_new) return;
		
		this.emit(`[${name}]`, {
			name: name,
			value_new: value_new,
			value_old: value_old
		});
	}
	
	async update() {
		if (typeof this.render !== 'function') return;
		
		this.binder.destroy();

		const template = await this.render();
		
		this.root.innerHTML = `${template}`;
		
		this.binder.walk(this.root);
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

	addListener(name, listener) { 
		this.addEventListener(name, listener);

		this.#listeners.push({ name, listener });
	}
}