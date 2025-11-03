$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=catalog/product&user_token={{ user_token }}&' + url);

    $('#list').load('index.php?route=catalog/product.list&user_token={{ user_token }}&' + url);
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
        $('#input-name').val(decodeHTMLEntities(item['label']));
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
        $('#input-model').val(decodeHTMLEntities(item['label']));
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
            $('#input-category').val(decodeHTMLEntities(item['label']));
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
            $('#input-manufacturer').val(decodeHTMLEntities(item['label']));
            $('#input-manufacturer-id').val(item['value']);
        } else {
            $('#input-manufacturer').val('');
            $('#input-manufacturer-id').val('');
        }
    }
});

$('textarea[data-oc-toggle=\'ckeditor\']').ckeditor({
    language: '{{ ckeditor }}'
});

var code_row = {{ code_row }};

$('#button-code').on('click', function() {
    var html = '';

    let value = $('#input-value').val();

    html += '<tr id="code-row-' + code_row + '">';
    html += '  <td style="width: 1px;">' + $('#input-code option:selected').text() + '<input type="hidden" name="product_code[' + code_row + '][identifier_id]" value="' + $('#input-code').val() + '"/></td>';
    html += '  <td>' + value + '<div id="error-code-' + code_row + '" class="invalid-feedback"></div><input type="hidden" name="product_code[' + code_row + '][value]" value="' + value + '"/></td>';
    html += '  <td class="text-end"><button type="button" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#product-code').append(html);

    code_row++;
});

$('#product-code').on('click', '.btn', function() {
    $(this).parent().parent().remove();
});

// Manufacturer
$('#input-manufacturer').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/manufacturer.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                json.unshift({
                    manufacturer_id: 0,
                    name: '{{ text_none }}'
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
        $('#input-manufacturer').val(decodeHTMLEntities(item['label']));
        $('#input-manufacturer-id').val(item['value']);
    }
});

// Category
$('#input-category').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/category.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
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

        $('#product-category-' + item['value']).remove();

        html = '<tr id="product-category-' + item['value'] + '">';
        html += '  <td>' + item['label'] + '<input type="hidden" name="product_category[]" value="' + item['value'] + '"/></td>';
        html += '  <td class="text-end"><button type="button" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#product-category tbody').append(html);
    }
});

$('#product-category').on('click', '.btn', function() {
    $(this).parent().parent().remove();
});

// Filter
$('#input-filter').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/filter.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['filter_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-filter').val('');

        $('#product-filter-' + item['value']).remove();

        html = '<tr id="product-filter-' + item['value'] + '">';
        html += '  <td>' + item['label'] + '<input type="hidden" name="product_filter[]" value="' + item['value'] + '"/></td>';
        html += '  <td class="text-end"><button type="button" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#product-filter tbody').append(html);
    }
});

$('#product-filter').on('click', '.btn', function() {
    $(this).parent().parent().remove();
});

// Downloads
$('#input-download').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/download.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['download_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-download').val('');

        $('#product-download-' + item['value']).remove();

        html = '<tr id="product-download-' + item['value'] + '">';
        html += '  <td>' + item['label'] + '<input type="hidden" name="product_download[]" value="' + item['value'] + '"/></td>';
        html += '  <td class="text-end"><button type="button" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#product-download tbody').append(html);
    }
});

$('#product-download').on('click', '.btn', function() {
    $(this).parent().parent().remove();
});

// Related
$('#input-related').autocomplete({
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
        $('#input-related').val('');

        $('#product-related-' + item['value']).remove();

        html = '<tr id="product-related-' + item['value'] + '">';
        html += '  <td>' + item['label'] + '<input type="hidden" name="product_related[]" value="' + item['value'] + '"/></td>';
        html += '  <td class="text-end"><button type="button" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
        html += '</tr>';

        $('#product-related tbody').append(html);
    }
});

$('#product-related').on('click', '.btn', function() {
    $(this).parent().parent().remove();
});

var attributeautocomplete = function(attribute_row) {
    $('#input-attribute-' + attribute_row).autocomplete({
        'source': function(request, response) {
            $.ajax({
                url: 'index.php?route=catalog/attribute.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            category: item.attribute_group,
                            label: item.name,
                            value: item.attribute_id
                        }
                    }));
                }
            });
        },
        'select': function(item) {
            $('#input-attribute-' + attribute_row).val(decodeHTMLEntities(item['label']));
            $('#input-attribute-id-' + attribute_row).val(item['value']);
        }
    });
}

var attribute_row = {{ attribute_row }};

$('#product-attribute tr').each(function(index) {
    attributeautocomplete(index);
});

$('#button-attribute').on('click', function() {
    html = '<tr id="attribute-row-' + attribute_row + '">';
    html += '  <td>';
    html += '    <input type="text" name="product_attribute[' + attribute_row + '][name]" value="" placeholder="{{ entry_attribute|escape('js') }}" id="input-attribute-' + attribute_row + '" data-oc-target="autocomplete-attribute-' + attribute_row + '" class="form-control" autocomplete="off"/>';
    html += '    <input type="hidden" name="product_attribute[' + attribute_row + '][attribute_id]" value="" id="input-attribute-id-' + attribute_row + '"/>';
    html += '    <ul id="autocomplete-attribute-' + attribute_row + '" class="dropdown-menu"></ul>';
    html += '  </td>';
    html += '  <td>';
    {% for language in languages %}
    html += '<div class="input-group">';
    html += '  <div class="input-group-text"><img src="{{ language.image }}" title="{{ language.name|escape('js') }}" /></div>';
    html += '  <textarea name="product_attribute[' + attribute_row + '][product_attribute_description][{{ language.language_id }}][text]" rows="5" placeholder="{{ entry_text|escape('js') }}" id="input-text-' + attribute_row + '-{{ language.language_id }}" class="form-control"></textarea>';
    html += '</div>';
    {% endfor %}
    html += '  </td>';
    html += '  <td class="text-end"><button type="button" onclick="$(\'#attribute-row-' + attribute_row + '\').remove();" data-bs-toggle="tooltip" title="{{ button_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#product-attribute').append(html);

    attributeautocomplete(attribute_row);

    attribute_row++;
});

{% if not master_id %}
var option_row = {{ option_row }};

$('#input-option').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=catalog/option.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        category: item['category'],
                        label: item['name'],
                        value: item['option_id'],
                        type: item['type'],
                        option_value: item['option_value']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        html = '<fieldset id="option-row-' + option_row + '">';
        html += '  <legend class="float-none">' + item['label'] + '</legend>';
        html += '  <input type="hidden" name="product_option[' + option_row + '][product_option_id]" value="" />';
        html += '  <input type="hidden" name="product_option[' + option_row + '][name]" value="' + decodeHTMLEntities(item['label']) + '" />';
        html += '  <input type="hidden" name="product_option[' + option_row + '][option_id]" value="' + item['value'] + '" />';
        html += '  <input type="hidden" name="product_option[' + option_row + '][type]" value="' + item['type'] + '" />';

        html += '  <div class="row align-items-center">';
        html += '    <div class="col-11">';

        html += '      <div class="mb-3">';
        html += '        <label for="input-required-' + option_row + '" class="form-label">{{ entry_required|escape('js') }}</label>';
        html += '	       <select name="product_option[' + option_row + '][required]" id="input-required-' + option_row + '" class="form-select">';
        html += '	         <option value="1">{{ text_yes|escape('js') }}</option>';
        html += '	         <option value="0">{{ text_no|escape('js') }}</option>';
        html += '	       </select>';
        html += '      </div>';

        if (item['type'] == 'text') {
            html += '  <div class="mb-3">';
            html += '     <label for="input-option-' + option_row + '" class="form-label">{{ entry_option_value|escape('js') }}</label>';
            html += '     <input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="{{ entry_option_value|escape('js') }}" id="input-option-' + option_row + '" class="form-control"/>';
            html += '	 </div>';
        }

        if (item['type'] == 'textarea') {
            html += '  <div class="mb-3">';
            html += '    <label for="input-option-' + option_row + '" class="form-label">{{ entry_option_value|escape('js') }}</label>';
            html += '    <textarea name="product_option[' + option_row + '][value]" rows="5" placeholder="{{ entry_option_value|escape('js') }}" id="input-option-' + option_row + '" class="form-control"></textarea>';
            html += '	 </div>';
        }

        if (item['type'] == 'file') {
            html += '  <div class="mb-3 d-none">';
            html += '    <label for="input-option-' + option_row + '" class="form-label">{{ entry_option_value|escape('js') }}</label>';
            html += '    <input type="text" name="product_option[' + option_row + '][value]" value="" placeholder="{{ entry_option_value|escape('js') }}" id="input-option-' + option_row + '" class="form-control"/>';
            html += '  </div>';
        }

        if (item['type'] == 'date') {
            html += '  <div class="mb-3">';
            html += '    <label for="input-option-' + option_row + '" class="form-label">{{ entry_option_value|escape('js') }}</label>';
            html += '    <input type="date" name="product_option[' + option_row + '][value]" value="" placeholder="{{ entry_option_value|escape('js') }}" id="input-option-' + option_row + '" class="form-control"/>';
            html += '  </div>';
        }

        if (item['type'] == 'time') {
            html += '  <div class="mb-3">';
            html += '    <label for="input-option-' + option_row + '" class="form-label">{{ entry_option_value|escape('js') }}</label>';
            html += '    <input type="time" name="product_option[' + option_row + '][value]" value="" placeholder="{{ entry_option_value|escape('js') }}" id="input-option-' + option_row + '" class="form-control"/>';
            html += '  </div>';
        }

        if (item['type'] == 'datetime') {
            html += '	 <div class="mb-3">';
            html += '    <label for="input-option-' + option_row + '" class="form-label">{{ entry_option_value|escape('js') }}</label>';
            html += '    <input type="datetime-local" name="product_option[' + option_row + '][value]" value="" placeholder="{{ entry_option_value|escape('js') }}" id="input-option-' + option_row + '" class="form-control"/>';
            html += '  </div>';
        }

        if (item['type'] == 'select' || item['type'] == 'radio' || item['type'] == 'checkbox' || item['type'] == 'image') {
            html += '<div class="table-responsive">';
            html += '  <table class="table table-bordered table-hover">';
            html += '  	 <thead>';
            html += '      <tr>';
            html += '        <td>{{ entry_option_value|escape('js') }}</td>';
            html += '        <td class="text-end">{{ entry_quantity|escape('js') }}</td>';
            html += '        <td>{{ entry_subtract|escape('js') }}</td>';
            html += '        <td class="text-end">{{ entry_price|escape('js') }}</td>';
            html += '        <td class="text-end">{{ entry_points|escape('js') }}</td>';
            html += '        <td class="text-end">{{ entry_weight|escape('js') }}</td>';
            html += '        <td></td>';
            html += '      </tr>';
            html += '    </thead>';
            html += '    <tbody id="option-value-' + option_row + '"></tbody>';
            html += '    <tfoot>';
            html += '      <tr>';
            html += '        <td colspan="6"></td>';
            html += '        <td class="text-end"><button type="button" data-option-row="' + option_row + '" data-bs-toggle="tooltip" title="{{ button_option_value_add|escape('js') }}" class="btn btn-primary"><i class="fa-solid fa-plus-circle"></i></button></td>';
            html += '      </tr>';
            html += '    </tfoot>';
            html += '  </table>';
            html += '</div>';

            html += '<select id="product-option-values-' + option_row + '" class="d-none">';

            for (i = 0; i < item['option_value'].length; i++) {
                html += '<option value="' + item['option_value'][i]['option_value_id'] + '">' + item['option_value'][i]['name'] + '</option>';
            }

            html += '</select>';
        }

        html += '	 </div>';
        html += '	 <div class="col">';
        html += '    <button type="button" class="btn btn-danger" onclick="$(\'#option-row-' + option_row + '\').remove();"><i class="fa-solid fa-minus-circle"></i></button>';
        html += '  </div>';
        html += '</fieldset>';

        $('#option').append(html);

        option_row++;
    }
});

var option_value_row = {{ option_value_row }};

$('#option').on('click', '.btn-primary', function() {
    var element = this;

    if ($(element).attr('data-option-value-row')) {
        element.option_value_row = $(element).attr('data-option-value-row');
    } else {
        element.option_value_row = option_value_row;
    }

    $('.modal').remove();

    html = '<div id="modal-option" class="modal fade">';
    html += '  <div class="modal-dialog">';
    html += '    <div class="modal-content">';
    html += '      <div class="modal-header">';
    html += '        <h5 class="modal-title"><i class="fa-solid fa-pencil"></i> {{ text_option_value|escape('js') }}</h5> <button type="button" class="btn-close" data-bs-dismiss="modal"></button>';
    html += '      </div>';
    html += '      <div class="modal-body">';
    html += '        <div class="mb-3">';
    html += '      	   <label for="input-modal-option-value" class="form-label">{{ entry_option_value|escape('js') }}</label>';
    html += '      	   <select name="option_value_id" id="input-modal-option-value" class="form-select">';

    option_value = $('#product-option-values-' + $(element).attr('data-option-row') + ' option');

    for (i = 0; i < option_value.length; i++) {
        if ($(element).attr('data-option-value-row') && $(option_value[i]).val() == $('input[name=\'product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][option_value_id]\']').val()) {
            html += '<option value="' + $(option_value[i]).val() + '" selected>' + $(option_value[i]).text() + '</option>';
        } else {
            html += '<option value="' + $(option_value[i]).val() + '">' + $(option_value[i]).text() + '</option>';
        }
    }

    html += '      	   </select>';
    html += '          <input type="hidden" name="product_option_value_id" value="' + ($(element).attr('data-option-value-row') ? $('input[name=\'product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][product_option_value_id]\']').val() : '') + '"/>';
    html += '        </div>';
    html += '        <div class="mb-3">';
    html += '      	   <label for="input-modal-quantity" class="form-label">{{ entry_quantity|escape('js') }}</label>';
    html += '      	   <input type="text" name="quantity" value="' + ($(element).attr('data-option-value-row') ? $('input[name=\'product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][quantity]\']').val() : '1') + '" placeholder="{{ entry_quantity|escape('js') }}" id="input-modal-quantity" class="form-control"/>';
    html += '        </div>';
    html += '        <div class="mb-3">';
    html += '      	   <label for="input-modal-subtract" class="form-label">{{ entry_subtract|escape('js') }}</label>';
    html += '      	   <select name="subtract" id="input-modal-subtract" class="form-select">';

    if ($(element).attr('data-option-value-row') && $('input[name=\'product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][subtract]\']').val() == '1') {
        html += '        <option value="1" selected>{{ text_yes|escape('js') }}</option>';
        html += '      	 <option value="0">{{ text_no|escape('js') }}</option>';
    } else {
        html += '      	 <option value="1">{{ text_yes|escape('js') }}</option>';
        html += '      	 <option value="0" selected>{{ text_no|escape('js') }}</option>';
    }

    html += '      	   </select>';
    html += '        </div>';
    html += '        <div class="mb-3">';
    html += '      	   <label for="input-modal-price" class="form-label">{{ entry_price|escape('js') }}</label>';
    html += '          <div class="input-group">';
    html += '            <select name="price_prefix" class="form-select">';

    if ($(element).attr('data-option-value-row') && $('input[name=\'product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][price_prefix]\']').val() == '+') {
        html += '      	   <option value="+" selected>+</option>';
    } else {
        html += '      	   <option value="+">+</option>';
    }

    if ($(element).attr('data-option-value-row') && $('input[name=\'product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][price_prefix]\']').val() == '-') {
        html += '      	       <option value="-" selected>-</option>';
    } else {
        html += '      	       <option value="-">-</option>';
    }

    html += '      	     </select>';
    html += '      	     <input type="text" name="price" value="' + ($(element).attr('data-option-value-row') ? $('input[name=\'product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][price]\']').val() : '0') + '" placeholder="{{ entry_price|escape('js') }}" id="input-modal-price" class="form-control"/>';
    html += '          </div>';
    html += '        </div>';
    html += '        <div class="mb-3">';
    html += '      	   <label for="input-modal-points" class="form-label">{{ entry_points|escape('js') }}</label>';
    html += '          <div class="input-group">';
    html += '      	     <select name="points_prefix" class="form-select">';

    if ($(element).attr('data-option-value-row') && $('input[name=\'product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][points_prefix]\']').val() == '+') {
        html += '      	       <option value="+" selected>+</option>';
    } else {
        html += '      	       <option value="+">+</option>';
    }

    if ($(element).attr('data-option-value-row') && $('input[name=\'product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][points_prefix]\']').val() == '-') {
        html += '      	       <option value="-" selected>-</option>';
    } else {
        html += '      	       <option value="-">-</option>';
    }

    html += '      	     </select>';
    html += '      	     <input type="text" name="points" value="' + ($(element).attr('data-option-value-row') ? $('input[name=\'product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][points]\']').val() : '0') + '" placeholder="{{ entry_points|escape('js') }}" id="input-modal-points" class="form-control"/>';
    html += '          </div>';
    html += '        </div>';
    html += '        <div class="mb-3">';
    html += '      	   <label for="input-modal-weight" class="form-label">{{ entry_weight|escape('js') }}</label>';
    html += '          <div class="input-group">';
    html += '      	     <select name="weight_prefix" class="form-select">';

    if ($(element).attr('data-option-value-row') && $('input[name=\'product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][weight_prefix]\']').val() == '+') {
        html += '      	       <option value="+" selected>+</option>';
    } else {
        html += '      	       <option value="+">+</option>';
    }

    if ($(element).attr('data-option-value-row') && $('input[name=\'product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][weight_prefix]\']').val() == '-') {
        html += '      	       <option value="-" selected>-</option>';
    } else {
        html += '      	       <option value="-">-</option>';
    }

    html += '      	     </select>';
    html += '      	     <input type="text" name="weight" value="' + ($(element).attr('data-option-value-row') ? $('input[name=\'product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][weight]\']').val() : '0') + '" placeholder="{{ entry_weight|escape('js') }}" id="input-modal-weight" class="form-control"/>';
    html += '          </div>';
    html += '        </div>';
    html += '      </div>';
    html += '      <div class="modal-footer">';
    html += '	       <button type="button" id="button-save" data-option-row="' + $(element).attr('data-option-row') + '" data-option-value-row="' + element.option_value_row + '" class="btn btn-primary">{{ button_save|escape('js') }}</button> <button type="button" class="btn btn-light" data-bs-dismiss="modal">{{ button_cancel|escape('js') }}</button>';
    html += '      </div>';
    html += '    </div>';
    html += '  </div>';
    html += '</div>';

    $('body').append(html);

    $('#modal-option').modal('show');

    $('#modal-option #button-save').on('click', function() {
        html = '<tr id="option-value-row-' + element.option_value_row + '">';
        html += '  <td>' + $('#modal-option select[name=\'option_value_id\'] option:selected').text() + '<input type="hidden" name="product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][option_value_id]" value="' + $('#modal-option select[name=\'option_value_id\']').val() + '"/><input type="hidden" name="product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][product_option_value_id]" value="' + $('#modal-option input[name=\'product_option_value_id\']').val() + '"/></td>';
        html += '  <td class="text-end">' + $('#modal-option input[name=\'quantity\']').val() + '<input type="hidden" name="product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][quantity]" value="' + $('#modal-option input[name=\'quantity\']').val() + '"/></td>';
        html += '  <td>' + ($('#modal-option select[name=\'subtract\'] option:selected').val() == '1' ? '{{ text_yes|escape('js') }}' : '{{ text_no|escape('js') }}') + '<input type="hidden" name="product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][subtract]" value="' + $('#modal-option select[name=\'subtract\'] option:selected').val() + '"/></td>';
        html += '  <td class="text-end">' + $('#modal-option select[name=\'price_prefix\'] option:selected').val() + $('#modal-option input[name=\'price\']').val() + '<input type="hidden" name="product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][price_prefix]" value="' + $('#modal-option select[name=\'price_prefix\'] option:selected').val() + '"/><input type="hidden" name="product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][price]" value="' + $('#modal-option input[name=\'price\']').val() + '"/></td>';
        html += '  <td class="text-end"> ' + $('#modal-option select[name=\'points_prefix\'] option:selected').val() + $('#modal-option input[name=\'points\']').val() + '<input type="hidden" name="product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][points_prefix]" value="' + $('#modal-option select[name=\'points_prefix\'] option:selected').val() + '"/><input type="hidden" name="product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][points]" value="' + $('#modal-option input[name=\'points\']').val() + '"/></td>';
        html += '  <td class="text-end">' + $('#modal-option select[name=\'weight_prefix\'] option:selected').val() + $('#modal-option input[name=\'weight\']').val() + '<input type="hidden" name="product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][weight_prefix]" value="' + $('#modal-option select[name=\'weight_prefix\'] option:selected').val() + '"/><input type="hidden" name="product_option[' + $(element).attr('data-option-row') + '][product_option_value][' + element.option_value_row + '][weight]" value="' + $('#modal-option input[name=\'weight\']').val() + '"/></td>';
        html += '  <td class="text-end"><button type="button" data-bs-toggle="tooltip" title="{{ button_edit|escape('js') }}" data-option-row="' + $(element).attr('data-option-row') + '" data-option-value-row="' + element.option_value_row + '"class="btn btn-primary"><i class="fa-solid fa-pencil"></i></button> <button type="button" onclick="$(\'#option-value-row-' + element.option_value_row + '\').remove();" data-bs-toggle="tooltip" rel="tooltip" title="{{ button_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
        html += '</tr>';

        if ($(element).attr('data-option-value-row')) {
            $('#option-value-row-' + element.option_value_row).replaceWith(html);
        } else {
            $('#option-value-' + $(element).attr('data-option-row')).append(html);

            option_value_row++;
        }

        $('#modal-option').modal('hide');
    });
});
{% endif %}

var discount_row = {{ discount_row }};

$('#button-discount').on('click', function() {
    html = '<tr id="discount-row-' + discount_row + '">';
    html += '  <td><select name="product_discount[' + discount_row + '][customer_group_id]" class="form-select">';
    {% for customer_group in customer_groups %}
    html += '    <option value="{{ customer_group.customer_group_id }}">{{ customer_group.name|escape('js') }}</option>';
    {% endfor %}
    html += '  </select><input type="hidden" name="product_discount[' + discount_row + '][product_discount_id]" value=""/></td>';
    html += '  <td class="text-end"><input type="text" name="product_discount[' + discount_row + '][quantity]" value="1" placeholder="{{ entry_quantity|escape('js') }}" class="form-control"/></td>';
    html += '  <td class="text-end"><input type="text" name="product_discount[' + discount_row + '][priority]" value="" placeholder="{{ entry_priority|escape('js') }}" class="form-control"/></td>';
    html += '  <td class="text-end"><input type="text" name="product_discount[' + discount_row + '][price]" value="" placeholder="{{ entry_price|escape('js') }}" class="form-control"/></td>';
    html += '  <td><select name="product_discount[' + discount_row + '][type]" class="form-select">';
    html += '    <option value="F">{{ text_fixed }}</option>';
    html += '    <option value="S">{{ text_subtract }}</option>';
    html += '    <option value="P">{{ text_percentage }}</option>';
    html += '  </select></td>';
    html += '  <td><x-switch name="product_discount[' + discount_row + '][special]" value="1" checked="0" input-class="form-switch form-switch-lg"></x-switch></td>';
    html += '  <td><input type="date" name="product_discount[' + discount_row + '][date_start]" value="" placeholder="{{ entry_date_start|escape('js') }}" class="form-control"/></td>';
    html += '  <td><input type="date" name="product_discount[' + discount_row + '][date_end]" value="" placeholder="{{ entry_date_end|escape('js') }}" class="form-control"/></td>';
    html += '  <td class="text-end"><button type="button" onclick="$(\'#discount-row-' + discount_row + '\').remove();" data-bs-toggle="tooltip" title="{{ button_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#product-discount tbody').append(html);

    discount_row++;
});

var image_row = {{ image_row }};

$('#button-image').on('click', function() {
    html = '<tr id="product-image-row-' + image_row + '">';
    html += '  <td><div class="border rounded d-block" style="max-width: 300px;">';
    html += '    <img src="{{ placeholder|escape('js') }}" alt="" title="" id="thumb-image-' + image_row + '" data-oc-placeholder="{{ placeholder|escape('js') }}" class="img-fluid"/> <input type="hidden" name="product_image[' + image_row + '][image]" value="" id="input-product-image-' + image_row + '"/>';
    html += '    <div class="d-grid">';
    html += '      <button type="button" data-oc-toggle="image" data-oc-target="#input-product-image-' + image_row + '" data-oc-thumb="#thumb-image-' + image_row + '" class="btn btn-primary rounded-0"><i class="fa-solid fa-pencil"></i> {{ button_edit|escape('js') }}</button>';
    html += '      <button type="button" data-oc-toggle="clear" data-oc-target="#input-product-image-' + image_row + '" data-oc-thumb="#thumb-image-' + image_row + '" class="btn btn-warning rounded-0"><i class="fa-regular fa-trash-can"></i> {{ button_clear|escape('js') }}</button>';
    html += '    </div>';
    html += '  </div></td>';
    html += '  <td><input type="text" name="product_image[' + image_row + '][sort_order]" value="0" placeholder="{{ entry_sort_order|escape('js') }}" class="form-control"/></td>';
    html += '  <td class="text-end"><button type="button" onclick="$(\'#product-image-row-' + image_row + '\').remove();" data-bs-toggle="tooltip" title="{{ button_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#product-image tbody').append(html);

    image_row++;
});

var subscription_row = {{ subscription_row }};

$('#button-subscription').on('click', function() {
    html = '<tr id="subscription-row-' + subscription_row + '">';
    html += '  <td><select name="product_subscription[' + subscription_row + '][subscription_plan_id]" class="form-select">';
    {% for subscription_plan in subscription_plans %}
    html += '      <option value="{{ subscription_plan.subscription_plan_id }}">{{ subscription_plan.name|escape('js') }}</option>';
    {% endfor %}
    html += '  </select></td>';
    html += '  <td><select name="product_subscription[' + subscription_row + '][customer_group_id]" class="form-select">';
    {% for customer_group in customer_groups %}
    html += '      <option value="{{ customer_group.customer_group_id }}">{{ customer_group.name|escape('js') }}</option>';
    {% endfor %}
    html += '  </select></td>';
    html += '  <td class="text-end"><input type="text" name="product_subscription[' + subscription_row + '][trial_price]" value="" placeholder="{{ entry_trial_price|escape('js') }}" class="form-control"/></td>';
    html += '  <td class="text-end"><input type="text" name="product_subscription[' + subscription_row + '][price]" value="" placeholder="{{ entry_price|escape('js') }}" class="form-control"/></td>';
    html += '  <td class="text-end"><button type="button" onclick="$(\'#subscription-row-' + subscription_row + '\').remove()" data-bs-toggle="tooltip" title="{{ button_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#product-subscription tbody').append(html);

    subscription_row++;
});

{% if master_id %}
// Variable products
$('x-switch[data-oc-target]').on('change', function(e) {
    var element = this;

    var target = $(this).attr('data-oc-target');

    // First we need to grab the default values
    // Now we need to enable or disable any fields in the targets
    $.merge($(target), $(target).find('.form-control, .form-select, .form-check-input, .btn')).not(element).each(function(i, elem) {
        // x-switch
        if ($(this).is('x-switch')) {
            $(this).prop('readonly', !$(element).prop('checked'));
        }

        // text, textarea
        if ($(this).is('.form-control')) {
            $(this).prop('readonly', !$(element).prop('checked'));
        }

        // Radio Checkbox
        if ($(this).is('.form-check-input')) {
            if (!$(element).prop('checked')) {
                $(this).on('click', function(e) {
                    return false;
                });
            } else {
                $(this).off('click');
            }
        }

        // Select
        if ($(this).is('.form-select')) {
            if (!$(element).prop('checked')) {
                $(this).addClass('disabled');

                $(this).prop('readonly', true);
            } else {
                $(this).removeClass('disabled');

                $(this).prop('readonly', false);
            }

            $(this).find('option').not(':selected').prop('disabled', !$(element).prop('checked'));
        }

        // Button
        if ($(this).is('.btn')) {
            $(this).prop('disabled', !$(element).prop('checked'));
        }

        // CKEditor readonly
        if ($(this).is('[data-oc-toggle=\'ckeditor\']')) {
            var editor = CKEDITOR.instances[$(this).attr('id')];

            if (editor.editable() == undefined) {
                editor.on('instanceReady', function() {
                    this.setReadOnly(!$(element).prop('checked'));
                });
            } else {
                editor.setReadOnly(!$(element).prop('checked'));
            }
        }
    });
});

$(document).ready(function() {
    $('x-switch').trigger('change');
});
{% endif %}

$('#report').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#report').load(this.href);
});