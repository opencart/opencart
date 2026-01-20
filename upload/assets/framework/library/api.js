export default class Api {
    async fetch(option) {

        if (option.headers == undefined) {
            option.headers == {
                'content-type': 'multipart/form-data'
            };
        }

        if (option.method == undefined) {
            option.method == '';
        }

        let response = fetch(option.url, {
            method: option.method,
            // whatever data you want to post with a key-value pair
            headers: option.headers,
            body: option.data,

        });

        response.thn();
    }

    async get(config) {

    }

    async post(url, data = {}) {
        let response = await fetch(option.url, {
            method: 'POST',
            body: new FormData(data),
        });

        return await response.json();
    }

    async get(url) {
        let response = await fetch(option.url, {
            method: 'GET',
            body: new FormData(data),
        });

        return await response.json();
    }
}