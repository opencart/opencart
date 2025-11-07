$('#form-login').on('submit', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('action'),
        type: 'post',
        dataType: 'json',
        data: $(element).serialize(),
        beforeSend: function() {
            $('#button-login').button('loading');
        },
        complete: function() {
            $('#button-login').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

               sessionStorage.setItem('customer', json['customer'].toJSON());

               $('#address').load('index.php?route=account/address.list&language=' + language + '&customer_token={{ customer_token }}');
            }


            if (json['redirect']) {
                location = json['redirect'];
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});