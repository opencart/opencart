$('#form-quote').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=extension/opencart/checkout/shipping.quote&language={{ language }}',
        type: 'post',
        data: $('#form-quote').serialize(),
        dataType: 'json',
        beforeSend: function() {
            $('#button-quote').button('loading');
        },
        complete: function() {
            $('#button-quote').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();
            $('#form-shipping').find('.is-invalid').removeClass('is-invalid');
            $('#form-shipping').find('.invalid-feedback').removeClass('d-block');

            if (json['error']) {
                if (json['error']['warning']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['shipping_methods']) {
                $('#modal-shipping').remove();

                html = '<div id="modal-shipping" class="modal">';
                html += '  <div class="modal-dialog">';
                html += '    <div class="modal-content">';
                html += '      <div class="modal-header">';
                html += '        <h4 class="modal-title">{{ text_shipping_method|escape('js') }}</h4>';
                html += '        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>';
                html += '      </div>';
                html += '      <div class="modal-body">';
                html += '        <form id="form-shipping">';
                html += '          <p>{{ text_estimate|escape('js') }}</p>';

                for (i in json['shipping_methods']) {
                    html += '<p><strong>' + json['shipping_methods'][i]['name'] + '</strong></p>';

                    if (!json['shipping_methods'][i]['error']) {
                        for (j in json['shipping_methods'][i]['quote']) {
                            html += '<div class="form-check">';

                            var code = i + '-' + j.replaceAll('_', '-');

                            html += '<input type="radio" name="shipping_method" value="' + json['shipping_methods'][i]['quote'][j]['code'] + '" id="input-shipping-method-' + code + '"';

                            if (json['shipping_methods'][i]['quote'][j]['code'] == '{{ code }}') {
                                html += ' checked';
                            }

                            html += '/>';
                            html += '  <label for="input-shipping-method-' + code + '">' + json['shipping_methods'][i]['quote'][j]['name'] + ' - <x-currency code="{{ currency }}" amount="' + json['shipping_methods'][i]['quote'][j]['cost'] + '"></x-currency></label>';
                            html += '</div>';
                        }
                    } else {
                        html += '<div class="alert alert-danger alert-dismissible">' + json['shipping_methods'][i]['error'] + '</div>';
                    }
                }

                html += '          <div class="text-end">';
                html += '            <button type="submit" id="button-shipping-method" class="btn btn-primary">{{ button_shipping|escape('js') }}</button>';
                html += '          </div>';
                html += '        </form>';
                html += '      </div>';
                html += '    </div>';
                html += '  </div>';
                html += '</div>';

                $('body').append(html);

                $('#modal-shipping').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$(document).on('submit', '#form-shipping', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=extension/opencart/checkout/shipping.save&language={{ language }}',
        type: 'post',
        data: $('#form-shipping').serialize(),
        dataType: 'json',
        contentType: 'application/x-www-form-urlencoded',
        beforeSend: function() {
            $('#button-shipping-method').button('loading');
        },
        complete: function() {
            $('#button-shipping-method').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#shopping-cart').load('index.php?route=checkout/cart.list&language={{ language }}');

                $('#modal-shipping').modal('hide');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});