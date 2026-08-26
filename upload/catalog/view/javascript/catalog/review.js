import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/review');

// Library
const ajax = await loader.library('ajax');

customElements.define('product-review', class extends WebComponent {

});

customElements.define('review-list', class extends WebComponent {

});

customElements.define('review-form', class extends WebComponent {


    async render() {
        let data = {};

        return loader.template('catalog/review_form', { ...data, ...language, ...config });
    }

    onSubmit(e) {
        e.preventDefault();

        let target = e.target;

        let form = new FormData(target);

        ajax.post('index.php?route=catalog/review.write&language=' + config.config_language + '&review_token=' + this.review_token + '&product_id={{ product_id }}', form, {
            beforeSend: function() {
                this.bind('button-review').loading = true;
            },
            complete: function() {
                this.bind('button-review').loading = false;
            },
            success: function(json) {
                $('.alert-dismissible').remove();

                // Remove past error classes from inputs
                target.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
                target.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                    }

                    for (let key in json['error']) {
                        $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                        $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                    }
                }

                if (json['success']) {
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#input-text').val('');
                    $('#input-rating input[type=\'radio\']').prop('checked', false);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

    }
});
