import { registry } from './registry.js';

export default class Url {
    static instance = null;
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

registry.url = Url.getInstance();