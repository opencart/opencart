import { Controller } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/login');

// Library
const session = await loader.library('session');
const cart = await loader.library('cart');

export default class extends Controller {
    connect() {

    }

    async render() {
        let data = {};

        var element = this;

        return loader.template('account/login', { ...data, ...language });
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.currentTarget.getAttribute('href');
    }

    async onSubmit(e) {
        e.preventDefault();

        console.log('onSubmit');

        //this.$button_cart.state = 'loading';

        let target = e.target;

        let form = new FormData(this.$form);

        let ajax = new Ajax({
            url: 'action.php?route=account/login.login',
            method: 'POST', // GET, POST, PUT, PATCH
            //headers: {},
            //accept: 'application/json',
            body: form,
            accept: 'json', // Return Type json, html, text
            beforeSend: (e) => {
                console.log('beforeSend', e);

                //this.$button.state = 'loading';
            },
            onComplete: (json) => {
                console.log('onComplete', json);

                //this.$button.state = '';
            },
            onSuccess: (json) => {
                console.log('onSuccess', json);
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });

        ajax.send();

        /*
        let response = await fetch('action.php?route=account/login', {
            method: 'POST',
            body: form
        });

        if (!response.ok) {
            console.log(response);

            //throw new Error(response.thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }

        let json = await response.json();

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

        // this.$button_cart.state = '';
        */
    }
}