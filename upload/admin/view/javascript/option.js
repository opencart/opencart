let option_value_row = 0;

$('#button-option-value').on('click', () => {
    let clone = $('#template-option-value').content.cloneNode(true);

    $('#option-value tbody').html(clone);
});

$('#input-type').on('change', (e) => {
    if (e.target.value == 'select' || e.target.value == 'radio' || e.target.value == 'checkbox' || e.target.value == 'image') {
        $('#display-option-value').show();
    } else {
        $('#display-option-value').hide();
    }

    if (e.target.value == 'text' || e.target.value == 'textarea') {
        $('#display-validation').show();
    } else {
        $('#display-validation').hide();
    }
});

$('#input-type').trigger('change');