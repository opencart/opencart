import { WebComponent } from '../../engine.js';

customElements.define('form-submit', class extends WebComponent {
	static formAssociated = true;

	/* Will only manage states listed here */
	states() {
		return {
			submitting: false,
			error: [],
			disabled: false
		};
	}

	getInputs() {
		return this.root.querySelectorAll('[name]');
	}

	getData() {		
		let form = new FormData();

		for (let input of this.getInputs()) {
			console.log('Appending input:', input.name, input.value);

			form.append(input.name, input.value);
		}

		return form;
	}

	render() {
		let template = this.querySelector('template');

		return '<form @ref="form" @submit="handleSubmit">' + template.innerHTML + '</form>';
	}

	handleConnect() { 
		console.log('Connected');
		console.dir(this);
	}

	handleSubmit(e) {
		e.preventDefault();
		// Custom submit logic here

		console.log('Submitting form');

		console.log(Object.fromEntries(this.getData()));
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
		console.log(error);
		
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