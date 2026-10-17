import { InputText } from './text.js';

class InputAutocomplete extends InputText {
    async render() {
        let html = '<slot name="label"></slot>';

        html += '<input type="text" `name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '" placeholder="{{ entry_name }}" autocomplete="off"/>';

        if (this.hasAttribute('placeholder')) {
            html += ' placeholder="' + this.getAttribute('placeholder') + '"';
        }

        let error = this.state.get('error');

        html += ' class="form-control' + (error ? ' is-invalid' : '') + '" @ref="input" @input="handleInput" @change="handleChange"';

        if (this.hasAttribute('disabled')) {
            html += ' disabled';
        }

        if (this.hasAttribute('readonly')) {
            html += ' readonly';
        }

        if (this.hasAttribute('required')) {
            html += ' required';
        }

        html += '/>';
        html += '<ul class="dropdown-menu" @ref="dropdown"></ul>';

        if (error) {
            html += '<div @ref="feedback" class="invalid-feedback">' + error + '</div>';
        }

        return html;
    }

    focusin() {
        this.request();
    }

    focusout() {
        if (!e.relatedTarget || !$(e.relatedTarget).hasClass('dropdown-item')) {
            $dropdown.removeClass('show');
        }
    }

    input() {

        element.request();
    }

    handleClick() {
        e.preventDefault();

        var value = $(this).attr('href');

        if (element.items[value] !== undefined) {
            element.select(element.items[value]);

            $dropdown.removeClass('show');
        }
    }

    timeout() {
        // Request
        this.request = function() {
            clearTimeout(this.timer);

            $('#autocomplete-loading').remove();

            $dropdown.prepend('<li id="autocomplete-loading"><span class="dropdown-item text-center disabled"><i class="fa-solid fa-circle-notch fa-spin"></i></span></li>');
            $dropdown.addClass('show');

            this.timer = setTimeout(function(object) {
                object.source($(object).val(), $.proxy(object.response, object));
            }, 150, this);
        }
    }

    // Response
    response(json) {
        var html = '';
        var category = {};
        var name;
        var i = 0, j = 0;

        if (json.length) {
            for (i = 0; i < json.length; i++) {
                // update element items
                this.items[json[i]['value']] = json[i];

                if (!json[i]['category']) {
                    // ungrouped items
                    html += '<li><a href="' + json[i]['value'] + '" class="dropdown-item">' + json[i]['label'] + '</a></li>';
                } else {
                    // grouped items
                    name = json[i]['category'];

                    if (!category[name]) {
                        category[name] = [];
                    }

                    category[name].push(json[i]);
                }
            }

            for (name in category) {
                html += '<li><h6 class="dropdown-header">' + name + '</h6></li>';

                for (j = 0; j < category[name].length; j++) {
                    html += '<li><a href="' + category[name][j]['value'] + '" class="dropdown-item">' + category[name][j]['label'] + '</a></li>';
                }
            }
        }

        $dropdown.html(html);
    }
}

customElements.define('input-autocomplete', InputAutocomplete);