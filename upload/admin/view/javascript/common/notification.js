// View
$('#list').on('click', 'table a', function(e) {
    e.preventDefault();

    var element = this;

    $('#modal-notification').remove();

    $.ajax({
        url: $(element).attr('href'),
        dataType: 'html',
        success: function(html) {
            $('body').append(html);

            $('#modal-notification').modal('show');

            $(element).parent().parent().removeClass('table-primary');
            $(element).parent().parent().find('.fa-bell').removeClass('fas').addClass('far');
            $(element).removeClass('font-weight-bold');
            $(element).parent().parent().find('.btn-primary').removeClass('btn-primary').addClass('btn-outline-primary');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#list').on('click', '.btn', function(e) {
    var element = this;

    $('#modal-notification').remove();

    $.ajax({
        url: $(element).attr('value'),
        dataType: 'html',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(html) {
            $('body').append(html);

            $('#modal-notification').modal('show');

            $(element).parent().parent().removeClass('table-primary');
            $(element).parent().parent().find('.fa-bell').removeClass('fas').addClass('far');
            $(element).parent().parent().find('a').removeClass('font-weight-bold');
            $(element).removeClass('btn-primary').addClass('btn-outline-primary');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

// Delete
$('#button-delete').on('click', function(e) {
    e.preventDefault();

    var element = this;

    if (confirm('{{ text_confirm }}')) {
        $.ajax({
            url: 'index.php?route=tool/notification.delete&user_token={{ user_token }}',
            type: 'post',
            data: $('#notification input[name^=\'selected\']:checked').serialize(),
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
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button</div>');
                }

                if (json['success']) {
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button</div>');

                    $('#list').load($('#form-notification').attr('data-oc-load'));
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});