import { WebComponent } from './../webcomponent.js';

class XCart extends WebComponent {
    static observed = [''];
    default = HTMLInputElement;
    element = HTMLInputElement;
    countries = [];

    event = {
        connected: async () => {
            this.innerHTML = '';


        },
        onloaded: (countries) => {

        },
        render: (json) => {

            let html = ' <div class="dropdown d-grid">';

            html += '<button type="button" data-bs-toggle="dropdown" class="btn btn-lg btn-dark d-block dropdown-toggle"><i class="fa-solid fa-cart-shopping"></i> {{ text_items }}</button>';
            html += '  <ul class="dropdown-menu dropdown-menu-end p-2">';


            if (json[products]) {

            html += '  <li><table class="table table-striped mb-2">';

            //{% for product in products %}

            html += '    <tr>';
            html += '      <td class="text-center w-25">{% if product.thumb %}<a href="{{ product.href }}"><img src="{{ product.thumb }}" alt="{{ product.name }}" title="{{ product.name }}" class="img-thumbnail"/></a>{% endif %}</td>';
            html += '      <td><a href="{{ product.href }}">{{ product.name }}</a>';
            html += '        <ul class="list-unstyled mb-0">';
            html += '          <li><small> - {{ text_model }}: {{ product.model }}</small></li>';

            //{% for option in product.option %}
            html += '          <li><small> - {{ option.name }}: {{ option.value }}</small></li>';
            //{% endfor %}

            //{% if product.subscription %}
            html += '          <li><small> - {{ text_subscription }}: {{ product.subscription }}</small></li>';
            //{% endif %}

            //{% if product.reward %}
            html += '          <li>small> - {{ text_points }}: {{ product.reward }}</small></li>';
            //{% endif %}

            html += '        </ul></td>';
            html += '        <td class="text-end text-nowrap">x {{ product.quantity }}</td>';
            html += '        <td class="text-end"><x-currency code="{{ currency }}" amount="{{ product.total }}"></x-currency></td>';
            html += '        <td class="text-end"><form action="{{ remove }}" method="post" data-oc-toggle="ajax" data-oc-load="{{ list }}" data-oc-target="#cart"><input type="hidden" name="key" value="{{ product.cart_id }}"><button type="submit" data-bs-toggle="tooltip" title="{{ button_remove }}" class="btn btn-danger"><i class="fa-solid fa-circle-xmark"></i></button></form>';
            html += '      </td>';
            html += '   </tr>';

            //{% endfor %}

            html += '  </table>';

            html += '  <div>';
            html += '    <table class="table table-sm table-bordered mb-2">';
            //{% for total in totals %}
            html += '      <tr>';
            html += '        <td class="text-end"><strong>{{ total.title }}</strong></  td>';
            html += '        <td class="text-end"><x-currency code="{{ currency }}" amount="{{ total.value }}"></x-currency></td>';
            html += '      </tr>';
            //{% endfor %}
            html += '    </table>';
            html += '    <p class="text-end"><a href="{{ cart }}"><strong><i class="fa-solid fa-cart-shopping"></i> {{ text_cart }}</strong></a>&nbsp;&nbsp;&nbsp;<a href="{{ checkout }}"><strong><i class="fa-solid fa-share"></i> {{ text_checkout }}</strong></a></p>';
            html += '  </div>';
            html += '</li>';

            //{% else %}
            html += '       <li class="text-center p-4">{{ text_no_results }}</li>';
            //{% endif %}
            html += '  </ul>';
            html += ' </div>';

            this.element.innerHTML = html;
        },
        postcode: () => {
            if (this.countries[this.value] !== undefined) {
                this.postcode = this.countries[this.value].postcode_required;
            } else {
                this.postcode = 0;
            }
        },
        onchange: (e) => {
            this.value = e.target.value;
        },
        changeValue: (e) => {
            this.value = e.detail.value_new;
        }
    };
}

customElements.define('x-cart', XCart);