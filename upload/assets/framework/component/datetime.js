import { WebComponent } from './../library/webcomponent.js';

class XDatetime extends WebComponent {
    static observed = ['format', 'value'];

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

    event = {
        connected: async () => {
            this.addEventListener('[format]', this.event.format);
            this.addEventListener('[value]', this.event.format);
        },
        format: () => {
            let date = new Date(Date.parse(this.value));

            console.log(date);
            console.log(new Intl.DateTimeFormat("en-US").format(date));

            this.innerHTML = string;
        }
    };
}

customElements.define('x-datetime', XDatetime);

