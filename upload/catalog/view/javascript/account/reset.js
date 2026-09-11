import { WebComponent } from '../component.js';
import { loader } from '../index.js';

const language = await loader.language('account/reset');

// Library
const ajax = await loader.library('ajax');
const customer = await loader.library('customer');

// Name
export const name = 'account-reset';

customElements.define('account-reset', class extends WebComponent {
    render() {
        return loader.template('account/reset', { ...language });
    }

    async onSubmit(e) {
        e.preventDefault();

        console.log('onSubmit');

        //this.$button_cart.state = 'loading';

        let target = e.target;

        let form = new FormData(target);

        ajax.post('action.php?route=account/reset', form, {
            beforeSend: () => {
                this.ref('button-cart').button('loading');
            },
            onComplete: () => {
                this.ref('button-cart').button('reset');
            },
            onSuccess: (json) => {
                console.log('onSuccess', json);

                // Remove past error classes from inputs
                target.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
                target.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

                // Display error messages
                if (json['error'] !== undefined) {
                    for (let key in json['error']) {
                        let value = key.replaceAll('_', '-');

                        let input = target.querySelector('#input-' + value);

                        if (input) {
                            input.classList.add('is-invalid');

                            // If the element has inputs inside.
                            input.querySelectorAll('.form-control, .form-select, .form-check-input, .form-check-label').forEach(element => element.classList.add('is-invalid'));
                        }

                        let error = target.querySelector('#error-' + value);

                        if (error) {
                            error.classList.add('d-block');
                        }
                    }
                }

                // Display success message
                if (json['success'] !== undefined) {
                    let alert = target.querySelector('#alert');

                    if (alert) {
                        alert.prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                    }
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
});