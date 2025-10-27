$('input[name=\'payment_method\']').on('change', function() {
    $('.payment').hide();

    $('#payment-' + this.value).show();
});

$('input[name=\'payment_method\']:checked').trigger('change');

$('#input-generator').autocomplete({
    'source': function(request, response) {
        return $.ajax({
            url: 'index.php?route=account/tracking.autocomplete&customer_token={{ customer_token }}&search=' + encodeURIComponent(request) + '&tracking=' + encodeURIComponent($('#input-code').val()) + '&language={{ language }}',
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['link']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-link').val(item['value']);
    }
});