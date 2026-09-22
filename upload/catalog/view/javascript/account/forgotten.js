import { WebComponent } from '../component.js';
import { loader, ajax, customer } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/forgotten');

export default class AccountForgotten extends WebComponent {
    onConnect() {
        if (customer.isLogged()) {
            let target = document.getElementById('content');

            target.src = 'account/account';
        }
    }

    render() {
        return loader.template('account/forgotten', { ...language });
    }

    confirm(e) {
        e.preventDefault();


        e.preventDefault();

        let target = e.target;

        let form = new FormData(target);

        ajax.post('action.php?route=account/forgotten.confirm&language=' + local.get('language'), form, {
            beforeSend: () => {
                //ref.get('button-cart').button('loading');
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

customElements.define('account-forgotten', AccountForgotten);