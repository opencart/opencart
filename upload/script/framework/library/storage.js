export default class Storage {
    path = '';
    data = [];

    constructor(path) {
        this.path = path;
    }

    get (key) {
        return this.data[key];
    }

    set (key, value) {
        this.data[key] = value;
    }

    has (key) {
        return this.data[key] !== undefined;
    }

    remove (key) {
        delete this.data[key];
    }

    all() {
        return this.data;
    }

    async load(path) {
        this.data[path.replaceAll('/', '.')] = await this.fetch(path);
    }

    async fetch(filename) {
        let response = await fetch(this.path + filename + '.json');

        if (response.status == 200) {
            return await response.json();
        } else {
            console.log('Could not load file ' + filename + '.json');

            return [];
        }
    }
}