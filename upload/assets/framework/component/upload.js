class XUpload extends WebComponent {
    static observed = ['checked'];
    element = HTMLInputElement;


    get required() {
        return this.getAttribute('checked') == 1 ? 1 : 0;
    }

    set required(value) {
        if (this.checked != value) {
            this.hasAttribute('required', value);
        }
    }

    get readonly() {
        return this.getAttribute('checked') == 1 ? 1 : 0;
    }

    set readonly(value) {
        if (this.checked != value) {
            this.setAttribute('checked', value);
        }
    }

    event = {
        connected: async () => {
            this.addEventListener('[checked]', this.event.onchecked);

            this.target = this.getAttribute('target') == 1 ? 1 : 0;
            this.value = this.getAttribute('value');

            let html = '';

            html += '<div class="input-group">';
            html += '  <button type="button" class="btn btn-primary"><i class="fa-solid fa-upload"></i> {{ button_upload }}</button>';
            html += '  <input type="text" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '" id="' + this.getAttribute('input-id') + '" class="form-control" readonly/>';
            html += '  <button type="button" disabled class="btn btn-outline-secondary"><i class="fa-solid fa-download"></i></button>';
            html += '  <button type="button" disabled class="btn btn-outline-danger"><i class="fa-solid fa-eraser"></i></button>';
            html += '</div>';



            if (this.hasAttribute('required')) {
                html += ' required';
            }

            if (this.hasAttribute('disabled')) {
                html += ' disabled';
            }



            // data-oc-toggle="upload" data-oc-url="{{ upload }}" data-oc-target="#input-custom-field-{{ custom_field.custom_field_id }}" data-oc-size-max="{{ config_file_max_size }}" data-oc-size-error="{{ error_upload_size }}"
            // data-oc-toggle="download"
            // data-oc-toggle="clear"
            // data-oc-target="#' + this.getAttribute('input-id') + '"
            // data-oc-target="#' + this.getAttribute('input-id') + '"{



            html += '<div class="' + this.getAttribute('input-class') + '">';
            html += '  <input type="hidden" name="' + this.getAttribute('name') + '" value=""/>';
            html += '  <input type="checkbox" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '" class="form-check-input"' + (this.checked ? ' checked' : '') + '/>';
            html += '</div>';

            this.innerHTML = html;

            this.element = this.querySelector('input[type=\'checkbox\']');

            this.element.addEventListener('change', this.event.onchange);

            if (this.hasAttribute('input-id')) {
                this.element.setAttribute('id', this.getAttribute('input-id'));
            }
        },
        onclick: (e) => {
            if (!$(element).prop('disabled')) {



            }
        },
        onchange: (e) => {
            this.checked = e.target.checked ? 1 : 0;
        },
        onchecked: (e) => {
            this.element.checked = e.detail.value_new == 1 ? true : false;
        }
    };
}

customElements.define('x-upload', XUpload);

// Upload
$(document).on('click', '[data-oc-toggle=\'upload\']', function() {
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

        if (typeof timer != 'undefined') {
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
                            $($(element).attr('data-oc-target')).val(json['code']);

                            $(element).parent().find('[data-oc-toggle=\'download\'], [data-oc-toggle=\'clear\']').prop('disabled', false);
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



$(document).on('click', '[data-oc-toggle=\'download\']', function(e) {
    var element = this;

    var value = $($(element).attr('data-oc-target')).val();

    if (value != '') {
        location = 'index.php?route=tool/upload.download&user_token=' + getURLVar('user_token') + '&code=' + value;
    }
});

$(document).on('click', '[data-oc-toggle=\'clear\']', function() {
    var element = this;

    // Images
    var thumb = $(this).attr('data-oc-thumb');

    if (thumb !== undefined) {
        $(thumb).attr('src', $(thumb).attr('data-oc-placeholder'));
    }

    // Custom fields
    var download = $(element).parent().find('[data-oc-toggle=\'download\']');

    if (download.length) {
        $(element).parent().find('[data-oc-toggle=\'download\'], [data-oc-toggle=\'clear\']').prop('disabled', true);
    }

    $($(this).attr('data-oc-target')).val('');
});
