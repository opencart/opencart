export default class Registry {
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
}