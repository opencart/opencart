
$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=localisation/country&user_token={{ user_token }}&' + url);

    $('#list').load('index.php?route=localisation/country.list&user_token={{ user_token }}&' + url);
});