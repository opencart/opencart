$('#button-cancel').on('click', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: 'index.php?route=account/subscription.cancel&language={{ language }}&customer_token={{ customer_token }}&subscription_id={{ subscription_id }}',
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            console.log(json);

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#history').load('index.php?route=account/subscription.history&language={{ language }}&customer_token={{ customer_token }}&subscription_id={{ subscription_id }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#history').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#history').load(this.href);
});

$('#order').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#order').load(this.href);
});