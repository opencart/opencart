import { Controller } from '../component.js';
import { loader } from '../index.js';

// Library
const session = await loader.library('session');
const ajax = await loader.library('ajax');
const customer = await loader.library('customer');

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('information/contact');

// Storage
const locations = await loader.storage('information/location');

export default class extends Controller {
    async render() {
        let data = {};

        if (customer.isLogged()) {
            data.name = customer.getFirstName() + ' ' + customer.getLastName();
            data.email = customer.getEmail();
        } else {
            data.name = '';
            data.email = '';
        }

        data.locations = locations;

        return loader.template('information/contact', { ...data, ...language, ...config });
    }

    onSubmit(e) {
        e.preventDefault();

        let target = e.target;

        let form = new FormData(target);

        ajax.post('action.php?route=information/contact.send', form, {
            beforeSend: (request) => {
                this.$('button-send').loading = true;
            },
            onComplete: (json) => {
                console.log(this.bind('button-send'));

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

                    let output = [];

                    console.log(json['products']);

                    //console.log(Object.fromEntries(form));
                    for (let product of json['products']) {
                        cart.add(product);
                    }

                    let button = document.querySelector('#cart > button');

                    button.click();
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
};