$('#button-filter').on('click', function() {
    filter = [];

    $('input[name^=\'filter\']:checked').each(function(element) {
        filter.push(this.value);
    });

    location = '{{ action|escape('js') }}&filter=' + filter.join(',');
});