import { WebComponent } from './../webcomponent.js';

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
            this.addEventListener('[format]', this.event.render);
            this.addEventListener('[value]', this.event.render);

            this.event.render();
        },
        render: () => {
            let date = new Date(Date.parse(this.value));

            console.log(date);
            console.log(new Intl.DateTimeFormat("en-US").format(date));


           // this.innerHTML = date.format(this.format);
        }
    };
}

customElements.define('x-datetime', XDatetime);

