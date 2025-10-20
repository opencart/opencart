import { WebComponent } from './../webcomponent.js';

class XCart extends WebComponent {
    event = {
        connected: async () => {
            let html = '';

            html += '<div class="dropdown d-grid">';
            html += '  <button type="button" data-bs-toggle="dropdown" class="btn btn-lg btn-dark d-block dropdown-toggle"><i class="fa-solid fa-cart-shopping"></i> {{ text_items }}</button>';
            html += '  <ul class="dropdown-menu dropdown-menu-end p-2"></ul>';
            html += '</div>';

            this.innerHTML = html;

            let data = sessionStorage.getItem('cart');

            if (data !== undefined) {
                let response = this.event.fetch('index.php?route=common/cart.json');

                response.then(this.event.onloaded);
            }
        },
        fetch: async (url) => {
            let response = await fetch(url);

            if (response.status == 200) {
                return response.json();
            }
        },
        onloaded: (test) => {
            console.log(test);

            let language = this.language('cart');

            this.template('cart', test);


        }
    };
}

customElements.define('x-cart', XCart);