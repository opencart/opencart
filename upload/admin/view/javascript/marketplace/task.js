$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    window.history.pushState({}, null, 'index.php?route=marketplace/task&user_token={{ user_token }}&' + url);

    $('#list').load('index.php?route=marketplace/task.list&user_token={{ user_token }}&' + url);
});

$('#button-refresh').on('click', function(e) {
    $.ajax({
        url: $('#form-task').attr('data-oc-load'),
        dataType: 'html',
        beforeSend: function() {
            $('#button-refresh').button('loading');
        },
        complete: function() {
            $('#button-refresh').button('reset');
        },
        success: function(html) {
            $('#list').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#button-start').on('click', function(e) {
    $.ajax({
        url: 'index.php?route=marketplace/task.start&user_token={{ user_token }}',
        dataType: 'json',
        beforeSend: function() {
            $('#button-start').button('loading');
        },
        complete: function() {
            $('#button-start').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                window.setTimeout(() => {
                    $('#list').load($('#form-task').attr('data-oc-load'));
                }, 3000);
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#button-pause').on('click', function(e) {
    $.ajax({
        url: 'index.php?route=marketplace/task.pause&user_token={{ user_token }}',
        dataType: 'json',
        beforeSend: function() {
            $('#button-pause').button('loading');
        },
        complete: function() {
            $('#button-pause').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#list').load($('#form-task').attr('data-oc-load'));
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#button-clear').on('click', function(e) {
    $.ajax({
        url: 'index.php?route=marketplace/task.clear&user_token={{ user_token }}',
        dataType: 'json',
        beforeSend: function() {
            $('#button-clear').button('loading');
        },
        complete: function() {
            $('#button-clear').button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#list').load($('#form-task').attr('data-oc-load'));
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});