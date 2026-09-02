import { Controller } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/edit');

// Library
const customer = await loader.library('customer');

export default class extends Controller {
    token = '';

    connect() {
        this.token = ajax.get('action.php?route=account/edit.token');
    }

    async render() {
        let data = {};

        data.firstname = customer.getFirstName();
        data.lastname = customer.getLastName();
        data.email = customer.getEmail();
        data.telephone = customer.getTelephone();

        // Custom Fields
        data.custom_fields = [];

        let customer_group = await loader.storage('customer/customer_group-' + customer.getGroupId());

        if (customer_group) {
            data.custom_fields = customer_group.custom_fields;
        }

        data.token = customer.getToken();

        return loader.template('account/edit', { ...data, ...language, ...config });
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.currentTarget.getAttribute('href');
    }

    async onSubmit(e) {
        e.preventDefault();

        console.log('addToCart');

        let target = e.target;

        let form = new FormData(target);

        ajax.post('action.php?route=acccount/edit.save&language={{ language }}', form, {
            beforeSend: (request) => {
                //this.bind('button-cart').setAttribute('loading', '');
            },
            onComplete: (json) => {
                //console.log(this.bind('button-cart'));

                //this.bind('button-cart').loading = false;
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