$('#product').on('click', 'thead a, .pagination a', function(e) {
    e.preventDefault();

    $('#product').load(this.href);
});

$('#button-filter').on('click', function() {
    var url = '';

    var filter_name = $('#input-name').val();

    if (filter_name) {
        url += '&filter_name=' + encodeURIComponent(filter_name);
    }

    var filter_model = $('#input-model').val();

    if (filter_model) {
        url += '&filter_model=' + encodeURIComponent(filter_model);
    }

    var filter_category_id = $('#input-category-id').val();

    if (filter_category_id) {
        url += '&filter_category_id=' + filter_category_id;
    }

    var filter_manufacturer_id = $('#input-manufacturer-id').val();

    if (filter_manufacturer_id) {
        url += '&filter_manufacturer_id=' + filter_manufacturer_id;
    }

    var filter_price_from = $('#input-price-from').val();

    if (filter_price_from) {
        url += '&filter_price_from=' + encodeURIComponent(filter_price_from);
    }

    var filter_price_to = $('#input-price-to').val();

    if (filter_price_to) {
        url += '&filter_price_to=' + encodeURIComponent(filter_price_to);
    }

    var filter_quantity_from = $('#input-quantity-from').val();

    if (filter_quantity_from) {
        url += '&filter_quantity_from=' + filter_quantity_from;
    }

    var filter_quantity_to = $('#input-quantity-to').val();

    if (filter_quantity_to) {
        url += '&filter_quantity_to=' + filter_quantity_to;
    }

    var filter_status = $('#input-status').val();

    if (filter_status !== '') {
        url += '&filter_status=' + filter_status;
    }

    window.history.pushState({}, null, 'index.php?route=catalog/product&user_token={{ user_token }}' + url);

    $('#product').load('index.php?route=catalog/product.list&user_token={{ user_token }}' + url);
});

$('#input-name').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/product.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['product_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-name').val(item['label']);
    }
});

$('#input-model').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/product.autocomplete&user_token={{ user_token }}&filter_model=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['model'],
                        value: item['product_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-model').val(item['label']);
    }
});

$('#input-category').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/category.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                json.unshift({
                    name: '{{ text_none }}',
                    category_id: '',
                });

                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['category_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        if (item['value']) {
            $('#input-category').val(item['label']);
            $('#input-category-id').val(item['value']);
        } else {
            $('#input-category').val('');
            $('#input-category-id').val('');
        }
    }
});

$('#input-manufacturer').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/manufacturer.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                json.unshift({
                    name: '{{ text_none }}',
                    category_id: '',
                });

                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['manufacturer_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        if (item['value']) {
            $('#input-manufacturer').val(item['label']);
            $('#input-manufacturer-id').val(item['value']);
        } else {
            $('#input-manufacturer').val('');
            $('#input-manufacturer-id').val('');
        }
    }
});