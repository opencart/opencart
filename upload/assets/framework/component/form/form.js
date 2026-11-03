import { WebComponent } from '../../engine.js';

customElements.define('form-submit', class extends WebComponent {
	static formAssociated = true;
	success = '';
	error = [];
	disabled = false;
	
	constructor() {
		super();
	
		this.template = this.querySelector('template');
		this.disabled = this.hasAttribute('disabled');
	}

	async render() {
		return `<form @ref="element" @submit="handleSubmit">${this.template.innerHTML}</form>`;
	}

	handleConnect() {
		if (this.submitter) this.submitter.addEventListener('[click]', () => this.submit());
	}

	getInputs() {
		return this.root.querySelectorAll('[name]');
	}

	getData() {		
		let form = new FormData();

		for (let input of this.getInputs()) form.append(input.name, input.value);

		return form;
	}

	async handleSubmit(e) {
		e.preventDefault();

		console.log('Submitting form with data:', this.getData());

		this.dispatchEvent(new CustomEvent('[submit]', { detail: { form: this.getData() } }));
	}

	submit() { 
		this.element.requestSubmit();
	}

	setSubmitting(value) {
        if (this.submitter) this.submitter.toggleAttribute('submitting', value);
    }
	
	setSuccess(handle) { 
		this.handleSuccess = handle;
	}

	setError(error) {
		if (error.warning) {
			this.alert.innerHTML = '<ui-alert type="warning">' + error.warning + '</ui-alert>';
		}

		let inputs = this.getInputs();

		for (let input of inputs) {
			if (input.name in error) { 
				input.setError(error[input.name]);
			} else {
				input.setError('');
			}
		}
	}

	handleDisabled(value) {
		if (this.submitter) this.submitter.toggleAttribute('disabled', value !== null);
	}
}); 