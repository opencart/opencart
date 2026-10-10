import { WebComponent } from '../index.js';
import { loader, ajax, local, customer } from '../index.js';

// Language
const language = await loader.language('information/gdpr');

export default class InformationGdpr extends WebComponent {
    async render() {


        return loader.template('information/gdpr', [ language ]);
    }

    handleChange() {
        if (this.value == 'remove') {
            $('#collapse-remove').slideDown();
        } else {
            $('#collapse-remove').slideUp();
        }
    }

    handleSubmit() {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=information/gdpr.action&language=' + local.get('language') + '&customer_token=' + customer.getToken(), form, {
            beforeSend: () => {
                this.form.state.set('submitting', true);
            },
            onComplete: () => {
                this.form.state.set('submitting', false);
            },
            onSuccess: (json)=> {
                this.form.state.clear();

                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                // Display success message
                if (json.has('success')) {
                    this.state.set('success', json.get('success'));

                    this.update();
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }
}

customElements.define('information-gdpr', InformationGdpr);