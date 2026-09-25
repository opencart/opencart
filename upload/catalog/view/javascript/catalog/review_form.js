import { WebComponent } from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/review');

customElements.define('review-form', class extends WebComponent {
    async render(){
        let data = new Map();

        return loader.template('catalog/review_form', [ data, language, config ]);
    }

    onConnect() {
        this.token = ajax.get('action.php?route=account/review.token&language=' + local.get('language') + '&customer_token=' + customer.getToken());
    }

    onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=catalog/review.write&language=' + local.get('language') + '&review_token=' + this.token + '&product_id=' + this.getAttribute('product_id'), form, {
            beforeSend: () => {
                this.button.button('loading');
            },
            complete: () => {
                this.button.button('reset');
            },
            success: this.success,
            error: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    success(json) {
        $('.alert-dismissible').remove();

        // Remove past error classes from inputs
        this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
        this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

        if (json['error']) {
            if (json['error']['warning']) {
                this.alert.prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            for (let key in json['error']) {
                $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
            }
        }

        if (json['success']) {
            this.alert.prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

            this.form.querySelector('#input-text').value = '';
            this.form.querySelector('#input-rating input[type=\'radio\']').checked = false;
        }
    }
});
