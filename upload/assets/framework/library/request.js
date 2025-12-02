export class Request {
    option = {
        method: 'get',
        headers: {
            'Content-Type': 'application/json'
        },
        body: option.data
    };

    async fetch(option) {
        if (!option.method) {
            option.method = 'get';
        }

        if (!option.headers) {
            option.headers = {
                'Content-Type': 'application/json'
            };
        }

        //if () {
        //     option.data
        //}


        let response = await fetch(option.url, {
            method: option.method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: option.data
        });

        if (response.status !== 200) {
            throw new Error('HTTP error! status: ' + response.status);
        }

        return await response.json();
    }
}