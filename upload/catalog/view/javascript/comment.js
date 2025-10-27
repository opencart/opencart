$('#comment').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#comment').load(this.href);
});

$('#input-sort').on('change', function(e) {
    $('#comment').load($(this).val());
});

// Add Comment
$('#cms-comment').on('click', '[data-oc-toggle=\'comment\']', function(e) {
    e.preventDefault();

    var element = this;

    $('#form-comment').attr('action', $(element).val());
    $('#form-comment').attr('data-oc-target', $(element).attr('data-oc-target'));
    $('#form-comment').attr('data-oc-trigger', $(element).attr('data-oc-trigger'));

    $('#modal-comment').modal('show');
});

$('#form-comment').on('submit', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('action'),
        type: 'post',
        data: $(element).serialize(),
        dataType: 'json',
        cache: false,
        contentType: 'application/x-www-form-urlencoded',
        processData: false,
        beforeSend: function() {
            $('#button-comment').button('loading');
        },
        complete: function() {
            $('#button-comment').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            $('#form-comment').find('.is-invalid').removeClass('is-invalid');
            $('#form-comment').find('.invalid-feedback').removeClass('d-block');

            if (json['error']) {
                if (json['error']['warning']) {
                    $('#modal-comment .modal-body').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                for (key in json['error']) {
                    $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                    $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                }
            }

            if (json['success']) {
                $('#modal-comment .modal-body').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#input-comment').val('');

                $($('#form-comment').attr('data-oc-trigger')).trigger('click');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// More
$('#comment').on('click', '[data-oc-toggle=\'next\']', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).val(),
        dataType: 'html',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(html) {
            $(element).parent().before(html);
            $(element).parent().remove();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Refresh
$('#comment').on('click', '[data-oc-toggle=\'refresh\']', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).val(),
        dataType: 'html',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(html) {
            $($(element).attr('data-oc-target')).remove();

            $(element).parent().before(html);
            $(element).parent().remove();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Rating
$('#comment').on('click', '[data-oc-toggle=\'rate\']', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).val(),
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});