export class Config {
    data = [];

    get(key) {
        return this.data[key];
    }

    set(key, value) {
        this.data[key] = value;
    }

    has(key) {
        return this.data[key] !== undefined;
    }

    remove(key) {
        delete this.data[key];
    }

    async load(filename) {
        let response = await fetch(this.path + filename + '.json');

        if (response.status == 200) {
            let json = await response.json();

            this.data = [...this.data, ...json];
        } else {
            console.log('Could not load config file ' + filename + '.json');
        }
    }
}