export class Button {
    element;
    html = '';
    width;

    constructor(element) {
        this.element = element;
        this.html = element.innerHTML;
        this.width = element.offsetWidth;

        Object.assign(element, {
            button: this.button.bind(this)
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