let option_value_row = 0;

$('#button-option-value').on('click', () => {
    let element = $('#template-option-value');

    $('#option-value tbody').append(element[0].content.cloneNode(true));
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