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

	style() {
		return `
			:host {
				display: inline-block;
			}
			button[disabled] {
				cursor: not-allowed;
			}
		`;
	}

	async render() {
        return `<button type="submit" @ref="element" @click="handleClick" class="btn btn-primary"${this.disabled ? ' disabled' : ''}>${this.html}</button>`;
    }

	handleClick(e) {
		this.emit('[click]');
	}

	handleSubmitting(name, value) {
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

	handleDisabled(name, value) {
		this.element.toggleAttribute('disabled', value !== null);
	}
}); 