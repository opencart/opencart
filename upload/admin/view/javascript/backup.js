$('#button-backup').on('click', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=tool/backup.backup&user_token=' + url.get('user_token'),
        type: 'post',
        data: $('input[name^=\'backup\']:checked'),
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
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#history').load('index.php?route=tool/backup.history&user_token=' + url.get('user_token'));
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Restore
$('#history').on('click', '.btn-warning', function() {
    var element = this;

    $(element).button('loading');

    $.ajax({
        url: 'index.php?route=tool/backup.restore&user_token={{ user_token }}&filename=' + encodeURIComponent($(this).val()),
        type: 'post',
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
            $('#button-upload, #button-backup, #history .btn').not(element).prop('disabled', true);
        },
        complete: function() {
            $(element).button('reset');
            $('#button-upload, #button-backup, #history .btn').not(element).prop('disabled', false);
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#history').load('index.php?route=tool/backup.history&user_token=' + url.get('user_token'));

            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Upload
$('#button-upload').on('click', function() {
    $('#form-upload').remove();

    $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="upload" /></form>');

    $('#form-upload input[name=\'upload\']').trigger('click');

    $('#form-upload input[name=\'upload\']').on('change', function() {
        if ((this.files[0].size / 1024) > {{ config_file_max_size }}) {
            $(this).val('');

            alert('{{ error_upload_size }}');
        }
    });

    if (typeof timer != 'undefined') {
        clearInterval(timer);
    }

    timer = setInterval(function() {
        if ($('#form-upload input[name=\'upload\']').val() !== '') {
            clearInterval(timer);

            $.ajax({
                url: 'index.php?route=tool/backup.upload&user_token=' + url.get('user_token'),
                type: 'post',
                dataType: 'json',
                data: new FormData($('#form-upload')[0]),
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#button-upload').button('loading');
                },
                complete: function() {
                    $('#button-upload').button('reset');
                },
                success: function(json) {
                    console.log(json);

                    $('.alert-dismissible').remove();

                    if (json['error']) {
                        $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                    }

                    if (json['success']) {
                        $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                        $('#history').load('index.php?route=tool/backup.history&user_token=' + url.get('user_token'));
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            });
        }
    }, 500);
});

// Delete
$('#history').on('click', '.btn-danger', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=tool/backup.delete&user_token=' + url.get('user_token') + '&filename=' + encodeURIComponent($(element).val()),
        type: 'post',
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
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#history').load('index.php?route=tool/backup.history&user_token=' + url.get('user_token'));
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});