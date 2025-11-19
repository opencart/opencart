import CustomEditor from './../../ckeditor/ckeditor.js';

class XCkeditor extends WebComponent {
    element = HTMLInputElement;
    editor = [];

    event = {
        connected: async () => {
            this.editor = null;
             //  <textarea name="category_description[{{ language.language_id }}][description]" placeholder="{{ entry_description }}" id="input-description-{{ language.language_id }}" data-oc-toggle="ckeditor" data-lang="{{ ckeditor }}" class="form-control">{{ category_description[language.language_id] ? category_description[language.language_id].description }}</textarea>
            let editor = document.createElement('div');

            CustomEditor.create(editor, {
                // Optional: Configure your editor here
            })

            this.innerHTML = editor;

        },
        onloaded: (countries) => {

        },
        disconnect: () => {
            if (this.editor) {
                this.editor.destroy();
            }
        }
    };
}

customElements.define('x-ckeditor', XCkeditor);