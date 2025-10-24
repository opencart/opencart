$('#input-theme').on('change', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=setting/setting.theme&user_token={{ user_token }}&theme=' + this.value,
        dataType: 'html',
        beforeSend: function() {
            $(element).prop('disabled', true);
        },
        complete: function() {
            $(element).prop('disabled', false);
        },
        success: function(html) {
            $('#theme').attr('src', html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#input-theme').trigger('change');