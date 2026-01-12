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
        let data = {...language};

        if (config.has('config_logo')) {
            data.logo = config.get('config_url') + 'image/' + config.get('config_logo');
        } else {
            data.logo = '';
        }

        data.name = config.get('config_name');
        data.telephone = config.get('config_telephone');
    }
}

customElements.define('common-header', CommonHeader);