import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('account/login');

// Library
const session = await loader.library('session');

class AccountLogin extends WebComponent {
    render() {
        data = {};

        var element = this;

        return loader.template('account/login', { ...data, ...language });
    }

    submit(e) {
        e.preventDefault();

        console.log(e);

        let login = api.fetch({
            url: this.getAttribute('action'),
            method: 'post',
            data: new FormData(this),
            beforeSend: () => {
                $('#button-login').button('loading');
            },
            afterSend: () => {
                $('#button-login').button('reset');
            },
            success: (json) => {
                this.querySelector('.alert-dismissible').remove();

                let alert = document.getElementById('alert');

                if (json.error !== undefined) {
                    alert.append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json.error + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                if (json.success !== undefined) {
                    alert.append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json.success + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    session.set('customer', json.customer);
                }
            },
            error: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
}

customElements.define('account-login', AccountLogin);

/*
$('#form-login').on('submit', function(e) {

    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('action'),
        type: 'post',
        dataType: 'json',
        data: $(element).serialize(),
        beforeSend: function() {
            $('#button-login').button('loading');
        },
        complete: function() {
            $('#button-login').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

               session.set('customer_token', json['customer_token']);
            }


            if (json['redirect']) {
                //location = json['redirect'];
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
*/