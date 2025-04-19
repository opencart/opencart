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
        let response = await fetch('./catalog/view/config/' + path + 'json');

        if (response.status == 200) {
            this.data = await response.json();
        } else {
            console.log('Could not load config file ' + path + '.json');
        }
    }
}