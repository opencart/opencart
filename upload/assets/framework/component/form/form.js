import { WebComponent } from '../../engine.js';

customElements.define('form-input', class extends WebComponent {
    properties = {

    }

    states() {
        return {
            submitting: false,
            success: '',
            error: ''
        }
    }

    styles() {
        return `
        :host {
            display: block;
        }
        
        form {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }
        
        ::slotted(button[type='submit']) {
            align-self: flex-start;
            margin-top: 0.5rem;
        }
        
        :host([data-submitting]) ::slotted(button[type='submit']) {
            opacity: 0.6;
            pointer-events: none;
        }`;
    }

    render() {
        return `<form @ref="form" @submit="onSubmit"><slot></slot></form>`;
    }

    onConnect() {
        this.state.addListener('submit', this.handleSubmit);

        this.state.addListener('error', this.stateError);
        ///this.state.addListener('disabled', this.stateDisabled);


        //this.state.addListener('disabled', this.handleDisabled);
    }

    async onSubmit(e) {
        e.preventDefault();

        const skip = this.hasAttribute('novalidate');

        if (!skip && !this.form.reportValidity()) return; // browser already focused/flagged the first invalid field

        this.submitter.state.set('loading', true);
    }

    handleState(keys) {
        if (keys.includes('submitting')) {
            this.toggleAttribute('submitting', this.state.get('submitting'));
        }

        if (keys.includes('error')) {
            this.handleError();
        }
    }

    stateError() {
        this.state.set('submitting', false);

        let errors = this.state.get('error');

        for (let [ key, value ] of errors) {

            //this.form.get(key).state.set('error', value);


            // Remove past error classes from inputs
            this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
            this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

        }
    }



});