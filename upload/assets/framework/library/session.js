export class Session {
    get(key) {
        return JSON.parse(sessionStorage.getItem(key));
    }

    set(key, value) {
        sessionStorage.setItem(key, JSON.stringify(value));
    }

    has(key) {
        return sessionStorage.getItem(key) !== null;
    }

    remove(key) {
        sessionStorage.removeItem(key);
    }

    clear() {
        sessionStorage.clear();
    }
}