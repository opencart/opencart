$(document).ready(function(e) {
    // Initialize codemirrror
    var codemirror = CodeMirror.fromTextArea(document.querySelector('#input-code'), {
        mode: 'text/html',
        lineNumbers: true,
        lineWrapping: true,
        autofocus: true,
        theme: 'monokai'
    });

    codemirror.setSize('100%', '500px');
});

$('#input-route').on('change', function(e) {
    var element = this;

    $.ajax({
        url: 'index.php?route=design/theme.template&user_token={{ user_token }}&store_id=' + $('#input-store').val() + '&path=' + $('#input-route').val(),
        dataType: 'json',
        beforeSend: function() {
            $(element).prop('disabled', true);
        },
        complete: function() {
            $(element).prop('disabled', false);
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + '  <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + '  <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            var codemirror = $('.CodeMirror')[0].CodeMirror;

            codemirror.setValue(json['code']);
            codemirror.setSize('100%', '500px');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});