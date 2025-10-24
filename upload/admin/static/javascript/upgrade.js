$('#button-upgrade').on('click', function() {
    var element = this;

    $(element).button('loading');

    if (confirm('{{ text_confirm }}')) {
        $('#progress').html('{{ text_download|escape('js') }}');

        var next = 'index.php?route=tool/upgrade.download&user_token={{ user_token }}&version={{ latest_version }}';

        var upgrade = function() {
            return $.ajax({
                url: next,
                dataType: 'json',
                beforeSend: function() {
                    $(element).button('loading');
                },
                complete: function() {
                    $(element).button('reset');
                },
                success: function(json) {
                    console.log(json);

                    $('#input-upgrade').removeClass('is-valid is-invalid');

                    if (json['error']) {
                        $('#input-upgrade').val(json['error']);
                        $('#input-upgrade').addClass('is-invalid');

                        $(element).button('reset');
                    }

                    if (json['text']) {
                        $('#input-upgrade').val(json['text']);
                    }

                    if (json['success']) {
                        $('#input-upgrade').val(json['success']);
                        $('#input-upgrade').addClass('is-valid');

                        $(element).button('reset');
                    }

                    if (json['next']) {
                        next = json['next'];

                        chain.attach(upgrade);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);

                    $(element).button('reset');
                }
            });
        };

        chain.attach(upgrade);
    }
});