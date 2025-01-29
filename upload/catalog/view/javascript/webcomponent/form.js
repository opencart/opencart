class XForm extends HTMLElement {
    element;

    data = [{
        name: '',
        value: 0,
        countries: []

    }];

    constructor() {
        super();

        this.shadow = this.attachShadow({
            mode: 'open'
        });

        //
        console.log(this.shadow.adoptedStyleSheets);

        // Set the attribute name and value
        this.data.name = $(this).attr('name');
        this.data.value = $(this).attr('value');

        //let countries = JSON.parse(localStorage.getItem('countries'));
        //JSON.stringify(model)
        //if (countries) {
        //  this.data.countries = countries;
        //}
    }

    connectedCallback() {
        this.shadow.innerHTML = `<form name="${this.data.name}" id="input-country" class="form-select"></form>`;

        // Set the select element as the element we want to work on.
        this.element = $(this.shadow).find('select');

        let countries = () => {
            let success = (countries) => {
                console.log(countries);

                let html = countries.map((country) => {
                    let code = '<option value="' + country.country_id + '"';

                    if (country.country_id == this.data.value) {
                        code += ' selected';
                    }

                    return code + '>' + country.name + '</option>';
                }).join('');

                $(this.element).html(html);
            }

            $.ajax({
                url:  'catalog/view/data/localisation/country.json',
                success: success.bind(this)
            });
        }

        // coutries('catalog/view/data/localisation/country.json');

        let coutries = () => {

            $.ajax({
                url: 'catalog/view/data/localisation/country.' + this.data.value + '.json',
                success: this.success.bind(this),
            });
        }

        $(this.element).on('change', this.onchange.bind(this));

        // Get the countries from the json file.
        // this.load('catalog/view/data/localisation/country.json');
    }

    render() {
        let template = '';

        let code = 'console.log(data);' + "\n";
        code += 'return `' + template + '`;';
        //let func = new Function('data', code);
        //func(this.data);
    }

    onchange(e) {
        //$.ajax({
        //    url: 'catalog/view/data/localisation/country.' + this.data.value + '.json',
        //    success: this.success.bind(this),
        //});
    }
}

customElements.define('x-form', XForm);