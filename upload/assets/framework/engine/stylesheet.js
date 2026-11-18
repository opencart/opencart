/**
 * Stylesheet
 * ----------
 * Loads CSS into shadow roots as shared stylesheets. Each file is fetched once and the
 * same sheet is used by every component.
 *
 *   stylesheet.addPath('catalog/', 'catalog/view/stylesheet/');
 *   stylesheet.addPath('fontawesome/css/', 'assets/fontawesome/css/');
 *
 *   await stylesheet.adopt(this.shadowRoot, ['catalog/stylesheet.css', 'fontawesome/css/all.css']);
 *   await stylesheet.adoptText(this.shadowRoot, [':host { display: block; }']);
 */
export class Stylesheet {
    static #directory = '';
    static #path = new Map();
	static #cache = new Map();
	#root;

	constructor(root) {
		this.#root = root;
	}

	static setDirectory(directory) {
        Stylesheet.#directory = directory;
	}
	
    static addPath(namespace, path) {
        Stylesheet.#path.set(namespace, path);
    }

	addStylesheet(path) {
		let stylesheets = [];

		if (typeof path == 'string') {
			stylesheets.push(path);
		} else if (Array.isArray(path)) {
			stylesheets = path;
		} else { 
			return;
		}
		
		for (let stylesheet of stylesheets) {
			this.fetch(stylesheet).then(stylesheet => this.#root.adoptedStyleSheets.push(stylesheet));
		}
    }

	addStyle(text) {
		if (!text) return;
		if (Stylesheet.#cache.has(text)) return Stylesheet.#cache.get(text);

        let stylesheet = new CSSStyleSheet();
        
		stylesheet.replaceSync(text);
        
		this.#root.adoptedStyleSheets.push(stylesheet);

		Stylesheet.#cache.set(text, stylesheet);
	}
	
	async fetch(path) {
        if (Stylesheet.#cache.has(path)) return Stylesheet.#cache.get(path);

        let file = Stylesheet.#directory + path;
        let namespace = '';
        let parts = path.replace(/\/+$/, '').split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (Stylesheet.#path.has(namespace + '/')) {
				file = Stylesheet.#path.get(namespace + '/') + path.substr(namespace.length + 1);
            }
        }
		
        let response = await fetch(file);

        if (response.status == 200) {
            let css = await response.text();
			
			let stylesheet = new CSSStyleSheet();

			stylesheet.replaceSync(css);
			
            Stylesheet.#cache.set(path, stylesheet);

            return Stylesheet.#cache.get(path);
        } else {
            throw new Error(`[Stylesheet] Could not load stylesheet file ${path}!`);
        }

        return undefined;
	}
}