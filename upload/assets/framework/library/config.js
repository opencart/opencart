export class Config {
    path = '';
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

        console.log(this.path + filename);

        let response = await fetch(this.path + filename + '.json');

        if (response.status == 200) {
            let json = await response.json();

            this.data = [...this.data, ...json];
        } else {
            console.log('Could not load config file ' + filename + '.json');
        }
    }
}