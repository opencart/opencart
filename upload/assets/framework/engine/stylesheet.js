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
    #paths = new Map();
    #files = new Map(); // url -> Promise<CSSStyleSheet>
    #texts = new Map(); // css -> CSSStyleSheet

    /** Names starting with `namespace` are loaded from `path` instead. */
    addPath(namespace, path) {
        this.#paths.set(namespace, path);
    }

    /** The absolute URL of a name. The longest matching namespace wins. */
    resolve(name) {
        let file = name;
        let length = 0;

        for (const [namespace, path] of this.#paths) {
            if (name.startsWith(namespace) && namespace.length > length) {
                file = path + name.slice(namespace.length);
                length = namespace.length;
            }
        }

        return new URL(file, document.baseURI).href;
    }

    /** Loads the files and adds them to the root. A file that fails is logged and skipped. */
    async adopt(root, names = []) {
        const sheets = await Promise.all(names.map(name => this.#load(this.resolve(name)).catch(error => {
            console.error(error);

            return null;
        })));

        this.#add(root, sheets.filter(Boolean));
    }

    /** Adds CSS text to the root. */
    async adoptText(root, texts = []) {
        this.#add(root, texts.map(css => {
            if (!this.#texts.has(css)) this.#texts.set(css, this.#sheet(css));

            return this.#texts.get(css);
        }));
    }

    #load(url) {
        if (!this.#files.has(url)) {
            const promise = fetch(url).then(response => {
                if (!response.ok) throw new Error(`Stylesheet: could not load "${url}" (${response.status})`);

                return response.text();
            }).then(css => {
                // An adopted sheet has no address of its own, so relative url(...) is made absolute.
                css = css.replace(/url\(\s*(['"]?)(?!['"]|[a-z][a-z0-9+.-]*:|#)(.*?)\1\s*\)/gi, (match, quote, path) => `url("${new URL(path, url).href}")`);

                // Browsers only load web fonts declared on the document, so those go there too.
                const fonts = css.match(/@font-face\s*\{[^}]*\}/gi);

                if (fonts) this.#add(document, [this.#sheet(fonts.join('\n'))]);

                return this.#sheet(css);
            });

            // Not kept when it failed, so the next call tries again.
            promise.catch(() => this.#files.delete(url));

            this.#files.set(url, promise);
        }

        return this.#files.get(url);
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
}

export const stylesheet = new Stylesheet();
