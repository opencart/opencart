import { WebComponent } from '../../engine.js';
import { loader } from '../../library.js';

// Config
const config = await loader.config('default');

// A page is a module such as "account/login.js": folders and file name made of
// letters, numbers, "_" and "-". There is deliberately no "." other than the
// extension, so "../" can never reach a script outside the pages folder.
const path_pattern = /^[\w\/-]+\.js$/;

// Attribute names come from the query string, so only plain names are passed
// on - and never on* names, which would be event handlers (onclick, ...).
const attribute_pattern = /^(?!on)[a-z_][\w-]*$/i;

customElements.define('nav-dialog', class extends WebComponent {
    static observedAttributes = ['src'];

	state() { 
        return {
            src: ''
        };
	}

    constructor() {
        super();

        this.data = new Map();
    }

    async render() {
		// Get the source HTML to load
		if (!this.hasAttribute('src') || !this.getAttribute('src')) return;
				
    	let [ path, query ] = this.getAttribute('src').split('?');

        try {
            if (!path_pattern.test(path)) {
                throw new Error('Invalid page path');
            }

            if (!this.data.has(path)) {
                let response = await import(config.get('config_path') + path);

                this.data.set(path, customElements.getName(response.default));
            }

            //if (request !== this.request) return;

			let name = this.data.get(path);

			let element = document.createElement(name);

			for (let [key, value] of (new URLSearchParams(query).entries())) {
                if (!attribute_pattern.test(key)) continue;

				element.setAttribute(key, value);
            }

			return element.outerHTML;
        } catch (error) {
            console.error('nav-include: could not load "' + this.getAttribute('src') + '"', error);

            return '<div class="alert alert-danger">The page could not be loaded.</div>';
        }
    }
});