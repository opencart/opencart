$('#input-type').on('change', function() {
    $.ajax({
        url: $(this).val(),
        dataType: 'html',
        beforeSend: function() {
            $('.fa-filter').addClass('fa-circle-notch fa-spin');
            $('.fa-filter').removeClass('fa-filter');
            $('#input-type').prop('disabled', true);
        },
        complete: function() {
            $('.fa-circle-notch').addClass('fa-filter');
            $('.fa-circle-notch').removeClass('fa-circle-notch fa-spin');
            $('#input-type').prop('disabled', false);
        },
        success: function(html) {
            $('#extension').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Download promotion extension
$('#extension').on('click', '#recommended .btn-primary', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('value'),
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
                $('#extension').before('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>');
            }

            if (json['success']) {
                $('#extension').before('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + '  <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                // Manually build the url so no need to refresh extensions and can trigger install
                // Once the extension is downloaded we trigger the installer
                $(element).parent().find('.dropdown-menu').html('<a href="index.php?route=marketplace/installer.install&user_token={{ user_token }}&extension_install_id=' + json['extension_install_id'] + '" class="dropdown-item"><i class="fa-solid fa-plus-circle fa-fw"></i> {{ text_install }}</a> <a href="index.php?route=marketplace/installer.delete&user_token={{ user_token }}&extension_install_id=' + json['extension_install_id'] + '" class="dropdown-item"><i class="fa-regular fa-trash-can fa-fw"></i> {{ text_delete }}</a>');

                $(element).parent().find('.dropdown-item:first').trigger('click');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

            $(element).button('reset');
        }
    });
});

// Download dropdown
$('#extension').on('click', '.dropdown-item', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('href'),
        dataType: 'json',
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#extension').before('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>');
            }

            if (json['success']) {
                $('#extension').before('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + '  <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#extension').load($('#input-type').val());
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Install
$('#extension').on('click', '.btn-success', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('href'),
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('loading');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#input-type').trigger('change');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Uninstall / Delete
$('#extension').on('click', '.btn-danger, .btn-outline-danger', function(e) {
    e.preventDefault();

    if (confirm('{{ text_confirm }}')) {
        var element = this;

        $.ajax({
            url: $(element).attr('href'),
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
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#input-type').trigger('change');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});