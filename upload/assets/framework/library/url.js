export class Url {
    hostname = '';
    path = '';
    query = '';

    constructor() {
        this.hostname = document.location.hostname;
        this.path = document.location.pathname;
        this.query = document.location.search ? new URLSearchParams(document.location.search) : [];
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Url();
        }

        return this.instance;
    }
}

const url = Url.getInstance();

export { url };