import { WebComponent } from '../../index.js';

customElements.define('form-input', class extends WebComponent {
    static observed = [
        'action',
        'method'
    ];

    initialState() {
        return {
            submitting: false,
            submit_count: 0
        };
    }

    render() {
        return '<form @bind="form" @submit="onSubmit"><slot></slot></form>';
    }

    onSubmit(e) {
        e.preventDefault();

        // Remove past error classes from inputs
        this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
        this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

        if (this.form.has('button')) {
            //binder.get('button-submitter').button('loading');
        }
    }
});