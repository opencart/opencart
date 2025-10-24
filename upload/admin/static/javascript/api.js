$('#form-api').on('submit', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=marketplace/api.save&user_token={{ user_token }}',
        type: 'post',
        dataType: 'json',
        data: $('#form-api').serialize(),
        beforeSend: function() {
            $('#button-save').button('loading');
        },
        complete: function() {
            $('#button-save').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();
            $('.invalid-feedback').removeClass('d-block');

            if (json['error']) {
                if (json['error']['warning']) {
                    $('#modal-api .modal-body').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#modal-api .modal-body').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                window.location.reload();
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});