import { WebComponent } from '../../engine.js';

customElements.define('input-upload', class extends WebComponent {
    static formAssociated = true;
    static observedAttributes = [
        'name',
        'value',
        'disabled',
        'required',
        'readonly'
    ];

    states() {
        return {
            value: '',
            error: '',
            required: false,
            disabled: false,
            readonly: false
        };
    }

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
        return '<form enctype="multipart/form-data" @submit="handleSubmit" style="display: none;"><input type="file" name="file" value=""/></form>';

        let html = '';

        html += '<div class="input-group">';
        html += '  <button type="button" class="btn btn-primary" @click="handleClick"><i class="fa-solid fa-upload"></i> {{ button_upload }}</button>';
        html += '  <input type="text" name="' + this.name + '" value="' + this.value + '" id="' + this.getAttribute('input-id') + '" class="form-control" data-on="click:onClick"/>';
        html += '  <button type="button" class="btn btn-outline-secondary" @click="download" disabled><i class="fa-solid fa-download"></i></button>';
        html += '  <button type="button" class="btn btn-outline-danger" @click="cancel" disabled><i class="fa-solid fa-eraser"></i></button>';
        html += '</div>';

        return html;
    }

    handleClick(e) {
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

    handleChange(e) {
        this.checked = e.target.checked ? 1 : 0;
    }

    handleChecked(e) {
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

        if (thumb instanceof Map) {
            $(thumb).attr('src', $(thumb).attr('data-oc-placeholder'));
        }

        // Custom fields
        var download = $(element).parent().find('[data-oc-toggle=\'download\']');

        if (download.length) {
            $(element).parent().find('[data-oc-toggle=\'download\'], [data-oc-toggle=\'clear\']').prop('disabled', true);
        }

        $($(this).attr('data-oc-target')).val('');
    }

    handleClick() {
        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        let timer = setInterval(this.timeout, 500);


    }

    async handleSubmit(e) {
        e.preventDefault();

        console.log('works');

        //if (customer.isLogged()) return;

        let form = new FormData(this.form);

        console.log(Object.fromEntries(form));

        await ajax.post('action.php?route=account/register.save&language=' + local.get('language') + '&register_token=' + this.token, form, {
            handleSend: () => {
                this.submitter.state.set('loading', true);
            },
            handleComplete: () => {
                this.submitter.state.set('loading', false);
            },
            handleSuccess: async (json) => {
                console.log(json);
                console.log(json.get('error'));

                // Display error messages
                if (json.has('error')) {
                    let errors = Object.entries(json.get('error'));

                    console.log(errors);

                    for (let [key, value] of errors) {
                        if (key === 'warning') {
                            this.state.set('error', 'warning');
                        }

                        if (this[key]) {
                            this[key].state.set('error', value);
                        }
                    }
                }

                // Display success message
                if (json.has('success')) {
                    this.state.set('success', json.get('success'));

                    this.update();
                }
            },
            handleError: (e) => {
                console.log('onError', e);
            }
        });
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

                    if (json.has('success')) {
                        alert(json.get('success'));
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