$(document).ready(function() {
    $.ajax({
        url: 'index.php?route=extension/opencart/dashboard/map.map&user_token={{ user_token }}',
        dataType: 'json',
        success: function(json) {
            data = [];

            for (i in json) {
                data[i] = json[i]['total'];
            }

            $('#vmap').vectorMap({
                map: 'world_en',
                backgroundColor: '#FFFFFF',
                borderColor: '#FFFFFF',
                color: '#9FD5F1',
                hoverOpacity: 0.7,
                selectedColor: '#666666',
                enableZoom: true,
                showTooltip: true,
                values: data,
                normalizeFunction: 'polynomial',
                onLabelShow: function(event, label, code) {
                    if (json[code]) {
                        label.html('<strong>' + label.text() + '</strong><br />' + '{{ text_order }} ' + json[code]['total'] + '<br />' + '{{ text_sale }} ' + json[code]['amount']);
                    }
                }
            });
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});