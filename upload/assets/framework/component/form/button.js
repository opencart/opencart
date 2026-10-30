import { WebComponent } from '../../engine.js';

customElements.define('button-submit', class extends WebComponent {
	static shadow = true;
	static formAssociated = true;
    static observedAttributes = [
        'submitting',
        'disabled'
	];
	listeners = [];

    /* Will only manage states listed here */
    states() {
        return {
            submitting: false,
            disabled: this.hasAttribute('disabled')
        };
    }

	constructor() { 
		super();

        this.html = this.innerHTML;
	}

	addListener(listener) {
		this.listeners.push(listener);
	}

    render() {
        return `<button type="submit" @ref="element" class="btn btn-primary"${this.state.get('disabled') ? ' disabled' : ''}>${this.html}</button>`;
    }

	handleConnect() {
		for (let listener of this.listeners) this.element.addEventListener('click', listener);
	}

	handleDisconnect() {
		for (let listener of this.listeners) this.element.removeEventListener('click', listener);
	}

	// Handle state changes for the 'submitting' attribute
	handleState(keys, state) {
		if (keys.includes('submitting')) this.handleSubmitting(state.submitting);
		if (keys.includes('disabled')) this.handleDisabled(state.disabled);
	}

	handleAttribute() { 
		this.state.set('disabled', this.hasAttribute('disabled'));
	}

	handleSubmitting(value) {
		if (value) {
			this.element.style.width = this.offsetWidth + 'px';
			this.element.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin"></i>';
			this.element.toggleAttribute('disabled', true);
		} else {
			this.element.style.width = '';
			this.element.innerHTML = this.html;
			this.element.toggleAttribute('disabled', false);
		}
	}

	handleDisabled(value) {
		this.element.toggleAttribute('disabled', value);
	}
}); 