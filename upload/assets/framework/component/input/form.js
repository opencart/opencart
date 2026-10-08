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
            error: '',
            success: ''
        };
    }

    render() {
        return `<form @ref="form" @submit="onSubmit" ${this.state.submitting ? ' disabled' : ''}></form>`;
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
            onSuccess: this.success,
            onError: (e) => {
                console.log('onError', e);
            }
        };

        let method = 'GET';

       // (options.method || 'GET').toUpperCase()

        if (this.hasAttribute('method') === 'get') {
            await ajax.get(this.getAttribute('action'), form, handler);
        } else {
            await ajax.post(this.getAttribute('action'), form, handler);
        }

        if (method) {
            await ajax.get(this.getAttribute('action'), form, handler);
        }

        //if (this.form.has('button')) {
            //binder.get('button-submitter').button('loading');
        //}
    }

    success(json) {
        // Remove past error classes from inputs
        this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
        this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

        // Display error messages
        if (json.has('error')) {
            for (let key in json.get('error')) {
                let value = key.replaceAll('_', '-');

                // If the element has inputs inside.
                this.form.querySelector('#input-' + value).classList.add('is-invalid');
                this.form.querySelector('#input-' + value).querySelectorAll('.form-control, .form-select, .form-check-input, .form-check-label').forEach(element => element.classList.add('is-invalid'));
                this.form.querySelector('#error-' + value).classList.add('d-block');
            }
        }

        // Display success message
        if (json.has('success')) {
            this.alert.prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');
        }
    }
});