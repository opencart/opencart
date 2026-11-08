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
class Stylesheet {
    static #instance = null;
    #directory = '';
    #path = new Map();
    #cache = new Map();

    addPath(namespace, path = '') {
        if (!path) {
            this.#directory = namespace;
        } else {
            this.#path.set(namespace, path);
        }
    }

 	async fetch(path) {
        if (this.#cache.has(path)) return this.#cache.get(path);

        let file = this.#directory + path + '.css';
        let namespace = '';
        let parts = path.replace(/\/+$/, '').split('/');

        for (let part of parts) {
            if (!namespace) {
                namespace += part;
            } else {
                namespace += '/' + part;
            }

            if (this.#path.has(namespace + '/')) {
                file = this.#path.get(namespace + '/') + path.substr(namespace.length) + '.css';
            }
        }

        let response = await fetch(file);

        if (response.status == 200) {
            let stylesheet = await response.text();
			
			const componentStyles = new CSSStyleSheet();

			componentStyles.replaceSync(stylesheet);
			
            this.#cache.set(path, componentStyles);

            return this.#cache.get(path);
        } else {
            throw new Error(`[Stylesheet] Could not load stylesheet file ${path}!`);
        }

        return undefined;
    }

    #sheet(css) {
        const sheet = new CSSStyleSheet();

        sheet.replaceSync(css);

        return sheet;
    }

    #add(root, sheets) {
        const existing = root.adoptedStyleSheets;

        root.adoptedStyleSheets = [...existing, ...sheets.filter(sheet => !existing.includes(sheet))];
	}
	
	static getInstance() {
        if (!Stylesheet.#instance) {
            Stylesheet.#instance = new Stylesheet();
        }

        return Stylesheet.#instance;
    }
}

export const stylesheet = new Stylesheet();

//stylesheet.fetch('catalog/stylesheet.css');