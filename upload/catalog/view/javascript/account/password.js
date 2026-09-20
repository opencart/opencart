import { WebComponent } from '../component.js';
import { loader, ajax, customer, local } from '../index.js';

// Language
const language = await loader.language('account/password');

export default class AccountPassword extends WebComponent {
    render() {
        let data = {};

        return loader.template('account/password', { ...data, ...language });
    }

    onSubmit(e) {
        e.preventDefault();

        let target = e.target;

        let form = new FormData(target);

        ajax.post('action.php?route=account/password.save&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            beforeSend: () => {
                //$element.get('button-cart').button('loading');
            },
            onComplete: (json) => {
                //ref.get('button-cart').button('reset');
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
}

customElements.define('account-password', AccountPassword);