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

	set submitting(value) {
		this.toggleAttribute('submitting', value == true);
	}

	get disabled() {
		return this.hasAttribute('disabled');
	}

	set disabled(value) {
		this.toggleAttribute('disabled', value == true);
	}

	constructor() {
		super();
	
		this.template = this.querySelector('template');
	}

	async render() {
		return `<form @ref="element" @submit="onSubmit">${this.template ? this.template.innerHTML : ``}</form>`;
	}
	
	onSubmit(e) {
		e.preventDefault();

		this.emit('custom-submit');
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

	handleSubmitting(name, value) {
        if (this.submitter) this.submitter.submitting = value !== null;
    }
	
	handleDisabled(name, value) {
		if (this.submitter) this.submitter.disabled = value !== null;
	}

	setSuccess(success) { 
		this.alert.prepend(`<ui-alert type="success">${success}</ui-alert>`);

		let inputs = this.getInputs();

		for (let input of inputs) {
			//input.setError('');
		}
	}

	reset() {
		let inputs = this.getInputs();

		for (let input of inputs) {
			input.reset();
		}
	}

	setError(error) {
		if (typeof error === 'string') {
			this.alert.innerHTML = `<ui-alert type="error">${error}</ui-alert>`;
						
			return;
		}

		if (error.warning) this.alert.innerHTML = `<ui-alert type="warning">${error.warning}</ui-alert>`;

		let inputs = this.getInputs();

		for (let input of inputs) {
			console.log(`Resetting input: ${input.name}`);
			
			input.reset();

			if (input.name in error) {
				input.setError(error[input.name]);
			} else {
				input.setError('');
			}
		}
	}
}); 