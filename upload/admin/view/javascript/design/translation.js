$('#input-language').on('change', function() {
    $.ajax({
        url: 'index.php?route=design/translation.path&user_token={{ user_token }}&language_id=' + $('#input-language').val(),
        dataType: 'json',
        beforeSend: function() {
            $('#input-language').prop('disabled', true);
            $('#input-route').prop('disabled', true);
            $('#input-key').prop('disabled', true);
        },
        complete: function() {
            $('#input-language').prop('disabled', false);
            $('#input-route').prop('disabled', false);
            $('#input-key').prop('disabled', false);
        },
        success: function(json) {
            html = '';

            if (json) {
                for (i = 0; i < json.length; i++) {
                    if (json[i] == '{{ route }}') {
                        html += '<option value="' + json[i] + '" selected>' + json[i] + '</option>';
                    } else {
                        html += '<option value="' + json[i] + '">' + json[i] + '</option>';
                    }
                }
            }

            $('#input-route').html(html);

            $('#input-route').trigger('change');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#input-route').on('change', function() {
    $.ajax({
        url: 'index.php?route=design/translation.translation&user_token={{ user_token }}&language_id=' + $('#input-language').val() + '&path=' + $('#input-route').val(),
        dataType: 'json',
        beforeSend: function() {
            $('#input-language').prop('disabled', true);
            $('#input-route').prop('disabled', true);
            $('#input-key').prop('disabled', true);
        },
        complete: function() {
            $('#input-language').prop('disabled', false);
            $('#input-route').prop('disabled', false);
            $('#input-key').prop('disabled', false);
        },
        success: function(json) {
            translation = [];

            html = '<option value=""></option>';

            if (json) {
                for (i = 0; i < json.length; i++) {
                    if (json[i]['key'] == $('#input-key').val()) {
                        html += '<option value="' + json[i]['key'] + '" selected>' + json[i]['key'] + '</option>';
                    } else {
                        html += '<option value="' + json[i]['key'] + '">' + json[i]['key'] + '</option>';
                    }

                    translation[json[i]['key']] = json[i]['value'];
                }
            }

            $('#input-keys').html(html);

            $('#input-keys').trigger('change');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#input-keys').on('change', function() {
    if (translation[$('#input-keys').val()]) {
        $('#input-default').val(translation[$('#input-keys').val()]);

        $('#input-key').val($('#input-keys').val());
    } else {
        $('#input-default').val('');
    }
});

$('#input-language').trigger('change');