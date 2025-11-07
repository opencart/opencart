$('#form-filter').on('submit', function(e) {
    e.preventDefault();

    let url = $(this).serialize();

    console.log(url);

    window.history.pushState({}, null, 'index.php?route=marketplace/marketplace&user_token={{ user_token }}&' + url);

    $.ajax({
        url: 'index.php?route=marketplace/marketplace.list&user_token={{ user_token }}&' + url,
        dataType: 'html',
        beforeSend: function() {
            $('#button-filter').button('loading');
            $('#form-filter .form-control, #form-filter .form-select').prop('disabled', true);
            $('#filter-license .btn').addClass('disabled');

        },
        complete: function() {
            $('#button-filter').button('reset');
            $('#form-filter .form-control, #form-filter .form-select').prop('disabled', false);
            $('#filter-license .btn').removeClass('disabled');
        },
        success: function(html) {
            $('#list').html(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#filter-license input').on('change', function(e) {
    $('#form-filter').trigger('submit');
});

$('#input-sort').on('change', function(e) {
    $('#form-filter').trigger('submit');
});

$('#button-api').on('click', function(e) {
    $('#modal-api').remove();

    $.ajax({
        url: 'index.php?route=marketplace/api&user_token={{ user_token }}',
        dataType: 'html',
        beforeSend: function() {
            $('#button-api').button('loading');
        },
        complete: function() {
            $('#button-api').button('reset');
        },
        success: function(html) {
            $('body').append(html);

            $('#modal-api').modal('show');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});


$('#button-buy').on('click', function(e) {
    e.preventDefault();

    $('#modal-purchase').remove();

    html = '<div id="modal-purchase" class="modal">';
    html += '  <div class="modal-dialog">';
    html += '    <div class="modal-content">';
    html += '      <div class="modal-header">';
    html += '        <h5 class="modal-title">{{ text_purchase|escape('js') }}</h5>';
    html += '        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>';
    html += '      </div>';
    html += '      <div class="modal-body">';
    html += '        <p>{{ text_pin|escape('js') }}</p>';
    html += '        <p>{{ text_secure|escape('js') }}</p>';
    html += '        <div class="mb-3">';
    html += '          <label for="input-pin">{{ entry_pin|escape('js') }}</label>';
    html += '          <input type="password" name="pin" value="" placeholder="{{ entry_pin|escape('js') }}" id="input-pin" class="form-control"/>';
    html += '        </div>';
    html += '        <div class="mb-3 text-end">';
    html += '          <div class="text-end"><a href="https://www.opencart.com/index.php?route=support/contact" class="btn btn-light btn-lg" target="_blank">{{ button_forgot_pin|escape('js') }}</a></div>';
    html += '          <button type="button" id="button-purchase" class="btn btn-primary btn-lg">{{ button_purchase|escape('js') }}</button>';
    html += '        </div>';
    html += '      </div>';
    html += '    </div>';
    html += '  </div>';
    html += '</div>';

    $('body').append(html);

    $('#modal-purchase').modal('show');
});

$('body').on('click', '#modal-purchase #button-purchase', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: 'index.php?route=marketplace/marketplace.purchase&user_token={{ user_token }}&extension_id={{ extension_id }}',
        type: 'post',
        data: $('#input-pin'),
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#modal-purchase .modal-body').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#modal-purchase').modal('hide');

                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#download').load('index.php?route=marketplace/marketplace.extension&user_token={{ user_token }}&extension_id={{ extension_id }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Download
$('#download').load('index.php?route=marketplace/marketplace.extension&user_token={{ user_token }}&extension_id={{ extension_id }}');

$('#tab-download').on('click', '.btn-primary', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).val(),
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#download').before('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>');
            }

            if (json['success']) {
                $('#download').before('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + '  <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#tab-download').on('click', '.dropdown-item', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('href'),
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#download').before('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + '  <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div></div>');
            }

            if (json['success']) {
                $('#download').before('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + '  <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#download').load('index.php?route=marketplace/marketplace.extension&user_token={{ user_token }}&extension_id={{ extension_id }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Comment
$('#comment').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#comment').load(this.href);
});

$('#comment').load('index.php?route=marketplace/marketplace.comment&user_token={{ user_token }}&extension_id={{ extension_id }}');

// Add comment
$('#button-comment').on('click', function() {
    $.ajax({
        url: 'index.php?route=marketplace/marketplace.addComment&user_token={{ user_token }}&extension_id={{ extension_id }}',
        type: 'post',
        dataType: 'json',
        data: 'comment=' + encodeURIComponent($('#input-history-comment').val()),
        beforeSend: function() {
            $('#button-comment').button('loading');
        },
        complete: function() {
            $('#button-comment').button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#comment').load('index.php?route=marketplace/marketplace.comment&user_token={{ user_token }}&extension_id={{ extension_id }}');

                $('#input-history-comment').val('');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Next replies
$('#comment').on('click', '.btn-outline-secondary', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('href'),
        dataType: 'html',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(html) {
            $(element).parent().parent().parent().append(html);

            $(element).parent().remove();
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Reply
$('#comment').on('click', '.btn-link', function(e) {
    e.preventDefault();

    $(this).parent().parent().find('.reply-input-box').toggle();
});

// Add reply
$('#comment').on('click', '.btn-primary', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('href'),
        type: 'post',
        dataType: 'json',
        data: 'comment=' + encodeURIComponent($(element).parents('.reply-input-box').find('textarea[name=\'comment\']').val()),
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            $('.alert-dismissible').remove();

            if (json['error']) {
                $(element).parents('.reply-input-box').before('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $(element).parents('.reply-input-box').before('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $(element).parents('.reply-input-box').parents('.media').find('.reply-refresh').last().trigger('click');

                $(element).parents('.reply-input-box').find('textarea[name=\'comment\']').val('');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Refresh
$('#comment').on('click', '.reply-refresh', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('href'),
        dataType: 'html',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(html) {
            $(element).parent().replaceWith(html);
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$(document).ready(function() {
    $('.thumbnails').magnificPopup({
        type: 'image',
        delegate: 'a',
        gallery: {
            enabled: true
        }
    });
});