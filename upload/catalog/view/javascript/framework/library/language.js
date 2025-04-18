export default class Language {
    data = [];

    get(key) {
        return this.data[key];
    }

    set(key, value) {
        this.data[key] = value;
    }

    all() {
        return this.data;
    }

    async load(path) {
        let response = await fetch('./data/language/' + path + '.json');

        response.then((data) => {
            this.data = data.json();
        });
    }
}