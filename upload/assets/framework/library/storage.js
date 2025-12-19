export class Storage {
    path = '';
    data = [];

    constructor(path) {
        this.path = path;
    }

    async fetch(filename) {
        let key = filename.replaceAll('/', '.');

        if (key in this.data) {
            return this.data[key];
        }

        let response = await fetch(this.path + filename + '.json');

        if (response.status == 200) {
            this.data[key] = await response.json();

            return this.data[key];
        } else {
            console.log('Could not load file ' + filename + '.json');

            return [];
        }
    }
}