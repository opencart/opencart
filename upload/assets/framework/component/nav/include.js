import { WebComponent } from '../../engine.js';
import { loader } from '../../library.js';

// Config
const config = await loader.config('default');

// A page is a module such as "account/login.js": folders and file name made of
// letters, numbers, "_" and "-". There is deliberately no "." other than the
// extension, so "../" can never reach a script outside the pages folder.
const path_pattern = /^[\w\/-]+\.js$/;

customElements.define('nav-include', class extends WebComponent {
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

			if (query === undefined) query = '';
			
			for (let [key, value] of (new URLSearchParams(query).entries())) {
                if (/^(?!on)[a-z_][\w-]*$/i.test(key)) element.setAttribute(key, value);
            }

			console.log('Creating element:', name, 'with attributes:', query);

            return element.outerHTML;
        } catch (error) {
            console.error('nav-include: could not load "' + this.getAttribute('src') + '"', error);

            return '<div class="alert alert-danger">The page could not be loaded.</div>';
        }
    }
	
	handleState(keys, state) {
		this.setAttribute('src', state.src);
	}
	
	handleAttribute(key, value) {		
		this.update();
    };
});