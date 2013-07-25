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

                htmlInj += '<div id="faqPopupContainer">';
                htmlInj += '<span id="faqClose" onclick="hideFaq();">X</span>';
                htmlInj += '<h4>'+data.title+'</h4>';
                htmlInj += '<p>'+data.message+'</p>';
                htmlInj += '<p class="buttons">';
                    htmlInj += '<a class="button" style="float:right;" href="'+data.link+'" target="_BLANK"><span>'+data.faqbtn+'</span></a>';
                htmlInj += '</p>';
                htmlInj += '</div>';

                $('#content').prepend(htmlInj);
                $('#faqPopupContainer').fadeIn();
            }
        }
    });
}

$(document).ready(function(){
    getFaq();
});

function hideFaq(){
    var route = $.getUrlVar('route');
    var token = $.getUrlVar('token');
    
    $('#faqPopupContainer').fadeOut();
    
    $.ajax({
        url: 'index.php?route=extension/openbay/faqDismiss&token='+token+'&qry_route='+route,
        type: 'GET',
        dataType: 'json',
        success: function(data) {}
    });
    return false;
}