import { registry, factory, loader } from './index.js';

/*
$(document).ready(function() {
    // Tooltip
    let oc_tooltip = function() {
        // Get tooltip instance
        let tooltip = bootstrap.Tooltip.getInstance(this);

        if (!tooltip) {
            // Apply to current element
            tooltip = bootstrap.Tooltip.getOrCreateInstance(this);
            tooltip.show();
        }
    }

    $(document).on('mouseenter', '[data-bs-toggle=\'tooltip\']', oc_tooltip);

    $(document).on('click', 'button', function() {
        $('.tooltip').remove();
    });
});

$(document).ready(function() {
    // Observe
    +function($) {
        $.fn.observe = function(callback) {
            observer = new MutationObserver(callback);

            observer.observe($(this)[0], {
                characterData: false,
                childList: true,
                attributes: false
            });
        };
    }(jQuery);

    $('#alert').observe(function() {
        window.setTimeout(function() {
            $('#alert .alert-dismissible').fadeTo(3000, 0, function() {
                $(this).remove();
            });
        }, 3000);
    });

    // Button
    +function($) {
        $.fn.button = function(state) {
            return this.each(function() {
                let element = this;

                if (state == 'loading') {
                    this.html = $(element).html();

                    $(element).width($(element).width()).html('<i class="fa-solid fa-circle-notch fa-spin text-light"></i>');
                }

                if (state == 'reset') {
                    $(element).width('').html(this.html);
                }

                // If button
                if ($(element).is('button')) {
                    if (state == 'loading') {
                        this.state = $(element).prop('disabled');

                        $(element).prop('disabled', true);
                    }

                    if (state == 'reset') {
                        $(element).prop('disabled', this.state);
                    }
                }

                // If link
                if ($(element).is('a')) {
                    if (state == 'loading') {
                        this.state = $(element).hasClass('disabled');

                        $(element).addClass('disabled');
                    }

                    if (state == 'reset') {
                        if (this.state) {
                            $(element).addClass('disabled');
                        } else {
                            $(element).removeClass('disabled');
                        }
                    }
                }
            });
        };
    }(jQuery);
});

// Forms
$(document).on('submit', 'form', function (e) {
    var element = this;
    var button = (e.originalEvent !== undefined && e.originalEvent.submitter !== undefined) ? e.originalEvent.submitter : '';

    if ($(element).attr('data-oc-toggle') == 'ajax' || $(button).attr('data-oc-toggle') == 'ajax') {
        e.preventDefault();

        var form = e.target;
        var action = $(button).attr('formaction') || $(form).attr('action');
        var method = $(button).attr('formmethod') || $(form).attr('method') || 'post';
        var enctype = $(button).attr('formenctype') || $(form).attr('enctype') || 'application/x-www-form-urlencoded';

        console.log(e);
        console.log(element);
        console.log('action ' + action);
        console.log('button ' + button);
        console.log('method ' + method);
        console.log('enctype ' + enctype);
        console.log($(element).serialize());

        // https://github.com/opencart/opencart/issues/9690
        if (typeof CKEDITOR != 'undefined') {
            for (instance in CKEDITOR.instances) {
                CKEDITOR.instances[instance].updateElement();
            }
        }

        $.ajax({
            url: action.replaceAll('&amp;', '&'),
            type: method,
            data: $(form).serialize(),
            dataType: 'json',
            contentType: enctype,
            beforeSend: function () {
                $(button).button('loading');
            },
            complete: function () {
                $(button).button('reset');
            },
            success: function (json, textStatus) {
                console.log(json);
                console.log(textStatus);

                $('.alert-dismissible').remove();
                $(element).find('.is-invalid').removeClass('is-invalid');
                $(element).find('.invalid-feedback').removeClass('d-block');

                if (json['redirect']) {
                    location = json['redirect'];
                }

                if (typeof json['error'] == 'string') {
                    $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                }

                if (typeof json['error'] == 'object') {
                    if (json['error']['warning']) {
                        $('#alert').prepend('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error']['warning'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
                    }

                    for (let key in json['error']) {
                        $('#input-' + key.replaceAll('_', '-')).addClass('is-invalid').find('.form-control, .form-select, .form-check-input, .form-check-label').addClass('is-invalid');
                        $('#error-' + key.replaceAll('_', '-')).html(json['error'][key]).addClass('d-block');
                    }
                }

                if (json['success']) {
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                    // Refresh
                    var url = $(form).attr('data-oc-load');
                    var target = $(form).attr('data-oc-target');

                    if (url !== undefined && target !== undefined) {
                        $(target).load(url);
                    }
                }

                // Replace any form values that correspond to form names.
                for (key in json) {
                    $(element).find('[name=\'' + key + '\']').val(json[key]);
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});

// Upload
$(document).on('click', 'button[data-oc-toggle=\'upload\']', function() {
    var element = this;

    if (!$(element).prop('disabled')) {
        $('#form-upload').remove();

        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" value=""/></form>');

        $('#form-upload input[name=\'file\']').trigger('click');

        $('#form-upload input[name=\'file\']').on('change', function(e) {
            if ((this.files[0].size / 1024) > $(element).attr('data-oc-size-max')) {
                alert($(element).attr('data-oc-size-error'));

                $(this).val('');
            }
        });

        if (typeof timer !== 'undefined') {
            clearInterval(timer);
        }

        var timer = setInterval(function() {
            if ($('#form-upload input[name=\'file\']').val() != '') {
                clearInterval(timer);

                $.ajax({
                    url: $(element).attr('data-oc-url'),
                    type: 'post',
                    data: new FormData($('#form-upload')[0]),
                    dataType: 'json',
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function() {
                        $(element).button('loading');
                    },
                    complete: function() {
                        $(element).button('reset');
                    },
                    success: function(json) {
                        console.log(json);

                        if (json['error']) {
                            alert(json['error']);
                        }

                        if (json['success']) {
                            alert(json['success']);
                        }

                        if (json['code']) {
                            $($(element).attr('data-oc-target')).attr('value', json['code']);
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    }
});


$(document).ready(function() {
    // Product List
    $('#button-list').on('click', function() {
        var element = this;

        $('#product-list').attr('class', 'row row-cols-1 product-list');

        $('#button-grid').removeClass('active');
        $('#button-list').addClass('active');

        localStorage.setItem('display', 'list');
    });

    // Product Grid
    $('#button-grid').on('click', function() {
        var element = this;

        // What a shame bootstrap does not take into account dynamically loaded columns
        $('#product-list').attr('class', 'row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3');

        $('#button-list').removeClass('active');
        $('#button-grid').addClass('active');

        localStorage.setItem('display', 'grid');
    });

    // Local Storage
    if (localStorage.getItem('display') == 'list') {
        $('#product-list').attr('class', 'row row-cols-1 product-list');
        $('#button-list').addClass('active');
    } else {
        $('#product-list').attr('class', 'row row-cols-1 row-cols-sm-2 row-cols-md-2 row-cols-lg-3');
        $('#button-grid').addClass('active');
    }


    $('body').on('click', '.modal-link', function(e) {
        e.preventDefault();

        var element = this;

        $('#modal-information').remove();

        $.ajax({
            url: $(element).attr('href'),
            dataType: 'html',
            success: function(html) {
                $('body').append(html);

                $('#modal-information').modal('show');
            }
        });
    });

});
*/
