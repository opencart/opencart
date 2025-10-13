import { WebComponent } from './../webcomponent.js';

class XCart extends WebComponent {
    static observed = [''];
    default = HTMLInputElement;
    element = HTMLInputElement;

    event = {
        connected: async () => {
            this.innerHTML = '';

            let data = sessionStorage.getItem('cart');

            if (data !== undefined) {
                let response = fetch('index.php?route=common/cart');

                response.then(this.event.onloaded);
            }
        },
        onloaded: (json) => {

        },
        render: (json) => {
            this.template('', );


            let html = '';

            this.element.innerHTML = html;
        },
        onchange: (e) => {
            this.value = e.target.value;
        },
        changeValue: (e) => {
            this.value = e.detail.value_new;
        }
    };
}

customElements.define('x-cart', XCart);