import { WebComponent } from '../component.js';

export class InputCheckbox extends WebComponent {
    render() {
        return '<input type="checkbox"' + (this.hasAttribute('input-id') ? ' id="' + this.getAttribute('input-id') + '"' : '') + ' class="form-check-input" data-on="change:onChange" data-target="' + this.getAttribute('target') + '"/>';
    }

    onChange(e) {
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
}