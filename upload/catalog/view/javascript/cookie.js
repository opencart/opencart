// Cookie Policy
$('#cookie button').on('click', function() {
    var element = this;

    $.ajax({
        url: $(this).val(),
        type: 'get',
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            if (json['success']) {
                $('#cookie').fadeOut(400, function() {
                    $('#cookie').remove();
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});