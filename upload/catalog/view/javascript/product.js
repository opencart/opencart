$('#input-subscription').on('change', function(e) {
    var element = this;

    $('.subscription').addClass('d-none');

    $('#subscription-description-' + $(element).val()).removeClass('d-none');
});

$('#form-product').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=checkout/cart.add&language={{ language }}',
        type: 'post',
        data: $('#form-product').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        cache: false,
        processData: false,
        beforeSend: function() {
            $('#button-cart').button('loading');
        },
        complete: function() {
            $('#button-cart').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('#form-product').find('.is-invalid').removeClass('is-invalid');
            $('#form-product').find('.invalid-feedback').removeClass('d-block');

            if (json['error']) {
                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#cart').load('index.php?route=common/cart.info&language={{ language }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$(document).ready(function() {
    $('.magnific-popup').magnificPopup({
        type: 'image',
        delegate: 'a',
        gallery: {
            enabled: true
        }
    });
});