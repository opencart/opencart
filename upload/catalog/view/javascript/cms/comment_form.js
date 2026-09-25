import { WebComponent } from '../index.js';
import { loader, ajax, customer } from '../index.js';

customElements.define('cms-comment', class extends WebComponent {
    async render() {

    }

    refresh() {
        e.preventDefault();

        var element = this;

        ajax.post({
            url: $(element).val(),
            dataType: 'html',
            beforeSend: function() {
                $(element).button('loading');
            },
            complete: function() {
                $(element).button('reset');
            },
            success: function(html) {
                $($(element).attr('data-oc-target')).remove();

                $(element).parent().before(html);
                $(element).parent().remove();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

    }

    submit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('index.php?route=cms/comment', form, {
            beforeSend: function() {
                //ref.get('button-comment').button('loading');
            },
            complete: function() {
                //ref.get('button-comment').button('reset');
            },
            success: function(json) {
                console.log(json);

                $('.alert-dismissible').remove();

                $('#form-comment').find('.is-invalid').removeClass('is-invalid');
                $('#form-comment').find('.invalid-feedback').removeClass('d-block');

                if (json['error']) {
                    if (json['error']['warning']) {
                        $('#modal-comment .modal-body').prepend('<ui-alert type="danger">' + json['error']['warning'] + '</ui-alert>');
                    }

                    for (key in json['error']) {
                        $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                        $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                    }
                }

                if (json['success']) {
                    $('#modal-comment .modal-body').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['success'] + '</ui-alert>');

                    $('#input-comment').val('');

                    $($('#form-comment').attr('data-oc-trigger')).trigger('click');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    next() {
        e.preventDefault();

        var element = this;

        $.ajax({
            url: $(element).val(),
            dataType: 'html',
            beforeSend: function() {
                $(element).button('loading');
            },
            complete: function() {
                $(element).button('reset');
            },
            success: function(html) {
                $(element).parent().before(html);
                $(element).parent().remove();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }

    rate() {
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
                    $('#alert').prepend('<ui-alert type="danger">' + json['error'] + '</ui-alert>');
                }

                if (json['success']) {
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['success'] + '</ui-alert>');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });

    }
});

$('#comment').on('click', '.pagination a', function(e) {
    e.preventDefault();

    $('#comment').load(this.href);
});

$('#input-sort').on('change', function(e) {
    $('#comment').load($(this).val());
});

// Add Comment
$('#cms-comment').on('click', '[data-oc-toggle=\'comment\']', function(e) {
    e.preventDefault();

    var element = this;

    $('#form-comment').attr('action', $(element).val());
    $('#form-comment').attr('data-oc-target', $(element).attr('data-oc-target'));
    $('#form-comment').attr('data-oc-trigger', $(element).attr('data-oc-trigger'));

    $('#modal-comment').modal('show');
});