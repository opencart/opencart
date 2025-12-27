$('#input-type').on('change', function() {
    if (this.value == 'select' || this.value == 'radio' || this.value == 'checkbox') {
        $('#custom-field-value').show();
        $('#display-value, #display-validation').hide();
    } else {
        $('#custom-field-value').hide();
        $('#display-value, #display-validation').show();
    }

    if (this.value == 'date') {
        $('#display-value > div').html('<input type="date" name="value" value="' + $('#input-value').val() + '" placeholder="{{ entry_value|escape('js') }}" id="input-value" class="form-control"/>');
    } else if (this.value == 'time') {
        $('#display-value > div').html('<input type="time" name="value" value="' + $('#input-value').val() + '" placeholder="{{ entry_value|escape('js') }}" id="input-value" class="form-control"/>');
    } else if (this.value == 'datetime') {
        $('#display-value > div').html('<input type="datetime-local" name="value" value="' + $('#input-value').val() + '" placeholder="{{ entry_value|escape('js') }}" id="input-value" class="form-control"/>');
    } else if (this.value == 'textarea') {
        $('#display-value > div').html('<textarea name="value" placeholder="{{ entry_value|escape('js') }}" id="input-value" class="form-control">' + $('#input-value').val() + '</textarea>');
    } else {
        $('#display-value > div').html('<input type="text" name="value" value="' + $('#input-value').val() + '" placeholder="{{ entry_value|escape('js') }}" id="input-value" class="form-control"/>');
    }
});

$('#input-type').trigger('change');

var custom_field_value_row = {{ custom_field_value_row }};

function addCustomFieldValue() {
    html = '<tr id="custom-field-value-row-' + custom_field_value_row + '">';
    html += '  <td style="width: 70%;"><input type="hidden" name="custom_field_value[' + custom_field_value_row + '][custom_field_value_id]" value="" />';
    {% for language in languages %}
    html += '    <div class="input-group">';
    html += '      <div class="input-group-text"><img src="{{ language.image|escape('js') }}" title="{{ language.name|escape('js') }}" /></div>';
    html += '      <input type="text" name="custom_field_value[' + custom_field_value_row + '][custom_field_value_description][{{ language.language_id }}][name]" value="" placeholder="{{ entry_custom_value|escape('js') }}" id="input-custom-field-{{ custom_field_value_row }}-{{ language.language_id }}" class="form-control"/>';
    html += '    </div>';
    html += '    <div id="error-custom-field-value-{{ custom_field_value_row }}-{{ language.language_id }}" class="invalid-feedback"></div>';
    {% endfor %}
    html += '  </td>';
    html += '  <td class="text-end"><input type="text" name="custom_field_value[' + custom_field_value_row + '][sort_order]" value="" placeholder="{{ entry_sort_order|escape('js') }}" class="form-control"/></td>';
    html += '  <td class="text-end"><button type="button" onclick="$(\'#custom-field-value-row-' + custom_field_value_row + '\').remove();" data-bs-toggle="tooltip" title="{{ button_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#custom-field-value tbody').append(html);

    custom_field_value_row++;
}