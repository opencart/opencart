class Zone extends HTMLElement {
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

    success(json) {
        let html = json.map((country) => {
            let html = '<option value="' + country.country_id + '"';

            if (country.country_id == this.data.value) {
                html += ' selected';
            }

            return html + '>' + country.name + '</option>';
        }).join('');

        $(this.element).html(html);
    }

    render() {
        this.shadow.innerHTML = '<select name="' + this.data.name + '" id="input-country" class="form-select"></select>';
    }
}

customElements.define('x-zone', Zone);
