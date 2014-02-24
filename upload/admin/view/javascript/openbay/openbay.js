function showGreyScreen(boxDiv){
    $('#greyScreen').css({	"display": "block", opacity: 0.6, "width":$(document).width()+20,"height":$(document).height(), "z-index":99998}).show();
    $('#'+boxDiv).fadeIn().css({"display": "block", "z-index":99999});
    $('body').css({"overflow":"hidden"});
}

function hideGreyScreen(){
    $('#greyScreen').css("display", "none");
    $('.greyScreenBox').css("display", "none");
    $('body').css({"overflow":"scroll"});
}

function showTooltip(tooldiv, title, desc){
    $('#'+tooldiv).css({
        'display':'inline',
        'position':'absolute',
        'margin-top':'-35px',
        'min-width':'153px',
        'background-color' : '#EFEFEF',
        'border' : 'solid 1px #898989',
        'padding' : '7px'}).html('<strong>' + title + ':</strong> ' + desc + '</div>');
}

function hideTooltip(div){
    $('#'+div).css('display','none');
}

$(window).resize(function(){
    $('#greyScreen').css("display") == 'block'?showGreyScreen():"";
});

$('.previewClose').live('click', function(){
    hideGreyScreen();
});

$(document).ready(function(){
    hideGreyScreen();
});