class Currency extends HTMLElement {
    constructor() {
        super();

        this.shadow = this.attachShadow({
            mode: 'open'
        });

        //console.log(this.properties.test);
    }

    static get observedAttributes() {
        return ['data-count'];
    }

    get count() {
        return this.getAttribute('data-count');
    }

    set count(value) {
        this.setAttribute('data-count', value);
    }

    increment() {
        this.count++;
    }

    connectedCallback() {
        console.log('connectedCallback');

        this.render();
    }

    adoptedCallback() {
        console.log("adoptedCallback");
    }

    attributeChangedCallback(name, value_old, value_new) {
        console.log("attributeChangedCallback.");

        this.render();
    }

    disconnectedCallback() {
        console.log("disconnectedCallback");
    }

    async fetch(url) {
        return await fetch(url).then((response) => response.json());
    }

    render() {
        this.shadow.innerHTML = `<select name="language">
        <h3>test</h3>
        <slot name="name"></slot>
        <slot name="description"></slot>
        <div>${this.count}</div>
        <button id="button-test">Click me</button>
        `;

        let button = this.shadow.querySelector('#button-test');

        button.addEventListener('click', this.increment.bind(this));
    }
}

customElements.define('x-language', Language);