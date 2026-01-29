import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Language
const language = await loader.language('common/language');

class InformationSitemap extends WebComponent {
    render() {



        return loader.template('information/sitemap', language);
    }
}

customElements.define('information-sitemap', InformationSitemap);