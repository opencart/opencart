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
export default class Stylesheet {
    static #instance = null;
    #directory = '';
    #path = new Map();
	#cache = new Map();
	
	setDirectory(directory) {
        this.#directory = directory;
	}
	
    addPath(namespace, path) {
        this.#path.set(namespace, path);
    }

 	async fetch(path) {
        if (this.#cache.has(path)) return this.#cache.get(path);

        let file = this.#directory + path;
        let namespace = '';
        let parts = path.replace(/\/+$/, '').split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (this.#path.has(namespace + '/')) {
				file = this.#path.get(namespace + '/') + path.substr(namespace.length + 1);
            }
        }
		
        let response = await fetch(file);

        if (response.status == 200) {
            let css = await response.text();
			
			let stylesheet = new CSSStyleSheet();

			stylesheet.replaceSync(css);
			
            this.#cache.set(path, stylesheet);

            return this.#cache.get(path);
        } else {
            throw new Error(`[Stylesheet] Could not load stylesheet file ${path}!`);
        }

        return undefined;
    }

	static getInstance() {
        if (!Stylesheet.#instance) {
            Stylesheet.#instance = new Stylesheet();
        }

        return Stylesheet.#instance;
    }
}

const stylesheet = Stylesheet.getInstance();

export { stylesheet };