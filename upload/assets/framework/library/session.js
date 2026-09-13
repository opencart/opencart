export default class Session {
    async get(key) {
        return await JSON.parse(sessionStorage.getItem(key));
    }

    set(key, value) {
        sessionStorage.setItem(key, JSON.stringify(value));
    }

    has(key) {
        return sessionStorage.getItem(key) !== null;
    }

    delete(key) {
        sessionStorage.removeItem(key);
    }
}