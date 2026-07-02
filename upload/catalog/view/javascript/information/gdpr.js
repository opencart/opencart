export default class extends Controller {
    async connected() {

    }

    onChange() {
        if (this.value == 'remove') {
            $('#collapse-remove').slideDown();
        } else {
            $('#collapse-remove').slideUp();
        }
    }
};

$('input[name=\'action\']').on('change', function() {

    if (this.value == 'remove') {
        $('#collapse-remove').slideDown();
    } else {
        $('#collapse-remove').slideUp();
    }
});