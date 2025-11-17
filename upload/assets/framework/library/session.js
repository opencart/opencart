export default class Session {
    get(key) {
        return Storage.getItem(key);
    }

    set(key, value) {
        Storage.setItem(key, value);
    }

    has(key) {
        return Storage.getItem(key) !== null;
    }

    remove(key) {
        Storage.removeItem(key);
    }
}