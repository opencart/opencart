import { WebComponent } from '../../library.js';
import { loader } from '../../library.js';

// Config
const config = await loader.config('default');

customElements.define('nav-include', class extends WebComponent {
    static observedAttributes = ['src'];

    constructor() {
        super();

        this.data = new Map();
    }

    async render() {
        // Get the source HTML to load
        if (!this.hasAttribute('src') || !this.getAttribute('src')) return;

		let [ path, query ] = this.getAttribute('src').split('?');
		
		if (!this.data.has(path)) {
			let response = await import(config.get('config_path') + path);

			this.data.set(path, customElements.getName(response.default));
		}

		//if (request !== this.request) return;

		let name = this.data.get(path);

		let element = document.createElement(name);

		if (query === undefined) query = '';
		
		for (let [key, value] of (new URLSearchParams(query).entries())) element.setAttribute(key, value);

		return element.outerHTML;
    }
		
	handleSrc(name, value) {		
		this.update();
    };
});