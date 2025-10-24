$('#list').on('click', 'thead a, .pagination a', function(e) {
    e.preventDefault();

    $('#list').load(this.href);
});

$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=sale/order&user_token={{ user_token }}&' + url);

    $('#list').load('index.php?route=sale/order.list&user_token={{ user_token }}&' + url);
});

$('#input-customer').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=customer/customer.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['customer_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-customer').val(decodeHTMLEntities(item['label']));
    }
});

$('#content').on('change', 'input[name^=\'selected\']', function() {
    $('#button-shipping, #button-invoice').prop('disabled', true);

    var selected = $('input[name^=\'selected\']:checked');

    if (selected.length) {
        $('#button-invoice').prop('disabled', false);
    }

    for (i = 0; i < selected.length; i++) {
        if ($(selected[i]).parent().find('input[name^=\'shipping_method\']').val()) {
            $('#button-shipping').prop('disabled', false);
            break;
        }
    }
});

$('#button-shipping, #button-invoice').prop('disabled', true);