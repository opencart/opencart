$('#button-upload').on('click', function() {
    var element = this;

    $('#form-upload').remove();

    $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file"/></form>');

    var $fileInput = $('#form-upload input[name=\'file\']');

    $fileInput.trigger('click');

    // If the file is canceled before being selected
    $fileInput[0].addEventListener('cancel', function(e) {
        $('#form-upload').remove();
    });

    $fileInput.on('change', function() {
        if (this.files[0].size > {{ config_file_max_size }}) {
            $(this).val('');

            $('#form-upload').remove();

            alert('{{ error_upload_size }}');
        }
    });

    if (typeof timer !== 'undefined') {
        clearInterval(timer);
    }

    var timer = setInterval(function() {
        if ($fileInput.val() !== '') {
            clearInterval(timer);

            $.ajax({
                url: 'index.php?route=catalog/download.upload&user_token={{ user_token }}',
                type: 'post',
                dataType: 'json',
                data: new FormData($('#form-upload')[0]),
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
                        alert(json['error']);
                    }

                    if (json['success']) {
                        alert(json['success']);

                        $('#input-filename').val(json['filename']);
                        $('#input-mask').val(json['mask']);

                        $('#button-download').prop('disabled', false);

                        $('#form-upload').remove();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                    $('#form-upload').remove();
                }
            });
        }
    }, 500);
});

$('#input-filename').on('change', function(e) {
    var value = $(this).val();

    if (value != '') {
        $('#button-download').prop('disabled', false);
    } else {
        $('#button-download').prop('disabled', true);
    }
});

$('#button-download').on('click', function(e) {
    e.preventDefault();

    location = 'index.php?route=catalog/download.download&user_token=' + getURLVar('user_token') + '&filename=' + $('#input-filename').val();
});