/**
 * PayTheFly - Checkout Payment Confirmation
 *
 * Handles the "Confirm Order" button click during checkout.
 * Sends AJAX request to generate EIP-712 signed payment URL,
 * then redirects the customer to PayTheFly's payment page.
 */
$('#button-confirm').on('click', function() {
    var element = this;

    $.ajax({
        url: 'index.php?route=extension/paythefly/payment/paythefly.confirm&language={{ language }}',
        dataType: 'json',
        beforeSend: function() {
            $(element).prop('disabled', true).html('<i class="fa-solid fa-spinner fa-spin"></i> Processing...');
        },
        complete: function() {
            $(element).prop('disabled', false).html('<i class="fa-solid fa-wallet"></i> {{ button_confirm }}');
        },
        success: function(json) {
            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['redirect']) {
                location = json['redirect'];
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log('PayTheFly Error: ' + thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> Payment request failed. Please try again. <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }
    });
});
