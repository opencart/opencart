import { loader } from '../index.js';

let form = document.getElementById('form-login');
let alert = document.getElementById('alert');

let onsubmit = async (e) => {
    e.preventDefault();

    let response = await fetch(e.target.getAttribute('action'), {
        method: 'POST',
        body: new FormData(form)
    });

    if (response.status == 200) {
        let json = await response.json();

        if (json['redirect']) {
            document.location = json['redirect'];
        }

        if (typeof json['error']) {
            alert.insertAdjacentElement('afterbegin', '<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }

        if (json['success']) {
            alert.insertAdjacentElement('afterbegin', '<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }
    }
};

form.addEventListener('submit', onsubmit);

/*
// Alert Fade
$('#alert').observe(function() {
    window.setTimeout(function() {
        $('#alert .alert-dismissible').fadeTo(3000, 0, function() {
            $(this).remove();
        });
    }, 3000);
});

$('#form-login').on('submit', 'form', function(e) {
    e.preventDefault();

    var form = e.target;
    var action = $(button).attr('formaction') || $(form).attr('action');
    var method = $(button).attr('formmethod') || $(form).attr('method') || 'post';
    var enctype = $(button).attr('formenctype') || $(form).attr('enctype') || ;

    // https://github.com/opencart/opencart/issues/9690
    if (typeof CKEDITOR != 'undefined') {
        for (instance in CKEDITOR.instances) {
            CKEDITOR.instances[instance].updateElement();
        }
    }

    $.ajax({
        url: action.replaceAll('&amp;', '&'),
        type: method,
        data: $(form).serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $(button).button('loading');
        },
        complete: function() {
            $(button).button('reset');
        },
        success: function(json, textStatus) {
            console.log(json);

            $('.alert-dismissible').remove();
            $(element).find('.is-invalid').removeClass('is-invalid');
            $(element).find('.invalid-feedback').removeClass('d-block');

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (typeof json['error'] == 'string') {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (typeof json['error'] == 'object') {
                if (json['error']['warning']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                // Refresh
                var url = $(form).attr('data-oc-load');
                var target = $(form).attr('data-oc-target');

                if (url !== undefined && target !== undefined) {
                    $(target).load(url);
                }
            }

            // Replace any form values that correspond to form names.
            for (key in json) {
                $(element).find('[name=\'' + key + '\']').val(json[key]);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});
*/