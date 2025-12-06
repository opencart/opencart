export class Config {
    path = '';
    loaded = [];
    data = [];

    constructor(path) {
        this.path = path;
    }

    get(key) {
        return key in this.data ? this.data[key] : null;
    }

    set(key, value) {
        this.data[key] = value;
    }

    has(key) {
        return key in this.data;
    }

    remove(key) {
        if (key in this.data) delete this.data[key];
    }

    all() {
        return this.data;
    }

    async load(filename) {
        let key = filename.replaceAll('/', '.');

        if (key in this.data) {
            return this.data[key];
        }

        let response = await fetch(this.path + filename + '.json');

        if (response.status == 200) {
            let json = await response.json();


            this.data[key] = json;

            return json;
        } else {
            console.log('Could not load config file ' + filename + '.json');
        }
    }

    async fetch(filename) {
        let key = filename.replaceAll('/', '.');

        if (key in this.data) {
            return this.data[key];
        }

        let response = await fetch(this.path + filename + '.json');

        if (response.status == 200) {
            let json = await response.json();

            this.data[key] = json;

            return json;
        } else {
            console.log('Could not load config file ' + filename + '.json');
        }
    }
}