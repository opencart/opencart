import { WebComponent } from '../index.js';
import { loader } from '../index.js';

// Load local components
import './header.js';
import './footer.js';
import '../component/autocomplete.js';
import '../component/checkbox.js';
import '../component/country.js';
import '../component/form.js';
import '../component/include.js';
import '../component/markdown.js';
import '../component/pagination.js';
import '../component/switch.js';
import '../component/upload.js';
import '../component/zone.js';

customElements.define('common-layout', class extends WebComponent {
    constructor() {
        super();

        this.data = new Map();
    }

    render() {
        return loader.template('common/layout');
    }

    async handleContent(e) {
        e.preventDefault();

        let target = e.currentTarget;

        // Get the source HTML to load
        if (!target.hasAttribute('href')) return;

        let [ path, query] = target.getAttribute('href').split('?');

        if (!this.data.has(path)) {
            let component = await import(config.config_path + path);

            this.data.set(path, customElements.getName(component.default));
        }

        let name = this.data.get(path);

        let html = '<' + name;

        for (let [ key, value] of (new URLSearchParams(query).entries())) {
            html += ' ' + key + '="' + value + '"';
        }

        return html + '></' + name + '>';
    }
});