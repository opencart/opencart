import { WebComponent } from '../../engine.js';
import { ajax } from '../../library.js';

customElements.define('form-input', class extends WebComponent {
    static observedAttributes = [
        'action',
        'method'
    ];

    initialState() {
        return {
            submitting: false,
            success: '',
            error: ''
        };
    }

    setState(state) {
        this.state.add('submitting');
    }

    render() {
        return `<form @ref="form" @submit="onSubmit" ${this.state.submitting ? ' disabled' : ''}>' + this.innerHTML + '</form>`;
    }

    async onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        let handler = {
            beforeSend: () => {
                this.submitter.toggleAttribute('loading', true);
            },
            onComplete: () => {
                this.submitter.toggleAttribute('loading', false);
            },
            onSuccess: (json) => this.onSuccess.bind(this),
            onError: (e) => {
                console.log('onError', e);
            }
        };

        let method = 'GET';

        // (options.method || 'GET').toUpperCase()

        await ajax.post(this.getAttribute('action'), form, handler);

        //await ajax.get(this.getAttribute('action'), form, handler);
    }

    setSuccess(fn) {
        this.success = fn;
    }

    onSuccess(json) {
        // Display error messages
        if (json.has('error')) {
            for (let [ key, value ] of json.get('error')) {
                if (key == 'warning') {
                    this.global.get('alert').prepend('<ui-alert type="danger">' + value + '</ui-alert>');

                    continue;
                }

                let input = this.bind.get('input-' + key);

                this.binder.get('input-' + key)?.setInvalid(value);

                console.log('works');
            }
        }

        // Display success message
        if (json.has('success')) {


            this.global.get('alert').prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');
        }
    }
});