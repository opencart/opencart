var route_row = {{ route_row }};

function addRoute() {
    html = '<tr id="route-row-' + route_row + '">';
    html += '  <td><select name="layout_route[' + route_row + '][store_id]" class="form-select">';
    html += '  <option value="0">{{ text_default|escape('js') }}</option>';
    {% for store in stores %}
    html += '<option value="{{ store.store_id }}">{{ store.name|escape('js') }}</option>';
    {% endfor %}
    html += '  </select></td>';
    html += '  <td><input type="text" name="layout_route[' + route_row + '][route]" value="" placeholder="{{ entry_route|escape('js') }}" class="form-control"/></td>';
    html += '  <td class="text-end"><button type="button" onclick="$(\'#route-row-' + route_row + '\').remove();" data-bs-toggle="tooltip" title="{{ button_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#route tbody').append(html);

    route_row++;
}

var module_row = {{ module_row }};

function addModule(type) {
    html = '<tr id="module-row-' + module_row + '">';
    html += '  <td><div class="input-group input-group-sm">';
    html += '    <select name="layout_module[' + module_row + '][code]" class="form-select input-sm">';
    {% for extension in extensions %}
    html += '    <optgroup label="{{ extension.name|escape('js') }}">';
    {% if not extension.module %}
    html += '      <option value="{{ extension.code|escape('js') }}">{{ extension.name|escape('js') }}</option>';
    {% else %}
    {% for module in extension.module %}
    html += '      <option value="{{ module.code|escape('js') }}">{{ module.name|escape('js') }}</option>';
    {% endfor %}
    {% endif %}
    html += '    </optgroup>';
    {% endfor %}
    html += '  </select>';
    html += '  <input type="hidden" name="layout_module[' + module_row + '][position]" value="' + type.replaceAll('-', '_') + '" />';
    html += '  <input type="hidden" name="layout_module[' + module_row + '][sort_order]" value="" />';
    html += '  <a href="" data-bs-toggle="tooltip" title="{{ button_edit }}" class="btn btn-primary btn-sm"><i class="fa-solid fa-pencil fa-fw"></i></a><button type="button" onclick="$(\'#module-row-' + module_row + '\').remove();" data-bs-toggle="tooltip" title="{{ button_remove }}" class="btn btn-danger btn-sm"><i class="fa-solid fa-minus-circle fa-fw"></i></button>';
    html += '  </div></td>';
    html += '</tr>';

    $('#module-' + type + ' tbody').append(html);

    $('#module-' + type + ' tbody input[name*=\'sort_order\']').each(function(i, element) {
        $(element).val(i);
    });

    $('#module-' + type + ' select:last').trigger('change');

    module_row++;
}

$('#module-column-left, #module-column-right, #module-content-top, #module-content-bottom').on('change', 'select[name*=\'code\']', function() {
    var part = this.value.split('.');

    if (typeof part[2] == 'undefined') {
        $(this).parent().find('a').attr('href', 'index.php?route=extension/' + part[0] + '/module/' + part[1] + '&user_token={{ user_token }}');
    } else {
        $(this).parent().find('a').attr('href', 'index.php?route=extension/' + part[0] + '/module/' + part[1] + '&user_token={{ user_token }}&module_id=' + part[2]);
    }
});