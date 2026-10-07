import { WebComponent } from '../index.js';
import { loader, ajax, cart, customer, local, tax } from '../index.js';

// Config
const config = await loader.config('default');

// Language
const language = await loader.language('checkout/cart');

export default class CheckoutCart extends WebComponent {
    async render(){
        let data = new Map();

        data.set('products', cart.getProducts());

        data.set('shipping', cart.hasShipping());
        data.set('download', cart.hasDownload());
        data.set('minimum', cart.hasMinimum());
        data.set('weight', cart.getWeight());

        data.set('currency', local.get('currency'));

        return loader.template('checkout/cart', [ data, language, config ]);
    }

    editProduct(e) {
        e.preventDefault();

        let form = new FormData(this.form);

        ajax.post('action.php?route=checkout/cart.add', form, {
            beforeSend: () => {
                this.submitter.state.add('loading');
            },
            onComplete: () => {
                this.submitter.state.remove('loading');
            },
            onSuccess: (json) => {
                // Remove past error classes from inputs
                this.form.querySelectorAll('.is-invalid').forEach(element => element.classList.remove('is-invalid'));
                this.form.querySelectorAll('.invalid-feedback').forEach(element => element.classList.remove('d-block'));

                if (json.has('redirect')) {
                    location = json.get('redirect');
                }

                // Display error messages
                if (json.has('error')) {
                    for (let key in json['error']) {
                        let value = key.replaceAll('_', '-');

                        let input = target.querySelector('#input-' + value);

                        if (input) {
                            input.classList.add('is-invalid');

                            // If the element has inputs inside.
                            input.querySelectorAll('.form-control, .form-select, .form-check-input, .form-check-label').forEach(element => element.classList.add('is-invalid'));
                        }

                        let error = target.querySelector('#error-' + value);

                        if (error) {
                            error.classList.add('d-block');
                        }
                    }
                }

                // Display success message
                if (json.has('success')) {
                    this.alert.prepend('<ui-alert type="success">' + json.get('success') + '</ui-alert>');

                    //console.log(Object.fromEntries(form));
                    for (let product of json['products']) {
                        cart.add(product);
                    }

                    this.cart.update();
                }
            },
            onError: (e) => {
                console.log('onError', e);
            }
        });
    }

    deleteProduct(e) {
        e.preventDefault();

        var element = this;

        ajax.post('', {
            beforeSend: function() {
                this.submitter.state.add('loading');
            },
            complete: function() {
                this.submitter.state.remove('loading');
            },
            success: function(json) {
                console.log(json);

                if (json['redirect']) {
                    location = json['redirect'];
                }

                if (json.has('error')) {
                    $('#alert').prepend('<ui-alert type="danger">' + json['error'] + '</ui-alert>');
                }

                if (json.has('success')) {
                    $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json.get('success') + '</ui-alert>');

                    //$('#shopping-cart').load('action.php?route=checkout/cart.list&language={{ language }}');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
}

customElements.define('checkout-cart', CheckoutCart);

/*
$('#shopping-cart').on('submit', '#output-cart form', function(e) {
    e.preventDefault();

    var element = this;

    if (e.originalEvent !== undefined && e.originalEvent.submitter !== undefined) {
        var button = e.originalEvent.submitter;
    } else {
        var button = '';
    }

    $.ajax({
        url: $(button).attr('formaction'),
        type: 'post',
        data: $(element).serialize(),
        dataType: 'json',
        beforeSend: function() {
            $(button).button('loading');
        },
        complete: function() {
            $(button).button('reset');
        },
        success: function(json) {
            console.log(json);

            if (json['redirect']) {
                location = json['redirect'];
            }

            if (json['error']) {
                $('#alert').prepend('<ui-alert type="danger">' + json['error'] + '</ui-alert>');
            }

            if (json.has('success')) {
                $('#alert').prepend('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json.get('success') + '</ui-alert>');

                $('#shopping-cart').load('action.php?route=checkout/cart.list&language={{ language }}', {}, function() {
                    $('#cart').load('action.php?route=common/cart.info&language={{ language }}');
                });
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});

$('#shopping-cart').on('click', '.btn-danger', function(e) {

});

$('#shopping-cart').observe(function(e) {
    $('#cart').load('action.php?route=common/cart.info&language={{ language }}');
});

$('#cart').on('submit', 'form', function(e) {
    window.setTimeout(function() {
        $('#shopping-cart').load('action.php?route=checkout/cart.list&language={{ language }}');
    }, 3000);
});
*/