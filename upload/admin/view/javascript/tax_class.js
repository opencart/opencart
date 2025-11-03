var tax_rule_row = {{ tax_rule_row }};

$('#button-tax-rule').on('click', function() {
    html = '<tr id="tax-rule-row-' + tax_rule_row + '">';
    html += '  <td><select name="tax_rule[' + tax_rule_row + '][tax_rate_id]" class="form-select">';
    {% for tax_rate in tax_rates %}
    html += '    <option value="{{ tax_rate.tax_rate_id }}">{{ tax_rate.name|escape('js') }}</option>';
    {% endfor %}
    html += '  </select></td>';
    html += '  <td><select name="tax_rule[' + tax_rule_row + '][based]" class="form-select">';
    html += '    <option value="shipping">{{ text_shipping|escape('js') }}</option>';
    html += '    <option value="payment">{{ text_payment|escape('js') }}</option>';
    html += '    <option value="store">{{ text_store|escape('js') }}</option>';
    html += '  </select></td>';
    html += '  <td><input type="text" name="tax_rule[' + tax_rule_row + '][priority]" value="" placeholder="{{ entry_priority|escape('js') }}" class="form-control"/></td>';
    html += '  <td class="text-end"><button type="button" onclick="$(\'#tax-rule-row-' + tax_rule_row + '\').remove();" data-bs-toggle="tooltip" title="{{ button_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#tax-rule tbody').append(html);

    tax_rule_row++;
});