import { InputText } from './text.js';

class InputAutocomplete extends InputText {
	async render() {
		return `
			<label @ref="label" for="input-text">${this.label}</label>
			<input type="text" name="${this.name}" value="${this.value}"${this.label ? ` placeholder="${this.label}"` : ``} id="input-text"${this.disabled ? ` disabled` : ``}${this.readonly ? ` readonly` : ``}${this.required ? ` required` : ``} @ref="input" @input="onInput" @change="onChange" @focusin="onFocusin" @focusout="onFocusout" autocomplete="off" class="form-control"/>
			<div @ref="error" class="invalid-feedback"></div>
			<ul @ref="dropdown" class="dropdown-menu"></ul>
		`;
    }
	
	handleConnect() { 
		//this.internal.setValidity({ valueMissing: true }, "my message");
		//this.input.setCustomValidity("my message");
	}

	onInput(e) {
		this.value = e.target.value;
	}

	onChange(e) { 
		this.value = e.target.value;
	}

    onFocusin() {
        this.dropdown.toggleAttribute('hidden', false);
    }

    onFocusout(e) {
        this.dropdown.toggleAttribute('hidden', false);
    }
	
	handleValue(name, value) {
        this.input.setAttribute('value', value);
		this.internal.setFormValue(value);
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