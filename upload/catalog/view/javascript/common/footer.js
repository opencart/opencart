import { WebComponent } from '../index.js';

class XFooter extends WebComponent {
    async connected() {
        console.log('fgfgfg');

        this.innerHtml = 'dfsdfdsfdsfsdf';

        /*
        let language = await this.language.fetch('common/footer');

        let data = [];

        // Blog
        let articles = await this.storage.fetch('cms/article');

        if (articles.length > 0) {
            data.blog = true;
        }

        // Information Pages
        data.informations = [];

        let i = 0;

        let informations = await this.storage.fetch('information/information');

        for (let information of informations) {
            data.informations[i++] = information + [href => information.information_id];
        }


        console.log(data);

        this.innerHtml = this.load.template('common/footer',  [...data, ...language]);

         */
    }
}

customElements.define('x-footer', XFooter);