//import
class XCountry extends HTMLElement {
    element;

    data = [{
        name: '',
        value: 0,
        countries: []
    }];

    event = [{
        onconnect: () => {
            let promise = fetch('catalog/view/data/localisation/country.json');

            promise.then((response) => {
                this.data.countries = response.json();
            }).then(this.onchange);
        },
        onchange: (e) => {
            let promise = fetch('catalog/view/data/localisation/country.' + this.data.value + '.json');

            promise.then((response) => response.json()).then(this.onchange);
        }
    }];

    constructor() {
        super();

        this.shadow = this.attachShadow({
            mode: 'open'
        });

        this.shadow.innerHTML = `<select name="${this.data.name}" id="input-country" class="form-select"></select>`;

        // Set the select element as the element we want to work on.
        this.element = $(this.shadow).find('select');

        // Add the default stylesheets to the shadow root.
        //[...document.styleSheets].map((stylesheet) => {
            //var css = new CSSStyleSheet()
            //css.replaceSync('@import url(' + stylesheet.href + ')')
            //this.shadow.adoptedStyleSheets = [css];
        //});

        //console.log(this.shadow.adoptedStyleSheets);
        //let countries = JSON.parse(localStorage.getItem('countries'));
        //JSON.stringify(model)
        //if (countries) {
        //  this.data.countries = countries;
        //}

        // Set the attribute name and value
        this.data.name = $(this).attr('name');
        this.data.value = $(this).attr('value');
    }

    connectedCallback() {
        $(this.element).on('change', this.event.onchange);
    }

    render() {
        let template = '';

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
                url: 'catalog/view/data/localisation/country.json',
                success: success.bind(this)
            });
        }

        // countries();
        /*
        let countries = () => {
            $.ajax({
                url: 'catalog/view/data/localisation/country.' + this.data.value + '.json',
                success: this.success.bind(this),
            });
        }
        let code = 'console.log(data);' + "\n";
        code += 'return `' + template + '`;';
        */
        //let func = new Function('data', code);
        //func(this.data);
        // Get the countries from the json file.
        // this.load('catalog/view/data/localisation/country.json');
    }
}

customElements.define('x-country', XCountry);