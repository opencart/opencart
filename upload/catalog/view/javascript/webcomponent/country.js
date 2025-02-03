const element = {};

const template = `<select name="${name}" id="input-country" class="form-select"></select>`;

const data = [{
    name: '',
    value: 0,
    country: [],
    countries: []
}];

const model = {
    getCountries: async () => {
        const countries = [];

        let promise = fetch('catalog/view/data/localisation/country.json');

        promise.then((response) => response.json()).then((country) => {
            countries[country.county_id] = country;

            console.log(countries);


        });

        console.log(countries);

        return countries;
    },
    getCountry: () => {
        let promise = fetch('catalog/view/data/localisation/country.' + data.value + '.json');

        promise.then((response) => response.json()).then(this.onchange);
    }
};

const loader = {
    controller: (path, data = []) => {

    },
    model: async (path) => {
        await fetch(path).then((response) => {
            return response.json();
        }).then((test) => data.countries = test);
    },
    view: (path, data = []) => {

    },
    language: (path) => {

    }
};

//loader.model('catalog/view/data/localisation/country.json');

let countries = await model.getCountries();

console.log(countries);

const render = ((string, data) => {
    let code = 'console.log(data);' + "\n";

    code += 'with (data) {' + "\n";
    code += '  return `' + string + '`;' + "\n";
    code += '}';

    let func = new Function('data', code);

    return func(data);
});

const event = {
    onconnect: () => {
        let promise = fetch('catalog/view/data/localisation/country.json');

        promise.then((response) => {
            data.countries = response.json();
        }).then(this.compile);
    },
    compile:() => {

    },
    onchange: (e) => {
        this.element


        let promise = fetch('catalog/view/data/localisation/country.' + this.data.value + '.json');

        promise.then((response) => response.json()).then(this.onchange);
    },
    updateZone: (e) => {

    }
};

class XCountry extends HTMLElement {
    constructor() {
        super();

        const shadow = this.attachShadow({
            mode: 'open'
        });

        shadow.innerHTML = render(template, data);

        //element = $(shadow).find('select');

        //let countries = model.getCountries();
        //console.log(countries);
        // Set the select element as the element we want to work on.


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
        data.name = $(this).attr('name');
        data.value = $(this).attr('value');
    }

    connectedCallback() {
        $(this.element).on('change', event.onchange);
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