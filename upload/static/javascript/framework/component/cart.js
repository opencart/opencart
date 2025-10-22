import { WebComponent } from './../webcomponent.js';

class XCart extends WebComponent {
    event = {
        connected: async () => {
            let html = '';

            html += '<div class="dropdown d-grid">';
            html += '  <button type="button" data-bs-toggle="dropdown" class="btn btn-lg btn-dark d-block dropdown-toggle"><i class="fa-solid fa-cart-shopping"></i> {{ text_items }}</button>';
            html += '  <ul class="dropdown-menu dropdown-menu-end p-2"></ul>';
            html += '</div>';

            this.innerHTML = html;

            let data = sessionStorage.getItem('cart');

            if (data !== undefined) {
                let response = this.event.fetch('index.php?route=common/cart.json');

                response.then(this.event.render);
            }
        },
        fetch: async (url) => {
            let response = await fetch(url);

            if (response.status == 200) {
                return response.json();
            }
        },
        render: (json) => {
            console.log(test);

            let language = this.language('cart');

            let html = '';

            if (json['products']) {
                html += '<li><table class="table table-striped mb-2">';

                for (let i in json['products']) {
                    html += '  <tr>';
                    html += '    <td class="text-center w-25">';

                    if (json['products'][i]['thumb']) {
                        html += '    <a href="{{ product.href }}"><img src="{{ product.thumb }}" alt="{{ product.name }}" title="{{ product.name }}" class="img-thumbnail"/></a>';
                    }

                    html += '    </td>';
                    html += '    <td><a href="{{ product.href }}">{{product.name}}</a>';
                    html += '      <ul class="list-unstyled mb-0">';
                    html += '        <li><small> - {{text_model}}: {{product.model}}</small></li>';

                    for (let j in json['products'][i]['option']) {
                        html += '  <li><small> - {{option.name}}: {{option.value}}</small></li>';
                    }

                    if (json['subscription']) {
                        html += '  <li><small> - {{text_subscription}}: {{product.subscription}}</small></li>';
                    }

                    if (json['reward']) {
                        html += '  <li><small> - {{text_points}}: {{product.reward}}</small></li>';
                    }

                    html += '    </ul></td>';
                    html += '    <td class="text-end text-nowrap">x {{ product.quantity }}</td>';
                    html += '    <td class="text-end"><x-currency code="{{ currency }}" amount="{{ product.total }}"></x-currency></td>';
                    html += '    <td class="text-end"><form action="{{ remove }}" method="post" data-oc-toggle="ajax" data-oc-load="{{ list }}" data-oc-target="#cart">';
                    html += '      <input type="hidden" name="key" value="{{ product.cart_id }}"/>';
                    html += '      <button type="submit" data-bs-toggle="tooltip" title="{{ button_remove }}" class="btn btn-danger"><i class="fa-solid fa-circle-xmark"></i></button>';
                    html += '    </form></td>';
                    html += '  </tr>';
                }

                html += '</table>';
                html += '<div>';
                html += '  <table class="table table-sm table-bordered mb-2">';

                for (let i in json['totals']) {
                    html += '  <tr>';
                    html += '    <td class="text-end"><strong>{{ total.title }}</strong></td>';
                    html += '    <td class="text-end"><x-currency code="{{ currency }}" amount="{{ total.value }}"></x-currency></td>';
                    html += '  </tr>';
                }

                html += '  </table>';
                html += '  <p class="text-end"><a href="{{ cart }}"><strong><i class="fa-solid fa-cart-shopping"></i> {{ text_cart }}</strong></a>&nbsp;&nbsp;&nbsp;<a href="{{ checkout }}"><strong><i class="fa-solid fa-share"></i> {{ text_checkout }}</strong></a></p>';
                html += '</div></li>';
            } else {
                html += '<li class="text-center p-4">{{ text_no_results }}</li>';
            }
        }
    };
}

customElements.define('x-cart', XCart);