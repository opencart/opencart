import { WebComponent } from '../component.js';

customElements.define('input-upload', class extends WebComponent {
    static observed = [
        'name',
        'value',
        'disabled',
        'required',
        'readonly'
    ];

    get name() {
        return this.getAttribute('name');
    }

    set name(value) {
        if (this.name != value) this.setAttribute('name', value);
    }

    get value() {
        return this.getAttribute('value');
    }

    set value(value) {
        if (this.value != value) this.setAttribute('value', value);
    }

    get disabled() {
        return this.hasAttribute('disabled');
    }

    set disabled(value) {
        value ? this.setAttribute('disabled', '') : this.removeAttribute('disabled');
    }

    get required() {
        return this.hasAttribute('required');
    }

    set required(value) {
        value ? this.setAttribute('required', '') : this.removeAttribute('required');
    }

    get readonly() {
        return this.hasAttribute('readonly');
    }

    set readonly(value) {
       value ? this.setAttribute('readonly', '') : this.removeAttribute('readonly');
    }

    render() {

        // data-oc-toggle="upload"
        // data-oc-url="{{ upload }}"
        // data-oc-target="#input-custom-field-{{ custom_field.custom_field_id }}"
        // data-oc-size-max="{{ config_file_max_size }}"
        // data-oc-size-error="{{ error_upload_size }}"

        let html = '';

        html += '<div class="input-group">';
        html += '  <button type="button" class="btn btn-primary" data-on="click:onClick"><i class="fa-solid fa-upload"></i> {{ button_upload }}</button>';
        html += '  <input type="text" name="' + this.name + '" value="' + this.value + '" id="' + this.getAttribute('input-id') + '" class="form-control" data-on="click:onClick"/>';
        html += '  <button type="button" class="btn btn-outline-secondary" data-on="click:download" disabled><i class="fa-solid fa-download"></i></button>';
        html += '  <button type="button" class="btn btn-outline-danger" data-on="click:cancel" disabled><i class="fa-solid fa-eraser"></i></button>';
        html += '</div>';

        return html;
    }

    onclick(e) {
        if (!this.disabled) {
            let form = document.getElementById('#form-upload');

            form.remove();

            $('#form-upload input[name=\'file\']').trigger('click');

            $('#form-upload input[name=\'file\']').on('change', function(e) {
                if ((this.files[0].size / 1024) > $(element).attr('data-oc-size-max')) {
                    alert($(element).attr('data-oc-size-error'));

                    $(this).val('');
                }
            });

            document.body.prepend();
        }
    }

    onchange(e) {
        this.checked = e.target.checked ? 1 : 0;
    }

    onchecked(e) {
        this.element.checked = e.detail.value_new == 1 ? true : false;
    }

    download() {
        var element = this;

        var value = $($(element).attr('data-oc-target')).val();

        if (value != '') {
            location = 'index.php?route=tool/upload.download&user_token=' + getURLVar('user_token') + '&code=' + value;
        }
    }

    clear() {
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
    }
});

customElements.define('form-upload', class extends WebComponent {


    render() {
        return '<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" value=""/></form>';
    }

    onClick() {
        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        let timer = setInterval(this.timeout, 500);


    }

    timeout() {



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
    }
});