import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// library
const customer = await loader.library('customer');

// Config
const config = await loader.config('catalog');

// Language
const language = await loader.language('common/header');

class CommonHeader extends WebComponent {
    async connected() {



        let template = await loader.template('common/header', { ...language, ...config });

        console.log(template);

        //wishlist


        this.innerHTML = template;
    }
}

customElements.define('common-header', CommonHeader);