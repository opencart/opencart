class Binder {
    data = new Map();

    get(key) {
        return this.data.get(key);
    }

    set(key, value) {
        this.data.set(key, value);
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Binder();
        }

        return this.instance;
    }
}

class Action {
    data = new Map();

    register(key, value) {
        this.data.set(key, value);
    }

    create(key, element) {
        console.log(key);

        let value = this.data.get(key);

        return new value(element);
    }

    static getInstance() {
        if (!this.instance) {
            this.instance = new Action();
        }

        return this.instance;
    }
}

export const binder = new Binder();
export const action = new Action();

action.register('link', class {
    element;
    target = '';

    constructor(element) {
        element.addEventListener('click', this.onClick.bind(this));

        this.target = element.getAttribute('data-target');
    }

    onClick(e) {
        e.preventDefault();

        console.log(this.target);

        let target = document.getElementById(this.target);

        target.src = e.currentTarget.getAttribute('href');
    }
});

class Button {
    element;
    html = '';
    width;

    constructor(element) {
        console.log('button');

        this.element = element;
        this.html = element.innerHTML;
        this.width = element.offsetWidth;

        Object.assign(element, {
            button: this.button.bind(this),
        });
    }

    button(state) {
        if (state === 'loading') {
            this.element.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-light"></i>';
            this.element.style.width = this.width;

            this.element.setAttribute('disabled', '');
        }

        if (state === 'reset') {
            this.element.innerHTML = this.html;
            this.element.style.width = '';

            this.element.removeAttribute('disabled');
        }
    }
}

action.register('button', Button);

action.register('form', class {
    element;

    constructor(element) {
        this.element = element;
        this.element.addEventListener('submit', this.onSubmit);
    }

    onSubmit(e) {
        e.preventDefault();

    }
});

