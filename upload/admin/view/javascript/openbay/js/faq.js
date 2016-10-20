$.extend({
    getUrlVars: function(){
        var vars = [], hash;
        var hashes = window.location.href.slice(window.location.href.indexOf('?') + 1).split('&');
        for(var i = 0; i < hashes.length; i++)
        {
            hash = hashes[i].split('=');
            vars.push(hash[0]);
            vars[hash[0]] = hash[1];
        }
        return vars;
    },
    getUrlVar: function(name){
        return $.getUrlVars()[name];
    }
});

function faq(){
    var route = $.getUrlVar('route');
    var token = $.getUrlVar('token');

    $.ajax({
        url: 'index.php?route=extension/openbay/faq&token='+token+'&qry_route='+route,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if(data.faq_id){
                var html = '';

                html += '<div class="container-fluid" id="faq" style="display:none;">';
                    html += '<div class="alert alert-info">';
                    	html += '<div class="pull-right">';
				            html += '<a class="btn btn-primary" href="' + data.link + '" target="_BLANK" data-toggle="tooltip" title="' + data.button_faq + '"><i class="fa fa-info-circle"></i></a> ';
							html += '<button onclick="faqclose();" type="button" class="btn btn-danger" data-toggle="tooltip" title="' + data.button_close + '" id="faq-close"><i class="fa fa-minus-circle"></i></button>';
						html += '</div>';
						html += '<h5>' + data.title + '</h5>';
						html += '<p>' + data.message + '</p>';
                    html += '</div>';
                html += '</div>';

                $('.page-header:first').after(html);

				setTimeout(function() {
					$('#faq').slideDown('slow');
				}, 2000);
            }
        }
    });
}

function faqclose() {
    var route = $.getUrlVar('route');
    var token = $.getUrlVar('token');

    $('#faq').slideUp();

    $.ajax({
        url: 'index.php?route=extension/openbay/faqdismiss&token='+token+'&qry_route='+route,
        type: 'GET',
        dataType: 'json',
        success: function(data) {}
    });
    return false;
}

$(document).ready(function(){
    faq();
});
