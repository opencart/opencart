export class Api {
    async send(option = {}) {


        if (!option.method) {
            option.method = 'get';
        }

        var enctype = $(button).attr('formenctype') || $(form).attr('enctype') || 'application/x-www-form-urlencoded';


        let response = await fetch(option.url, {
            method: option.method,
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(option.data.toJSON())
        });

        if (response.status !== 200) {
            throw new Error('HTTP error! status: ' + response.status);
        }

        return await response.json();
    }
}