/*
// Example
let ajax = new Ajax({
    url: 'index.php?route=account/login',
    type: 'GET',
    headers: {},
    accept: 'application/json',
    body: new FormData({}),
    responseType: 'json',
    beforeSend: (e) => {
        this.$button.state = 'loading';
    },
    onComplete: (json) => {
        this.$button.state = '';
    },
    onSuccess: (json) => {

    },
    onError: (e) => {

    }
});

ajax.send();
*/
export class Ajax {
    base = '';
    url = '';
    method = 'GET'; // GET, POST, PUT, PATCH
    headers= {
        'Content-Type': 'application/x-www-form-urlencoded'
    };
    body = {};
    responseType = 'json'; // Accepted response Type json, html, text, etc...
    before = null;
    success = null;
    complete = null;
    error = null;

    constructor(option) {
        //this.headers.append('Content-Type', 'application/x-www-form-urlencoded');
        //this.headers.append('Accept', 'application/json');

        this.url = option.url;

        // Method to use e.g. GET, POST, PUT, PATCH, DELETE
        if ('method' in option) {
            if (['GET', 'POST', 'PUT', 'PATCH', 'DELETE'].includes(option.method)) {
                this.method = option.method;
            } else {
                throw new Error('Method Type ' + option.method + ' is not supported.');
            }
        }

        // Headers
        if ('headers' in option) {
            //if (typeof option.headers == 'object') {
            //    this.headers.set(key, value);
            //}

            for (let [key, value] of option.headers) {
                this.headers.set(key, value);
            }
        }

        // Body
        if ('body' in option) {
            this.body = option.body;
        }

        // Response
        if ('beforeSend' in option) {
            this.before = option.beforeSend;
        }

        if ('onSuccess' in option) {
            this.success = option.onSuccess;
        }

        if ('onComplete' in option) {
            this.complete = option.onComplete;
        }

        if ('onError' in option) {
            this.error = option.onError;
        }
    }

    // Core request method
    async send() {
        let url = this.url.startsWith('http') ? this.url : `${document.baseURI}${this.url}`;

        console.log(url);
        console.log(this.method);
        console.log(this.headers);
        console.log(this.body);

        try {
            let response = await fetch(url, {
                //headers: this.headers,
                method: this.method,
                body: this.body
            });

            // Try to parse JSON (even on error responses)
            //let result;


            // arrayBuffer()
            //const contentType = response.headers.get('content-type');

           // if (contentType && contentType.includes('application/json')) {
            //    result = response.json();
            //} else {
            ///    result = response.text();
            //}

            console.log(await response.json());

            //console.log(...response.headers.entries());

            /*
            if (response.ok) {
                //this.success(result);


                const error = new Error(result.message || `HTTP ${response.status}`);

                error.status = response.status;
                error.data = result;

                throw error;
            }
*/
            //response.then(this.success);

           // response.catch(this.error);

            //return result;
        } catch (err) {
            // Network errors or thrown errors above
            throw err;
        }
    }
}