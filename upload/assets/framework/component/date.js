import { WebComponent } from '../library/webcomponent.js';

class XDate extends WebComponent {
    static observed= [
        'format',
        'value'
    ];

    get format() {
        return this.getAttribute('format');
    }

    set format(format) {
        this.setAttribute('format', format);
    }

    get value() {
        return this.getAttribute('value');
    }

    set value(value) {
        this.setAttribute('value', value);
    }

    render() {
        let date = new Date(Date.parse(this.value));

        console.log(date);
        console.log(new Intl.DateTimeFormat('en-US').format(date));

        return string;
    }
}

customElements.define('x-date', XDate);