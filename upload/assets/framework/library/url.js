export class Url {
    hostname = '';
    path = '';
    query = '';

    constructor() {
        this.hostname = document.location.hostname;
        this.path = document.location.pathname;
        this.query = document.location.search ? new URLSearchParams(document.location.search) : [];
    }
}