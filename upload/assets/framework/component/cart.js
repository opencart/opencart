export default class XCart extends WebComponent {
    element = HTMLInputElement;
    data = [];

    event = {
        connected: async () => {
            let language = this.language.fetch('common/cart');

            language.then(this.event.language);

            this.element = this.querySelector('.dropdown-menu');

           // let data = sessionStorage.getItem('cart');

            //if (data !== undefined) {
            //    let response = this.event.fetch('index.php?route=checkout/cart.json');

            //    response.then(this.event.render);
            //}
        },
        fetch: async (url) => {
            let response = await fetch(url);

            if (response.status == 200) {
                return response.json();
            }
        },
        language: (json) => {
            this.data.language = json;

            let html = '';

            html += '<div class="dropdown d-grid">';
            html += '  <button type="button" data-bs-toggle="dropdown" class="btn btn-lg btn-dark d-block dropdown-toggle"><i class="fa-solid fa-cart-shopping"></i> ' + this.data.language['text_items'] + '</button>';
            html += '  <ul class="dropdown-menu dropdown-menu-end p-2"></ul>';
            html += '</div>';

            this.innerHTML = html;

            let response = this.event.fetch('index.php?route=checkout/cart.json');

            response.then(this.event.render);

            //console.log(this.data);
        },
        render: (json) => {
            //console.log(this.storage);
            //console.log(this.data);

            let html = '';

            if (json['products']) {
                console.log(json);

                html += '<li><table class="table table-striped mb-2">';

                for (let i in json['products']) {
                    html += '  <tr>';
                    html += '    <td class="text-center w-25">';

                    if (json['products'][i]['thumb']) {
                        html += '    <a href="' + json['products'][i]['href'] + '"><img src="' + json['products'][i]['thumb'] + '" alt="' + json['products'][i]['name'] + '" title="' + json['products'][i]['name'] + '" class="img-thumbnail"/></a>';
                    }

                    html += '    </td>';
                    html += '    <td><a href="' + json['products'][i]['href'] + '">' + json['products'][i]['name'] + '</a>';
                    html += '      <ul class="list-unstyled mb-0">';
                    html += '        <li><small> - {{ text_model }}: ' + json['products'][i]['model'] + '</small></li>';

                    for (let j in json['products'][i]['option']) {
                        html += '  <li><small> - ' + json['products'][i]['option'][j]['name'] + ': ' + json['products'][i]['option'][j]['value'] + '</small></li>';
                    }

                    if (json['products'][i]['subscription']) {
                        html += '  <li><small> - {{ text_subscription }}: ' + json['products'][i]['subscription'] + '</small></li>';
                    }

                    if (json['products'][i]['reward']) {
                        html += '  <li><small> - {{ text_points }}: ' + json['products'][i]['reward'] + '</small></li>';
                    }

                    html += '    </ul></td>';
                    html += '    <td class="text-end text-nowrap">x ' + json['products'][i]['quantity'] + '</td>' ;
                    html += '    <td class="text-end"><x-currency code="{{ currency }}" amount="' + json['products'][i]['total'] + '"></x-currency></td>';
                    html += '    <td class="text-end"><form action="{{ remove }}" method="post" data-oc-target="#cart">';
                    html += '      <input type="hidden" name="key" value="' + json['products'][i]['cart_id'] + '"/>';
                    html += '      <button type="submit" data-bs-toggle="tooltip" title="{{ button_remove }}" class="btn btn-danger"><i class="fa-solid fa-circle-xmark"></i></button>';
                    html += '    </form></td>';
                    html += '  </tr>';
                }

                html += '</table>';
                html += '<div>';
                html += '  <table class="table table-sm table-bordered mb-2">';

                for (let i in json['totals']) {
                    html += '  <tr>';
                    html += '    <td class="text-end"><strong>' + json['totals'][i]['title'] + '</strong></td>';
                    html += '    <td class="text-end"><x-currency code="{{ currency }}" amount="' + json['totals'][i]['value'] + '"></x-currency></td>';
                    html += '  </tr>';
                }

                html += '  </table>';
                html += '  <p class="text-end"><a href="{{ cart }}"><strong><i class="fa-solid fa-cart-shopping"></i> {{ text_cart }}</strong></a>&nbsp;&nbsp;&nbsp;<a href="{{ checkout }}"><strong><i class="fa-solid fa-share"></i> {{ text_checkout }}</strong></a></p>';
                html += '</div></li>';
            } else {
                html += '<li class="text-center p-4">{{ text_no_results }}</li>';
            }

            this.element.innerHTML = html;
        }
    };
}

