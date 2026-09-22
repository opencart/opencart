import { WebComponent } from '../component.js';

customElements.define('a-ajax', class extends WebComponent {
    render() {
        return `<a href="${this.getAttribute('href')}" data-on="click:onClick">${this.innerHTML}</a>`;
    }

    onClick(e) {
        e.preventDefault();

        let target = document.getElementById('content');

        target.src = e.currentTarget.getAttribute('href');
    }
});