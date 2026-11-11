/**
 * This is a description of the MyClass constructor function.
 *
 * @class
 * @classdesc This is a description of the MyClass class.
 */
export default class Ajax {
    static #instance = null;

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
	async request(option = {}) {
		if (!option.url) {
			throw new Error('URL is required');
		};			
		
		let url = option.url.startsWith('http') ? option.url : `${document.baseURI}${option.url}`;

        // Default Config
		let config = {
            method: (option.method || 'GET').toUpperCase(),
            ...option
        };

        if (typeof option.handleSend === 'function') option.handleSend(config); // you can modify config here

        // Try to parse JSON (even on error responses)
        let result;

        try {
            let response = await fetch(url, config);

            let response_type = response.headers.get('content-type');

            if (response_type && response_type.includes('application/json')) {
                let data = await response.json();

                if (!Array.isArray(data)) {
                    result = new Map(Object.entries(data));
                } else {
                    result = data;
                }
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
            if (typeof option.handleSuccess === 'function') option.handleSuccess(result, response);
        } catch (e) {
            // ----- onError -----
            if (typeof option.handleError === 'function') option.handleError(e);

            throw e;
        } finally {
            // ----- onComplete -----
            if (typeof option.handleComplete === 'function') option.handleComplete();
        }

        return result;
    }

    /**
     * Sends a GET request, appending `data` to the URL as a query string.
     * @param {object} option
     * @param {string} option.url
     * @param {object} [option.data] - key/value pairs serialized into the query string
     * @param {object} [option.options] - extra request options, same shape as `request()`
     * @return {Promise<*>} resolves with the parsed response body
     */
    get(url, option = {}) {
        const query = new URLSearchParams(option.data).toString();

        return this.request({
            url: query ? `${url}?${query}` : url,
            method: 'GET',
            ...option
        });
    }

    post(option = {}) {
        return this.request({
            url: option.url,
            method: 'POST',
            body: option.data,
            ...option
        });
    }

    put(option = {}) {
		return this.request({
            url: option.url,
            method: 'PUT',
            body: option.data,	
            ...option
        });
    }

    patch(option = {}) {
        return this.request({
            url: option.url,
            method: 'PATCH',
            body: option.data,
            ...option
        });
    }

    delete(option = {}) {
        return this.request({
            url: option.url,
            method: 'DELETE',
            ...option
        });
    }

    static getInstance() {
        if (!Ajax.#instance) {
            Ajax.#instance = new Ajax();
        }

        return Ajax.#instance;
    }
}

const ajax = Ajax.getInstance();

export { ajax };