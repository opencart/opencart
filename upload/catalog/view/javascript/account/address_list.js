class XAddressList extends WebComponent {
    async connected() {
        await this.load.language('account/address');

        this.innerHtml = await this.load.template('account/address', this.language.all());

        let buttons = this.querySelectorAll('.btn-danger');

        for (let button of buttons) {
            button.addEventListener('click', this.onClick);
        }
    }

    onClick() {
        let dismissible = document.querySelectorAll('.alert-dismissible');

        dismissible.remove();

        this.request.post({
            url: '',
            success: this.onComplete
        });
    }

    onSuccess(json) {
        let alert = document.getElementById('alert');

        if (json['error']) {
            alert.append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }

        if (json['success']) {
            alert.append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
        }
    }
}

customElements.define('x-address-list', XAddressList);

$('#address').on('click', '.btn-danger', function(e) {
    e.preventDefault();

    var element = this;

    $.ajax({
        url: $(element).attr('href'),
        dataType: 'json',
        beforeSend: function() {
            $(element).button('loading');
        },
        complete: function() {
            $(element).button('reset');
        },
        success: function(json) {
            console.log(json);

            $('.alert-dismissible').remove();

            if (json['error']) {
                $('#alert').append('<div class="alert alert-danger alert-dismissible"><i class="fa-solid fa-circle-exclamation"></i> ' + json['error'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');
            }

            if (json['success']) {
                $('#alert').append('<div class="alert alert-success alert-dismissible"><i class="fa-solid fa-circle-check"></i> ' + json['success'] + ' <button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>');

                $('#address').load('index.php?route=account/address.list&language=' + language + '&customer_token={{ customer_token }}');
            }
        },
        error: function(xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
});