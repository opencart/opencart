import { WebComponent } from '../../engine.js';

customElements.define('button-submit', class extends WebComponent {
	static formAssociated = true;
    static observedAttributes = [
        'submitting',
        'disabled'
	];

	get submitting() {
		return this.hasAttribute('submitting');
	}

	get disabled() {
		return this.hasAttribute('disabled');
	}
	
	constructor() { 
		super();

        this.html = this.innerHTML;
	}

	setSubmitting(value) { 
		this.submitting = value;
	}

    async render() {
        return `<button type="submit" @ref="element" @click="handleClick" class="btn btn-primary"${this.disabled ? ' disabled' : ''}>${this.html}</button>`;
    }

	// Handle state changes for the 'submitting' attribute
	handleClick(e) {
		this.dispatchEvent(new CustomEvent('[click]', {}));
	}

	handleSubmitting(value) {
		if (value !== null) {
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
		this.update();
	}
}); 