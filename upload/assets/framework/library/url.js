export class Url {
    hostname = '';
    path = '';
    query = '';
    port = 80;

    constructor() {
        let location = document.location;

        this.hostname = location.hostname;
        this.path = location.pathname;
        this.query = new URLSearchParams(location.search);
        this.port = location.port;

        console.log(this);
    }

    path(key, value) {
        return this.path;
    }

    query(key, value) {
        return this.query;
    }

    port(key, value) {
        return this.port;
    }
}