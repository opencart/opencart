import { registry, factory, loader, config, storage, language, template, url, session, local, db, cart, tax, currency } from './opencart.js';

$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=marketing/affiliate&user_token=' + session.get('user_token') + '&' + url);

    $('#list').load('index.php?route=marketing/affiliate.list&user_token=' + session.get('user_token') + '&' + url);
});

$('#input-customer').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=customer/customer.autocomplete&user_token=' + session.get('user_token') + '&filter_affiliate=1&filter_name=' + encodeURIComponent(request),
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

$('#button-calculate').on('click', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: 'index.php?route=marketing/affiliate.calculate&user_token=' + session.get('user_token'),
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
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                url = '';

                var filter_customer = $('#input-customer').val();

                if (filter_customer) {
                    url += '&filter_customer=' + encodeURIComponent(filter_customer);
                }

                var filter_tracking = $('#input-tracking').val();

                if (filter_tracking) {
                    url += '&filter_tracking=' + encodeURIComponent(filter_tracking);
                }

                var filter_payment_method = $('#input-payment-method').val();

                if (filter_payment_method) {
                    url += '&filter_payment_method=' + filter_payment_method;
                }

                var filter_commission = $('#input-commission').val();

                if (filter_commission) {
                    url += '&filter_commission=' + filter_commission;
                }

                var filter_date_from = $('#input-date-from').val();

                if (filter_date_from) {
                    url += '&filter_date_from=' + encodeURIComponent(filter_date_from);
                }

                var filter_date_to = $('#input-date-to').val();

                if (filter_date_to) {
                    url += '&filter_date_to=' + encodeURIComponent(filter_date_to);
                }

                var filter_status = $('#input-status').val();

                if (filter_status !== '') {
                    url += '&filter_status=' + filter_status;
                }

                var limit = $('#input-limit').val();

                if (limit) {
                    url += '&limit=' + limit;
                }

                $('#list').load('index.php?route=marketing/affiliate.list&user_token={{ user_token }}' + url);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#input-customer').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=customer/customer.autocomplete&user_token=' + session.get('user_token') + '&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        category: item['customer_group'],
                        label: item['name'],
                        value: item['customer_id'],
                        customer_group_id: item['customer_group_id'],
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-customer').val(decodeHTMLEntities(item['label']));
        $('#input-customer-id').val(item['value']);
        $('#input-customer-group').val(item['customer_group_id']);

        $('#input-customer-group').trigger('change');
    }
});

$('#input-customer-group').on('change', function() {
    $.ajax({
        url: 'index.php?route=customer/customer.customfield&user_token=' + session.get('user_token') + '&customer_group_id=' + this.value,
        dataType: 'json',
        success: function(json) {
            $('.custom-field').hide();
            $('.custom-field').removeClass('required');

            for (i = 0; i < json.length; i++) {
                custom_field = json[i];

                $('.custom-field-' + custom_field['custom_field_id']).show();

                if (custom_field['required']) {
                    $('.custom-field-' + custom_field['custom_field_id']).addClass('required');
                }
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#input-customer-group').trigger('change');

$('#history').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#history').load(this.href);
});

$('#button-history').on('click', function(e) {
    var element = this;

    $.ajax({
        url: 'index.php?route=customer/customer.addHistory&user_token=' + url.get('user_token') + '&customer_id=' + $('#input-customer-id').val(),
        type: 'post',
        dataType: 'json',
        data: 'comment=' + encodeURIComponent($('#input-history').val()),
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#history').load('index.php?route=customer/customer.history&user_token={{ user_token }}&customer_id=' + $('#input-customer-id').val());

                $('#input-history').val('');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#transaction').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#transaction').load(this.href);
});

$('#button-transaction').on('click', function(e) {
    e.preventDefault();

    $.ajax({
        url: 'index.php?route=customer/customer.addTransaction&user_token=' + url.get('user_token') + '&customer_id=' + $('#input-customer-id').val(),
        type: 'post',
        dataType: 'json',
        data: 'description=' + encodeURIComponent($('#input-transaction').val()) + '&amount=' + encodeURIComponent($('#input-amount').val()),
        beforeSend: function() {
            $('#button-transaction').button('loading');
        },
        complete: function() {
            $('#button-transaction').button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#transaction').load('index.php?route=customer/customer.transaction&user_token=' + $('#input-user-token').val() + '&customer_id=' + $('#input-customer-id').val());

                $('#input-transaction').val('');
                $('#input-amount').val('');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#report').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#report').load(this.href);
});

$('input[name=\'payment_method\']').on('change', function() {
    $('#payment-method').html($('#template-' + this.value).html());
});

$('input[name=\'payment_method\']:checked').trigger('change');