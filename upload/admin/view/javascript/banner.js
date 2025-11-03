var image_row = {{ image_row }};

$('button[id^=\'button-banner\']').on('click', function() {
    var element = this;

    html = '<tr id="image-row-' + image_row + '">';
    html += '  <td class="text-center"><div class="border rounded d-block" style="max-width: 300px;">';
    html += '      <img src="{{ placeholder|escape('js') }}" alt="" title="" id="thumb-image-' + $(element).attr('data-language') + '-' + image_row + '" data-oc-placeholder="{{ placeholder|escape('js') }}" class="img-fluid"/>';
    html += '      <input type="hidden" name="banner_image[' + $(element).attr('data-language') + '][' + image_row + '][image]" value="" id="input-image-' + $(element).attr('data-language') + '-' + image_row + '-image"/>';
    html += '      <div class="d-grid">';
    html += '        <button type="button" data-oc-toggle="image" data-oc-target="#input-image-' + $(element).attr('data-language') + '-' + image_row + '-image" data-oc-thumb="#thumb-image-' + $(element).attr('data-language') + '-' + image_row + '" class="btn btn-primary rounded-0"><i class="fa-solid fa-pencil"></i> {{ button_edit|escape('js') }}</button>';
    html += '        <button type="button" data-oc-toggle="clear" data-oc-target="#input-image-' + $(element).attr('data-language') + '-' + image_row + '-image" data-oc-thumb="#thumb-image-' + $(element).attr('data-language') + '-' + image_row + '" class="btn btn-warning rounded-0"><i class="fa-regular fa-trash-can"></i> {{ button_clear|escape('js') }}</button>';
    html += '      </div>';
    html += '    </div></td>';
    html += '  <td><input type="text" name="banner_image[' + $(element).attr('data-language') + '][' + image_row + '][title]" value="" placeholder="{{ entry_title|escape('js') }}" id="input-image-' + $(element).attr('data-language') + '-' + image_row + '-title" class="form-control"/>';
    html += '    <div id="error-image-' + $(element).attr('data-language') + '-' + image_row + '-title" class="invalid-feedback"></div></td>';
    html += '  <td><input type="text" name="banner_image[' + $(element).attr('data-language') + '][' + image_row + '][link]" value="" placeholder="{{ entry_link|escape('js') }}" id="input-image-' + $(element).attr('data-language') + '-' + image_row + '-link" class="form-control"/></td>';
    html += '  <td class="text-end"><input type="text" name="banner_image[' + $(element).attr('data-language') + '][' + image_row + '][sort_order]" value="" placeholder="{{ entry_sort_order|escape('js') }}" id="input-image-' + $(element).attr('data-language') + '-' + image_row + '-sort-order" class="form-control"/></td>';
    html += '  <td class="text-end"><button type="button" onclick="$(\'#image-row-' + image_row + '\').remove();" data-bs-toggle="tooltip" title="{{ button_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#image-' + $(element).attr('data-language') + ' tbody').append(html);

    image_row++;
});