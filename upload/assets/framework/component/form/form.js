import { WebComponent } from '../../engine.js';

customElements.define('form-submit', class extends WebComponent {
	static formAssociated = true;
	
	/* Will only manage states listed here */
	states() {
		return {
			submitting: false,
			success: '',
			error: [],
			disabled: false
		};
	}

	constructor() {
		super();
	
		this.template = this.querySelector('template');
	}

	addListener(listener) {
		this.element.addEventListener('submit', listener);
	}

	getInputs() {
		return this.root.querySelectorAll('[name]');
	}

	getData() {		
		let form = new FormData();

		for (let input of this.getInputs()) form.append(input.name, input.value);

		return form;
	}

	render() {
		return `<form @ref="element">${this.template.innerHTML}</form>`;
	}

	handleConnect() {
		if (this.submitter) this.submitter.addListener(this.handleSubmitter.bind(this));
	}

	handleSubmitter(e) {
		// Custom submit logic here
		console.log('Submitting form');

		this.element.requestSubmit();
	}

	// Handle state changes for the 'submitting' attribute
	handleState(keys, state) {
		if (keys.includes('submitting')) this.handleSubmitting(state.submitting);
		if (keys.includes('error')) this.handleError(state.error);
		if (keys.includes('success')) this.handleSuccess(state.success);
		if (keys.includes('disabled')) this.handleDisabled(state.disabled);	
	}
	
	handleAttribute() { 
		this.state.set('disabled', this.hasAttribute('disabled'));
	}

	handleSubmitting(value) {
        if (this.submitter) this.submitter.state.set('submitting', value);
    }

	handleError(error) {
		if (error.warning) {
			this.alert.innerHTML = '<ui-alert type="warning">' + error.warning + '</ui-alert>';
		}

		let inputs = this.getInputs();

		for (let input of inputs) {
			if (input.name in error) { 
				input.state.set('error', error[input.name]);
			} else {
				input.state.set('error', '');
			}
		}
	}

	handleSuccess(value) {
		//if (this.submitter) this.submitter.state.set('success', value);
	}

	handleDisabled(value) {
		if (this.submitter) this.submitter.state.set('disabled', value);
	}
}); 