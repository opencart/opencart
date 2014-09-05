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

function getFaq(){
    var route = $.getUrlVar('route');
    var token = $.getUrlVar('token');

    $.ajax({
        url: 'index.php?route=extension/openbay/faqGet&token='+token+'&qry_route='+route,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if(data.faq_id){
                var htmlInj = '';

                htmlInj += '<div class="row">';
                    htmlInj += '<div class="col-md-5 col-md-offset-7">';
                        htmlInj += '<div id="faq" class="alert alert-info">';
                            htmlInj += ' <button type="button" class="close" data-dismiss="alert" onclick="hideFaq();">&times;</button>';
                            htmlInj += '<h4><i class="fa fa-info-circle"></i> '+data.title+'</h4>';
                            htmlInj += '<h5>'+data.message+'<label class="label label-info pull-right"><a class="alert-link" href="'+data.link+'" target="_BLANK">'+data.button_faq+'</a></label></h5>';
                        htmlInj += '</div>';
                    htmlInj += '</div>';
                htmlInj += '</div>';

                //$('#content').prepend(htmlInj);
                $('#faq').slideDown('slow');
            }
        }
    });
}

$(document).ready(function(){
    getFaq();
});

$('#faq-close').bind('click', function() {
    var route = $.getUrlVar('route');
    var token = $.getUrlVar('token');

    $('#faq').fadeOut();

    $.ajax({
        url: 'index.php?route=extension/openbay/faqDismiss&token='+token+'&qry_route='+route,
        type: 'GET',
        dataType: 'json',
        success: function(data) {}
    });
    return false;
});