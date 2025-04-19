export default class Storage {
    data = [];

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

    async fetch(path) {
        let response = await fetch('./catalog/view/data/' + path + '.json');

        if (response.status == 200) {
            return await response.json();
        } else {
            console.log('Could not load file ' + path + '.json');

            return [];
        }
    }
}