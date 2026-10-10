import {WebComponent} from '../index.js';
import { loader, ajax, customer, local } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('catalog/review');

customElements.define('review-form', class extends WebComponent {
    constructor() {
        super();

        this.token = '';
    }

    async render() {
        let data = new Map();

        return loader.template('catalog/review_form', [data, language, config]);
    }

    async onConnect() {
        let json = await ajax.get('action.php?route=account/review.token&language=' + local.get('language') + '&customer_token=' + customer.getToken());

        if (json.has('token')) {
            this.token = json.get('token');
        }
    }

    onSubmit(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=catalog/review.write&language=' + local.get('language') + '&review_token=' + this.token + '&product_id=' + this.getAttribute('product_id'), form, {
            beforeSend: () => {
                this.form.state.set('submitting', true);
            },
            onComplete: () => {
                this.form.state.set('submitting', false);
            },
            success: (json) => {
                this.form.state.clear();

                if (json.has('error')) {
                    this.form.state.set('error', json.get('error'));
                }

                if (json.has('success')) {
                    this.form.state.set('success', json.get('success'));

                    this.update();
                }
            },
            error: (xhr, ajaxOptions, thrownError) => {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
});
