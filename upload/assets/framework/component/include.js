import { WebComponent } from '../component.js';
import { loader } from '../index.js';

// Config
const config = await loader.config('default');

customElements.define('x-include', class extends WebComponent {
    static observed = ['src'];
    data = new Map();

    get src() {
        return this.getAttribute('src');
    }

    set src(src) {
        this.setAttribute('src', src);
    }

    async render() {
        // Get the source HTML to load
        if (!this.src) return;

        console.log('x-include', this.src);

        let [path, query] = this.src.split('?');

        if (!this.data.has(path)) {
            let response = await import(config.config_path + path);

            this.data.set(path, response.name);
        }

        let name = this.data.get(path);

        let html = '<' + name;

        for (let [ key, value] of (new URLSearchParams(query).entries())) {
            html += ' ' + key + '="' + value + '"';
        }

        return html + '></' + name + '>';
    }
});