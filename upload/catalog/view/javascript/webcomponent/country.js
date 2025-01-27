class Country extends HTMLElement {
    element;

    data = [{
        name: '',
        value: 0
    }];

    constructor() {
        super();

        this.shadow = this.attachShadow({
            mode: 'open'
        });

        // Add the default stylesheets to the shadow root.
        [...document.styleSheets].map((stylesheet) => {
            let promise = import(stylesheet.href, {
                with: {
                    type: 'css'
                }
            });

            promise.then((stylesheet) => {
                this.shadow.adoptedStyleSheets.push(stylesheet.default);
            });
        });

        // Set the attribute name and value
        this.data.name = $(this).attr('name');
        this.data.value = $(this).attr('value');
    }

    connectedCallback() {
        this.render();

        // Set the select element as the element we want to work on.
        this.element = $(this.shadow).find('select');

        // Get the countries from the json file.
        this.load('catalog/view/data/localisation/country.json');
    }

    async load(file) {
        return await fetch(file).then((response) => {
            return response.json();
        }).then(this.success.bind(this));
    }

    success(countries) {
        let html = countries.map((country) => {
            let code = '<option value="' + country.country_id + '"';

            if (country.country_id == this.data.value) {
                code += ' selected';
            }

            return code + '>' + country.name + '</option>';
        }).join('');

        $(this.element).html(html);
    }

    render() {
        let template = '<select name="${data.name}" id="input-country" class="form-select"></select>';

        let code = 'console.log(data);' + "\n";
        code += 'return `' + template + '`;';

        let func = new Function('data', code);

        this.shadow.innerHTML = func(this.data);

        console.log(this.shadow)//.adoptedStyleSheets
    }
}

customElements.define('x-country', Country);