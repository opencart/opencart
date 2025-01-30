class XForm extends HTMLElement {
    element;

    data = [{
        name: '',
        action: 0,
    }];

    constructor() {
        super();

        this.shadow = this.attachShadow({
            mode: 'open'
        });

        //form-address
        //action="{{ save }}" method="post"

        //console.log(this.shadow.adoptedStyleSheets);

        // Set the attribute name and value
        this.data.action = $(this).attr('action');
        this.data.method = $(this).attr('method');
    }

    connectedCallback() {
        this.shadow.innerHTML = `<form action="${this.data.action}">${this.innerHTML}</form>`;

        // Set the select element as the element we want to work on.
        this.element = $(this.shadow).find('form');

        $(this.element).on('submit', this.onsubmit.bind(this));

        // Get the countries from the json file.
        //this.load('catalog/view/data/localisation/country.json');
    }

    render() {
        let template = '';

        let code = 'console.log(data);' + "\n";
        code += 'return `' + template + '`;';
        //let func = new Function('data', code);
        //func(this.data);
    }

    onsubmit(e) {
        //$.ajax({
        //    url: 'catalog/view/data/localisation/country.' + this.data.value + '.json',
        //    success: this.success.bind(this),
        //});
    }
}

customElements.define('x-form', XForm);