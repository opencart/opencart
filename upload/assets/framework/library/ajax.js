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
    option  = {
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        }
    };
    //option.base = '';
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
        this.option = option;
    }

    // Core request method
    async send() {
        let url = this.option.url.startsWith('http') ? this.option.url : `${document.baseURI}${this.option.url}`;

        // Method to use e.g. GET, POST, PUT, PATCH, DELETE
        let method = 'GET';

        if ('method' in this.option) {
            if (['GET', 'POST', 'PUT', 'PATCH', 'DELETE'].includes(this.option.method)) {
                method = this.option.method;
            } else {
                throw new Error('Method Type ' + option.method + ' is not supported.');
            }
        }

        // Headers
        if ('headers' in this.option) {
           // headers: {
           //     'Content-Type': 'application/x-www-form-urlencoded'
           // }

            //if (typeof option.headers == 'object') {
            //    this.headers.set(key, value);
            //}

            for (let [key, value] of this.option.headers) {
                this.headers.set(key, value);
            }
        }

        // Body
        let body = '';

        if ('body' in this.option) {
            body = this.option.body;
        }

        // Response
        if ('beforeSend' in this.option) {
            this.before = this.option.beforeSend;
        }

        console.log(url);
        console.log(this.option.method);
        console.log(this.option.headers);
        console.log(this.option.body);

        try {
            let response = await fetch(url, {
                headers: this.option.headers,
                method: method,
                body: body
            });

            // Try to parse JSON (even on error responses)
            let result;

            let response_type = response.headers.get('content-type');

            if (response_type && response_type.includes('application/json')) {
                result = await response.json();
            } else {
                result = await response.text();
            }

            this.option.onSuccess(result);

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

            if ('onSuccess' in this.option) {
                this.success = this.option.onSuccess;
            }

            if ('onComplete' in this.option) {
                this.complete = this.option.onComplete;
            }

            if ('onError' in this.option) {
                this.error = this.option.onError;
            }


            //response.then(this.success);

           // response.catch(this.error);

            //return result;
        } catch (err) {
            // Network errors or thrown errors above
            throw err;
        }
    }

    get(url, params = {}, options = {}) {
        const query = new URLSearchParams(params).toString();
        const fullURL = query ? `${url}?${query}` : url;

        return this.send(fullURL, {
            method: 'GET',
            ...options
        });
    }

    post(url, body = {}, options = {}) {
        return this.send(url, {
            method: 'POST',
            body,
            ...options
        });
    }

    put(url, body = {}, options = {}) {
        return this.send(url, {
            method: 'PUT',
            body,
            ...options
        });
    }

    patch(url, body = {}, options = {}) {
        return this.send(url, {
            method: 'PATCH',
            body,
            ...options
        });
    }

    delete(url, options = {}) {
        return this.send(url, {
            method: 'DELETE',
            ...options
        });
    }
}