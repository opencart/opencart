class Language {
    static #instance = null;
    path = '';
    data = [];

    constructor(path) {
        this.path = path;
    }

    async fetch(filename) {
        let key = filename.replaceAll('/', '.');

        if (this.data[key] !== undefined) {
            return this.data[key];
        }

        let response = await fetch(this.path + filename + '.json');

        if (response.status == 200) {
            let json = await response.json();

            this.data[key] = json;

            return json;
        } else {
            console.log('Could not load file ' + filename + '.json');

            return [];
        }
    }

    static getInstance() {
        if (!Language.#instance) {
            // Base
            const base = new URL(document.querySelector('base').href);

            // lang
            let code = document.querySelector('html').lang.toLowerCase();

            Language.#instance = new Language('./catalog/view/language/' + base.host + '/' + code + '/');
        }

        return Language.#instance;
    }
}

const language = Language.getInstance();

export { language };