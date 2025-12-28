$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=marketing/marketing&user_token={{ user_token }}&' + url);

    $('#list').load('index.php?route=marketing/marketing.list&user_token={{ user_token }}&' + url);
});

$('#input-code').on('keyup', function(e) {
    $('#input-example1').attr('value', '{{ store }}?tracking=' + $('#input-code').val());

    $('#input-example2').attr('value', '{{ store }}index.php?route=common/home&tracking=' + $('#input-code').val());
});

$('#input-code').trigger('keyup');

$('#report').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#report').load(this.href);
});