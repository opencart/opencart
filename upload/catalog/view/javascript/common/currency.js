import { WebComponent } from '../component.js';

class CommonCurrency extends WebComponent {
    async connected() {
        await this.load.language('common/currency');

        this.innerHtml = this.load.template('common/currency', this.data);

        const currencies = await this.storage.fetch('localisation/currency');


        console.log(form);

        let elements = form.querySelectorAll('a');

        elements.forEach((element) => {

        });

        currency.addEventListener('click', async (e) => {
            let element = this;

            let code = $(element).attr('href');

            registry.local.set('currency', code);
        });
    }

    render(json) {
        let html = '';

        html += '<form id="form-language">';
        html += '  <div class="dropdown">';
        html += '    <a href="#" data-bs-toggle="dropdown" class="dropdown-toggle"><strong>$</strong> <span class="d-none d-md-inline">Currency</span> <i class="fa-solid fa-caret-down"></i></a>';
        html += '    <ul class="dropdown-menu">';

        for (let currency of currencies) {



            html += '      <li><a href="' + currency.code + '" class="dropdown-item">€ ' + currency.title + '</a></li>';
        }

        html += '     </ul>';
        html += '  </div>';
        html += '</form>';

        this.innerHtml = html;
    }
}

customElements.define('common-currency', CommonCurrency);