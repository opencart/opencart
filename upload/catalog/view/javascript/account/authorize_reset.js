import { WebComponent } from '../index.js';
import { ajax, loader, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('account/address');

export default class AuthorizeReset extends WebComponent {
    async render() {
        return loader.template('account/authorize_reset', language);
    }

    onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=account/authorize.send&language=' + local.get('language'), form, {
            beforeSend: () => {
                this.submitter.button('loading');
            },
            onComplete: (json) => {
                this.submitter.button('reset');
            },
            onSuccess: this.success.bind(this),
            onError: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    success(json) {
        if (json['redirect']) {
            location = json['redirect'];
        }

        if (json['error']) {
            this.alert.prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }

        if (json['success']) {
            this.alert.prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }
    }
}

customElements.define('authorize-reset', AuthorizeReset);

$('#button-reset').on('click', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'action.php?route=account/authorize.confirm&language={{ language }}',
        dataType: 'json',
        beforeSend: function() {
            $('#button-reset').button('loading');
        },
        complete: function() {
            $('#button-reset').button('reset');
        },
        success: function(json) {
            console.log(json);

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});