// Upload
$('#button-upload').on('click', function() {
    var element = this;

    if (!$('#button-upload').prop('disabled')) {
        $('#form-upload').remove();

        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file"/></form>');

        $('#form-upload input[name=\'file\']').trigger('click');

        $('#form-upload input[name=\'file\']').on('change', function() {
            if ((this.files[0].size / 1024) > {{ config_file_max_size }}) {
                $(this).val('');

                alert('{{ error_upload_size }}');
            }

            if (!this.files[0].name.endsWith('.ocmod.zip')) {
                $(this).val('');

                alert('{{ error_file_type }}');
            }
        });

        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        timer = setInterval(function() {
            if ($('#form-upload input[name=\'file\']').val() !== '') {
                clearInterval(timer);

                $.ajax({
                    url: 'index.php?route=marketplace/installer.upload&user_token={{ user_token }}',
                    type: 'post',
                    data: new FormData($('#form-upload')[0]),
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $(element).button('loading');
                    },
                    complete: function() {
                        $(element).button('reset');
                    },
                    success: function(json) {
                        if (json['error']) {
                            $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                        }

                        if (json['success']) {
                            $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                            $('#extension').load('index.php?route=marketplace/installer.list&user_token={{ user_token }}');
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    }
});

// Install
$('#extension').on('click', '.btn-success, .btn-warning', function(e) {
    e.preventDefault();

    var element = this;

    $(element).button('loading');

    $('#progress-bar').removeClass('bg-danger bg-success').css('width', '0%');
    $('#progress-text').html('');

    var installer = function(next) {
        return $.ajax({
            url: next,
            dataType: 'json',
            success: function(json) {
                console.log(json);

                $('.alert-dismissible').remove();

                if (json['error']) {
                    $('#progress-bar').addClass('bg-danger');
                    $('#progress-text').html('<div class="text-danger">' + json['error'] + '</div>');

                    $(element).button('reset');
                }

                if (json['text']) {
                    $('#progress-bar').addClass('bg-success');
                    $('#progress-text').html('<div class="text-success">' + json['text'] + '</div>');
                }

                if (json['success']) {
                    $('#progress-bar').addClass('bg-success').css('width', '100%');
                    $('#progress-text').html('<div class="text-success">' + json['success'] + '</div>');

                    $(element).button('reset');

                    $('#extension').load('index.php?route=marketplace/installer.list&user_token={{ user_token }}');
                }

                if (json['next']) {
                    installer(json['next']);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                $(element).button('reset');
            }
        });
    };

    installer($(element).attr('href'));
});

// Delete
$('#extension').on('click', '.btn-danger', function(e) {
    e.preventDefault();

    var element = this;

    if (confirm('{{ text_confirm }}')) {
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
                console.log(json);

                $('.alert-dismissible').remove();

                if (json['error']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                if (json['success']) {
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#extension').load('index.php?route=marketplace/installer.list&user_token={{ user_token }}');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});

$('.tab-content .btn-danger').on('click', function() {
    var element = this;

    if (confirm('{{ text_confirm }}')) {
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
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                if (json['success']) {
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#' + $(element).attr('data-oc-target')).val('');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});