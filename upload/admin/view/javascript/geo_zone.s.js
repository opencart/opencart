var zone_to_geo_zone_row = {{ zone_to_geo_zone_row }};

$('#button-geo-zone').on('click', function() {
    html = '<tr id="zone-to-geo-zone-row-' + zone_to_geo_zone_row + '">';
    html += '  <td><x-country name="zone_to_geo_zone[' + zone_to_geo_zone_row + '][country_id]" value="{{ zone_to_geo_zone.country_id }}" id="x-country-' + zone_to_geo_zone_row + '" input-id="input-country-' + zone_to_geo_zone_row + '" input-class="form-select"><option value="0">{{ text_select }}</option></x-country></td>';
    html += '  <td><x-zone name="zone_to_geo_zone[' + zone_to_geo_zone_row + '][zone_id]" value="{{ zone_to_geo_zone.zone_id }}" target="x-country-' + zone_to_geo_zone_row + '" input-id="input-zone-' + zone_to_geo_zone_row + '" input-class="form-select" required><option value="0">{{ text_all_zones }}</option></x-zone></td>';
    html += '  <td class="text-end"><button type="button" onclick="$(\'#zone-to-geo-zone-row-' + zone_to_geo_zone_row + '\').remove();" data-bs-toggle="tooltip" title="{{ button_remove|escape('js') }}" class="btn btn-danger"><i class="fa-solid fa-minus-circle"></i></button></td>';
    html += '</tr>';

    $('#zone-to-geo-zone tbody').append(html);

    zone_to_geo_zone_row++;
});