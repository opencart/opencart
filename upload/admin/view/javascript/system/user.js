$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=user/user&user_token={{ user_token }}&' + url);

    $('#list').load('index.php?route=user/user.list&user_token={{ user_token }}&' + url);
});

$('#input-username').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=user/user.autocomplete&user_token={{ user_token }}&filter_username=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['username'],
                        value: item['user_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-username').val(decodeHTMLEntities(item['label']));
    }
});

$('#input-name').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=user/user.autocomplete&user_token={{ user_token }}&filter_name=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['name'],
                        value: item['user_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-name').val(decodeHTMLEntities(item['label']));
    }
});

$('#input-email').autocomplete({
    'source': function(request, response) {
        $.ajax({
            url: 'index.php?route=user/user.autocomplete&user_token={{ user_token }}&filter_email=' + encodeURIComponent(request),
            dataType: 'json',
            success: function(json) {
                response($.map(json, function(item) {
                    return {
                        label: item['email'],
                        value: item['user_id']
                    }
                }));
            }
        });
    },
    'select': function(item) {
        $('#input-email').val(decodeHTMLEntities(item['label']));
    }
});

$('table tbody label').on('click', function() {
    var checked = $(this).parent().parent().find(':checkbox:first').prop('checked');

    $(this).parent().parent().find(':checkbox').prop('checked', !checked);
    $(this).parent().parent().find(':checkbox').prop('checked', !checked);
});

$('#authorize').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#authorize').load(this.href);
});

$('#authorize').on('click', 'a', function(e) {
    e.preventDefault();

    var element = this;

    if (confirm('{{ text_confirm }}')) {
        $.ajax({
            url: $(element).attr('href'),
            dataType: 'json',
            beforeSend: function() {
                $(element).prop('disabled', true);
            },
            complete: function() {
                $(element).prop('disabled', false);
            },
            success: function(json) {
                $('.alert-dismissible').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                }

                if (json['error']) {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                if (json['success']) {
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    $('#authorize').load('index.php?route=user/user.authorize&user_token={{ user_token }}&user_id={{ user_id }}');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});

$('#login').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#login').load(this.href);
});

