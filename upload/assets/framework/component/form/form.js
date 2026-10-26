import { WebComponent } from '../../engine.js';

customElements.define('form-submit', class extends WebComponent {
	static shadow = false;
	static formAssociated = true;

	/* Will only manage states listed here */
	states() {
		return {
			submitting: false,
			error: [],
			disabled: false
		};
	}

	constructor() { 
		super();

		this.html = this.innerHTML;
	}

	getInputs() {
		return this.querySelectorAll('[name]');
	}

	getData() {		
		let form = new FormData();
		
		for (let input of this.getInputs()) form.append(input.name, input.value);

		return form;
	}

	render() {
		return '<form @ref="form">' + this.html + '</form>';
	}

	// Handle state changes for the 'submitting' attribute
	handleState(key, value) {
		switch (key) {
			case 'submitting':
				this.handleSubmitting(value);
				break;
			case 'error':
				this.handleError(value);
				break;
			case 'disabled':
				this.handleDisabled(value);
				break;
		}
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

	handleDisabled(value) {
		if (this.submitter) this.submitter.state.set('disabled', value);
	}
}); 