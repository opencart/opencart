import { Controller } from '../component.js';
import { loader } from '../index.js';

const language = await loader.language('account/reset');

export default class extends Controller {
    render() {


        return loader.template('account/reset', { ...language });
    }

    async onSubmit(e) {
        e.preventDefault();

        console.log('addToCart');

        //this.$button_cart.state = 'loading';

        let target = e.target;

        let form = new FormData(target);

        let response = await fetch('action.php?route=account/register', {
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
    }
}