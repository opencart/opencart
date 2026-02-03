/**
 * Request - A jQuery.ajax-like HTTP client using fetch
 *
 * @example
 * const req = new Request();
 *
 * // Simple GET
 * req.get('/api/users')
 *    .then(data => console.log(data))
 *    .catch(err => console.error(err));
 *
 * // POST with data & custom headers
 * req.post('/api/login', { username: 'daniel', password: '123' }, {
 *   headers: { 'Authorization': 'Bearer xxx' },
 *   timeout: 10000
 * }).then(res => console.log(res));
 */
class Request {
    constructor(defaults = {}) {
        this.defaults = {
            baseURL: '',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
            },
            timeout: 30000,           // 30 seconds default timeout
            credentials: 'same-origin',
            ...defaults
        };
    }

    /**
     * Core request method (similar to $.ajax)
     * @param {Object} options - Request configuration
     * @returns {Promise<any>}
     */
    async request(options) {
        const config = {...this.defaults, ...options};

        // Merge headers (don't overwrite defaults completely)
        config.headers = {
            ...this.defaults.headers,
            ...options.headers
        };

        // Handle baseURL
        let url = config.url;
        if (config.baseURL && !url.startsWith('http')) {
            url = config.baseURL.replace(/\/$/, '') + '/' + url.replace(/^\//, '');
        }

        // Prepare fetch init object
        const fetchInit = {
            method: (config.method || 'GET').toUpperCase(),
            headers: config.headers,
            credentials: config.credentials,
            signal: null,
        };

        // Handle body
        if (config.data) {
            if (fetchInit.method !== 'GET' && fetchInit.method !== 'HEAD') {
                if (config.headers['Content-Type']?.includes('application/json')) {
                    fetchInit.body = JSON.stringify(config.data);
                } else if (config.headers['Content-Type']?.includes('multipart/form-data')) {
                    // Let browser set boundary → remove content-type header
                    delete fetchInit.headers['Content-Type'];
                    fetchInit.body = this._toFormData(config.data);
                } else {
                    fetchInit.body = config.data;
                }
            }
        }

        // Timeout handling
        const controller = new AbortController();
        fetchInit.signal = controller.signal;

        const timeoutId = setTimeout(() => {
            controller.abort();
        }, config.timeout);

        try {
            // Optional beforeSend callback (jQuery style)
            if (typeof config.beforeSend === 'function') {
                config.beforeSend(config);
            }

            const response = await fetch(url, fetchInit);

            clearTimeout(timeoutId);

            // Optional complete callback
            if (typeof config.complete === 'function') {
                config.complete(response);
            }

            if (!response.ok) {
                const error = new Error(`Request failed with status ${response.status}`);
                error.response = response;
                throw error;
            }

            // Auto-parse JSON if expected
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return await response.json();
            }

            return response;

        } catch (err) {
            clearTimeout(timeoutId);

            if (err.name === 'AbortError') {
                throw new Error('Request timeout');
            }

            if (typeof config.error === 'function') {
                config.error(err);
            }

            throw err;
        }
    }

    // ────────────────────────────────────────────────
    // Convenience methods (jQuery-like)
    // ────────────────────────────────────────────────

    get(url, data = null, config = {}) {
        return this.request({
            method: 'GET',
            url,
            data,
            ...config
        });
    }

    post(url, data = {}, config = {}) {
        return this.request({
            method: 'POST',
            url,
            data,
            ...config
        });
    }

    put(url, data = {}, config = {}) {
        return this.request({
            method: 'PUT',
            url,
            data,
            ...config
        });
    }

    patch(url, data = {}, config = {}) {
        return this.request({
            method: 'PATCH',
            url,
            data,
            ...config
        });
    }

    delete(url, data = null, config = {}) {
        return this.request({
            method: 'DELETE',
            url,
            data,
            ...config
        });
    }

    // Helper: object → FormData
    _toFormData(obj) {
        const formData = new FormData();
        for (const key in obj) {
            if (Object.prototype.hasOwnProperty.call(obj, key)) {
                const value = obj[key];
                if (Array.isArray(value)) {
                    value.forEach(val => formData.append(`${key}[]`, val));
                } else {
                    formData.append(key, value);
                }
            }
        }
        return formData;
    }
}

export default Request;