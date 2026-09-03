import { loader } from '../index.js';

let type = {
    form: (element) => {
        return new Form(element);
    },
    button: (element) => {
        return new Button(element);
    },
    link: (element) => {
        return new Link(element);
    }
}

class Form {
    element;

    construct(element) {
        this.element = element;
        this.element.addEventListener('submit', this.onSubmit);
    }

    onSubmit(e) {
        e.preventDefault();

    }
}

class Button {
    element;
    html = '';
    width;

    construct(element) {
        this.element = element;
        this.html = element.innerHTML;
        this.width = element.offsetWidth;
    }

    button(state) {
        if (state === 'loading') {
            this.element.innerHTML = '<i class="fa-solid fa-circle-notch fa-spin text-light"></i>';
            this.element.style.width = this.width;

            this.element.addAttribute('disabled', '');
        }

        if (state === 'reset') {
            this.element.innerHTML = this.html;
            this.element.style.width = '';

            this.element.removeAttribute('disabled');
        }
    }
}

class Link {
    element;
    target = '';

    construct(element) {
        this.element = element;
        this.target = element.getAttribute('data-target');
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById(this.target);

        target.src = e.currentTarget.getAttribute('href');
    }
}