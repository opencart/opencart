import { WebComponent } from '../../engine.js';

customElements.define('form-submit', class extends WebComponent {
    static formAssociated = true;
    #inputs = [];

    /* Will only manage states listed here */
    static states() {
        return {
            submitting: false,
            success: '',
            error: [],
            inputs: []
        }
    }

    styles() {
        return ``;
    }

    addInputs(input) {
        this.#inputs.push(input);
    }

    getInputs() {
        return this.#inputs;
    }

    submit() {
        this.form.requestSubmit();
    }

    render() {
        return '<form @ref="form" @submit="handleSubmit"><slot></slot></form>';
    }

    handleConnect() {
        //console.log(this.binder.refs);
        //console.log(this.binder.listeners);
    }

    handleState(changed, state) {
        console.log('form-submit handleState', changed, state);
    }

    handleSubmit(e) {
        e.preventDefault();

        console.log('handleSubmit');

        this.state.set('submitting', true);

        let inputs = this.getInputs();
        let errors = this.state.get('error');

        console.log('inputs', inputs);
        console.log('inputs', errors);

        for (let input of inputs) {
            input.state.set('error', errors[input.name] || '');
        }


    }

    test() {
        //console.log('form-submit');
        //console.dir(this.form);
        //console.log(this.form.elements);
    }

    async handleSubmitting(e) {
        e.preventDefault();
    }

    handleError(e) {
        console.log('handleError', e);

        this.state.set('submitting', false);

        let errors = this.state.get('errors');

        for (let [key, value] of errors) {
            if (key === 'warning') {

            }

            value.state.set('error', value);
        }
    }

    handleAgree(e) {
        this.submitter.toggleAttribute('disabled', !e.target.hasAttribute('checked'));
    }
});