/**
 * This is a description of the MyClass constructor function.
 *
 * @class
 * @classdesc This is a description of the MyClass class.
 */
export default class Ajax {
    /**
     * This function can prefix/suffix your string.
     *
     * @example
     *
     * let ajax = new Ajax();
     *
     * ajax.request({
     *   url: 'index.php?route=account/login',
     *   type: 'GET',
     *   headers: {},
     *   body: new FormData({}),
     *   beforeSend: (e) => {
     *      this.$button.state = 'loading';
     *   },
     *   onComplete: (json) => {
     *      this.$button.state = '';
     *   },
     *   onSuccess: (json) => {
     *
     *   },
     *   onError: (e) => {
     *
     *   }
     * });
     */
    async request(url, options = {}) {
        url = url.startsWith('http') ? url : `${document.baseURI}${url}`;

        // Default Config
        let config = {
            method: options.method.toUpperCase() || 'GET',
            ...options
        };

        if (typeof options.beforeSend === 'function') options.beforeSend(config); // you can modify config here

        // Try to parse JSON (even on error responses)
        let result;

        try {
            let response = await fetch(url, config);

            let response_type = response.headers.get('content-type');

            if (response_type && response_type.includes('application/json')) {
                result = await response.json();
            } else {
                result = await response.text();
            }

            if (!response.ok) {
                let error = new Error(result.message || `HTTP ${response.status}`);

                error.status = response.status;
                error.data = result;

                throw error;
            }

            // ----- onSuccess -----
            if (typeof options.onSuccess === 'function') options.onSuccess(result, response);
        } catch (e) {
            // ----- onError -----
            if (typeof options.onError === 'function') options.onError(e);

            throw e;
        } finally {
            // ----- onComplete -----
            if (typeof options.onComplete === 'function') options.onComplete();
        }

        return result;
    }

    /**
     * Get the x value.
     * @return {url} The x value.
     */
    get(url, data = {}, options = {}) {
        const query = new URLSearchParams(data).toString();

        return this.request(query ? `${url}?${query}` : url, {
            method: 'GET',
            ...options
        });
    }

    post(url, body = {}, options = {}) {
        return this.request(url, {
            method: 'POST',
            body,
            ...options
        });
    }

    put(url, body = {}, options = {}) {
        return this.request(url, {
            method: 'PUT',
            body,
            ...options
        });
    }

    patch(url, body = {}, options = {}) {
        return this.request(url, {
            method: 'PATCH',
            body,
            ...options
        });
    }

    delete(url, options = {}) {
        return this.request(url, {
            method: 'DELETE',
            ...options
        });
    }
}