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
                let response = await fetch('index.php?route=common/cart.json');

                let json = response.json();

               // response.then(this.event.onloaded);
            }
        },
        onloaded: (json) => {
            console.log(json.json());



        }
    };
}

customElements.define('x-cart', XCart);