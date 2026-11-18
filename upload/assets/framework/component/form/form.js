import { WebComponent } from '../../framework.js';

customElements.define('form-submit', class extends WebComponent {
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
	
		this.template = this.querySelector('template');
	}

	async render() {
		return `<form @ref="element" @submit="handleSubmit">${this.template ? this.template.innerHTML : ``}</form>`;
	}

	getInputs() {
		return this.root.querySelectorAll('[name]');
	}

	getInput(name) {
		return this.root.querySelector(`[name="${name}"]`);
	}
	
	getData() {		
		let form = new FormData();

		for (let input of this.getInputs()) form.append(input.name, input.value);

		return form;
	}

	submit() { 
		this.element.requestSubmit();
	}

	handleSubmit(e) {
		e.preventDefault();

		this.emit('custom-submit');
	}

	handleSubmitting(name, value) {
        if (this.submitter) this.submitter.toggleAttribute('submitting', value !== null);
    }
	
	handleDisabled(name, value) {
		if (this.submitter) this.submitter.toggleAttribute('disabled', value !== null);
	}

	setSuccess(success) { 
		this.alert.innerHTML = `<ui-alert type="success">${success}</ui-alert>`;

		let inputs = this.getInputs();

		for (let input of inputs) {
			//input.setError('');
		}
	}

	setError(error) {
		if (typeof error === 'string') {
			this.alert.innerHTML = `<ui-alert type="error">${error}</ui-alert>`;
			
			let inputs = this.getInputs();
			
			//for (let input of inputs) input.setError(error);
			
			return;
		}

		if (error.warning) this.alert.innerHTML = `<ui-alert type="warning">${error.warning}</ui-alert>`;

		let inputs = this.getInputs();

		for (let input of inputs) {
			if (input.name in error) {
				input.setError(error[input.name]);
			} else {
				input.setError('');
			}
		}
	}
}); 