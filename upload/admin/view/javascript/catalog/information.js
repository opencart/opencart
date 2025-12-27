$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=catalog/information&user_token={{ user_token }}&' + url);

    $('#list').load('index.php?route=catalog/information.list&user_token={{ user_token }}&' + url);
});

$('textarea[data-oc-toggle=\'ckeditor\']').ckeditor({
    language: '{{ ckeditor }}'
});