$('#review').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#review').load(this.href);
});

// Forms
$('#form-review').on('submit', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: 'index.php?route=product/review.write&language={{ language }}&review_token={{ review_token }}&product_id={{ product_id }}',
        type: 'post',
        data: $('#form-review').serialize(),
        dataType: 'json',
        cache: false,
        contentType: 'application/x-www-form-urlencoded',
        processData: false,
        beforeSend: function() {
            $('#button-review').button('loading');
        },
        complete: function() {
            $('#button-review').button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();
            $('#form-review').find('.is-invalid').removeClass('is-invalid');
            $('#form-review').find('.invalid-feedback').removeClass('d-block');

            if (json['error']) {
                if (json['error']['warning']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
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
});