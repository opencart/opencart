export class Request {
    option = {
        method: 'get',
        headers: {
            'Content-Type': 'application/json'
        },
        body: option.data
    };

    async post(config) {
        if (!config.method) {
            config.method = 'get';
        }

        if (!config.headers) {
            config.headers = {
                'Content-Type': 'application/json'
            };
        }

        //if () {
        //  option.data
        //}

        let response = await fetch(config.url, {
            method: config.method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: config.data
        });

        if (response.status !== 200) {
            throw new Error('HTTP error! status: ' + response.status);
        }

        return await response.json();
    }

    async get() {

    }
}