import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// library
const cart = await loader.library('cart');

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/cart');

class CommonCart extends WebComponent {
    async connected() {
        let data = {};

        let response = loader.template('common/cart', { ...data,  ...language });

        response.then(this.render.bind(this));
        response.then(this.addEvent.bind(this));
    };

    render(html) {
        this.innerHTML = html;
    }

    addEvent() {
        let form = document.getElementById('form-cart');

        form.addEventListener('submit', this.onSubmit);
    }

    onSubmit(e) {
        e.preventDefault();

        this.request.fetch({

        });
    }
}

