import { WebComponent } from '../library/webcomponent.js';
import CustomEditor from './../../ckeditor/ckeditor.js';

customElements.define('x-ckeditor', class extends WebComponent {
    element = HTMLInputElement;
    editor = [];

    async connected() {
        this.editor = null;
        //<textarea name="category_description[{{ language.language_id }}][description]" placeholder="{{ entry_description }}" id="input-description-{{ language.language_id }}" data-oc-toggle="ckeditor" data-lang="{{ ckeditor }}" class="form-control">{{ category_description[language.language_id] ? category_description[language.language_id].description }}</textarea>
        let editor = document.createElement('div');

        CustomEditor.create(editor, {
            // Optional: Configure your editor here
        });

        return editor;
    }

    disconnect() {
        if (this.editor) {
            this.editor.destroy();
        }
    }

    render() {

    }
});