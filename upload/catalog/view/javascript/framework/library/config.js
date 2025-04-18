export default class Config {
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
        let response = await fetch('./data/config/' + path + 'json');

        response.then((data) => {
            this.data = data.json();
        });
    }
}