import { WebComponent } from '../component.js';

class InformationSitemap extends WebComponent {
    async connected() {



        let response = this.load.template.render('common/header', this.data);
    }
}

customElements.define('information-sitemap', InformationSitemap);