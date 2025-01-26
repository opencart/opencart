class Language extends HTMLElement {
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
        return await fetch(url).then((value) => value.json());
    }

    render() {
        this.shadow.innerHTML = `
       
       {% if languages|length > 1 %}
      <div id="language" class="dropdown mt-1">
        <div data-bs-toggle="dropdown" class="dropdown-toggle"><img src="{{ image }}" alt="{{ name }}" title="{{ name }}"> <span class="d-none d-md-inline">{{ text_language }}</span> <i class="fa-solid fa-caret-down"></i></div>
        <ul class="dropdown-menu">
          {% for language in languages %}
            <li><a href="{{ language.href }}" class="dropdown-item"><img src="{{ language.image }}" alt="{{ language.name }}" title="{{ language.name }}"/> {{ language.name }}</a></li>
          {% endfor %}
        </ul>
      </div>
    {% endif %}

        <div>${this.count}</div>
 
        `;

        let button = this.shadow.querySelector('#button-test');

        button.addEventListener('click', this.increment.bind(this));
    }
}

customElements.define('x-language', Language);