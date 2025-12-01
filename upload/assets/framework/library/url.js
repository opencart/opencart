export class Url {


    constructor() {
        let location = document.location.search;


        let test = new URLSearchParams(location.search);

        console.log(test);


        this.host;
        this.hostname;
        this.path;
        this.query = new URLSearchParams(document.location.search);
        this.port;
    }

    query() {
        return this.query;
    }
}