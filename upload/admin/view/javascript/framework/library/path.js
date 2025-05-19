
export default class Registry {
    data = [];

    getPath (key) {
        return this.data[key];
    }

    setPath (key, value) {
        this.data[key] = value;
    }

    has (key) {
        return this.data[key] !== undefined;
    }

    remove (key) {
        delete this.data[key];
    }
}