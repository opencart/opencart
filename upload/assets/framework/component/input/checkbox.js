import { WebComponent } from '../../engine.js';

customElements.define('input-checkbox', class extends WebComponent {
    static formAssociated = true;

    render() {
        return '<input type="checkbox" class="form-check-input" data-on="change:onChange" data-target="' + this.getAttribute('target') + '"/>';
    }

    handleChange(e) {
        let stack = [];

        let elements = document.querySelectorAll(e.target.getAttribute('data-target'));

        for (let element of elements)  {
            if (element.matches('input[type=\'checkbox\']')) {
                stack.push(element);
           } else {
               let checkboxes = element.querySelectorAll('input[type=\'checkbox\']');

               for (let checkbox of checkboxes) {
                   stack.push(checkbox);
               }
           }
        }

        for (let element of stack) {
            if (!element.parentElement.matches('checkbox-all')) {
                element.toggleAttribute('checked', !e.target.checked);
            }
        }
    }
});