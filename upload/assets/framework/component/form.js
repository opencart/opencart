import { WebComponent } from '../component.js';

export class FormAjax extends WebComponent {
    render() {
        return '<form @bind="form" @submit="onSubmit">' + this.innerHTML + '</form>';
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
}

customElements.define('form-ajax', FormAjax);