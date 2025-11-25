export class Local {
    get(key) {
        return localStorage.getItem(key);
    }

    set(key, value) {
        localStorage.setItem(key, value);
    }

    has(key) {
        return localStorage.getItem(key) !== null;
    }

    remove(key) {
        localStorage.removeItem(key);
    }

    clear() {
        localStorage.clear();
    }
}