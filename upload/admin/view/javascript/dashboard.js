$(document).ready(function() {
    $('#modal-security').modal('show');

    $('#accordion .accordion-header:first button').trigger('click');
});

$('#button-refresh').on('click', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=common/security.list&user_token={{ user_token }}',
        dataType: 'html',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(html) {
            $('#modal-security').modal('dispose');

            $('#security').html(html);

            if ($('#modal-security .accordion-item').length > 0) {
                $('#modal-security').modal('show');

                $('#accordion .accordion-header:first button').trigger('click');
            } else {
                $('#modal-security').modal('dispose');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#security').on('click', '#button-install', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=common/security.install&user_token={{ user_token }}',
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert-install').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert-install').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                setTimeout(function() {
                    $('#button-refresh').trigger('click');
                }, 3000);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#security').on('submit', '#form-storage', function(e) {
    e.preventDefault();

    $('#button-storage').button('loading');

    var storage = function(next) {
        return $.ajax({
            url: next,
            dataType: 'json',
            success: function(json) {
                console.log(json);

                $('.alert-dismissible').remove();

                if (json['error']) {
                    $('#alert-storage').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#button-storage').button('reset');
                }

                if (json['text']) {
                    $('#alert-storage').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle-circle"></i> ' + json['text'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                if (json['success']) {
                    $('#alert-storage').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#button-storage').button('reset');

                    setTimeout(function() {
                        $('#button-refresh').trigger('click');
                    }, 3000);
                }

                if (json['next']) {
                    storage(json['next']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                $('#button-storage').button('reset');
            }
        });
    };

    storage('index.php?route=common/security.storage&user_token={{ user_token }}&name=' + encodeURIComponent($('#input-storage').val()) + '&path=' + encodeURIComponent($('#input-path').val()));
});

$('#security').on('click', '#button-storage-delete', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=common/security.storage_delete&user_token={{ user_token }}',
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert-storage').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert-storage').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                setTimeout(function() {
                    $('#button-refresh').trigger('click');
                }, 3000);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#security').on('submit', '#form-admin', function(e) {
    e.preventDefault();

    $('#button-admin').button('loading');

    var admin = function(next) {
        return $.ajax({
            url: next,
            dataType: 'json',
            success: function(json) {
                console.log(json);

                $('.alert-dismissible').remove();

                if (json['redirect']) {
                    setTimeout(function(redirect) {
                        location = redirect;
                    }, 3000, json['redirect']);
                }

                if (json['error']) {
                    $('#alert-admin').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#button-admin').button('reset');
                }

                if (json['text']) {
                    $('#alert-admin').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle-circle"></i> ' + json['text'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                if (json['success']) {
                    $('#alert-admin').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#button-admin').button('reset');
                }

                if (json['next']) {
                    admin(json['next']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                $('#button-admin').button('reset');
            }
        });
    };

    admin('index.php?route=common/security.admin&user_token={{ user_token }}&name=' + encodeURIComponent($('#input-admin').val()));
});

$('#security').on('click', '#button-admin-delete', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=common/security.admin_delete&user_token={{ user_token }}',
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert-admin').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert-admin').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                setTimeout(function() {
                    $('#button-refresh').trigger('click');
                }, 3000);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#button-setting').on('click', function() {
    $.ajax({
        url: 'index.php?route=common/developer&user_token={{ user_token }}',
        dataType: 'html',
        beforeSend: function() {
            $('#button-setting').button('loading');
        },
        complete: function() {
            $('#button-setting').button('reset');
        },
        success: function(html) {
            $('#modal-developer').remove();

            $('body').prepend(html);

            $('#modal-developer').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#modal-developer table button').on('click', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=common/developer.' + $(element).attr('value') + '&user_token={{ user_token }}',
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
                $('#alert-modal').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert-modal').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});