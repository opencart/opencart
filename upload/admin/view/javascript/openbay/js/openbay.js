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

$(window).resize(function(){
    $('#greyScreen').css("display") == 'block'?showGreyScreen():"";
});

$('.previewClose').bind('click', function(){
    hideGreyScreen();
});

$(document).ready(function(){
    hideGreyScreen();
});