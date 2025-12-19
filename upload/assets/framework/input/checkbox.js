import { WebComponent } from '../library/webcomponent.js';

class XCheckbox extends WebComponent {
    static observed = ['checked'];
    element = HTMLInputElement;

    get checked() {
        return this.getAttribute('checked') == 1 ? 1 : 0;
    }

    set checked(value) {
        if (this.checked != value) {
            this.setAttribute('checked', value);
        }
    }

    async connected() {
        this.addEventListener('[checked]', this.onChecked);

        let html = '';

        html = '<div class="row mb-3{% if custom_field.required %} required{% endif %}">';
        html = '  <label class="col-sm-2 col-form-label">{{custom_field.name}}</label>';
        html = '    <div class="col-sm-10">';
        html = '      <div id="' +  + '">';

        for (let custom_field_value of custom_field.custom_field_value) {
            html = '<div class"form-check">';
            html = '  <input type="checkbox" name="custom_field[' + custom_field.custom_field_id + '][]" value="{{ custom_field_value.custom_field_value_id }}" id="input-custom-value-{{ custom_field_value.custom_field_value_id }}" class="form-check-input"{% if address_custom_field[custom_field.custom_field_id] and custom_field_value.custom_field_value_id in address_custom_field[custom_field.custom_field_id] %} checked{% endif %}/>';
            html = '  <label for="input-custom-value-{{ custom_field_value.custom_field_value_id }}" className="form-check-label">{{custom_field_value.name}}</label>';
            html = '</div>';
        }

        html = '       <div id="error-custom-field-{{ custom_field.custom_field_id }}" className="invalid-feedback"></div>';



        html = '       </div>';

        html = '   </div>';
        html = '</div>';



        html += '<div class="form-control" style="height: 150px; overflow: auto;">';
        html += '  <input type="hidden" name="' + this.getAttribute('name') + '" value=""/>';
        html += '  <input type="checkbox" name="' + this.getAttribute('name') + '" value="' + this.getAttribute('value') + '" class="form-check-input"' + (this.checked ? ' checked' : '') + '/>';
        html += '</div>';

        this.innerHTML = html;

        this.element = this.querySelector('input[type=\'checkbox\']');

        this.element.addEventListener('change', this.onChange);

        if (this.hasAttribute('input-id')) {
            this.element.setAttribute('id', this.getAttribute('input-id'));
        }
    };

    onChange(e) {
        this.checked = e.target.checked ? 1 : 0;
    }

    onChecked(e) {
        this.element.checked = e.detail.value_new == 1 ? true : false;
    }
}

customElements.define('x-radio', XRadio);