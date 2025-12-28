const url = new URLSearchParams(document.location.search);


$('#input-product').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/product.autocomplete&user_token=' + url.get('user_token') + '&filter_name=' + encodeURIComponent(request),
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
        $('#input-product').val('');

        $('#coupon-product-' + item['value']).remove();

        html = '<tr id="coupon-product-' + item['value'] + '">';
        html += '  <td>' + item['label'] + '<input type="hidden" name="coupon_product[]" value="' + item['value'] + '"/></td>';
        html += '  <td class="text-end"><button type="button" class="btn btn-danger btn-sm"><i class="fa-solid fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#coupon-product tbody').append(html);
    }
});

$('#coupon-product').on('click', '.btn', function() {
    $(this).parent().parent().remove();
});

// Category
$('#input-category').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/category.autocomplete&user_token=' + url.get('user_token') + '&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
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
        $('#input-category').val('');

        $('#coupon-category-' + item['value']).remove();

        html = '<tr id="coupon-category-' + item['value'] + '">';
        html += '  <td>' + item['label'] + '<input type="hidden" name="coupon_category[]" value="' + item['value'] + '"/></td>';
        html += '  <td class="text-end"><button type="button" class="btn btn-danger btn-sm"><i class="fa-solid fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#coupon-category tbody').append(html);
    }
});

$('#coupon-category').on('click', '.btn', function() {
    $(this).parent().parent().remove();
});

$('#history').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#history').load(this.href);
});