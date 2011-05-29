$(document).ready(function() {
	$('#column-left + #column-right + #content, #column-left + #content').css('margin-left', '195px');
	
	$('#column-right + #content').css('margin-right', '195px');

	$('.box-category ul li a.active + ul').css('display', 'block');	
	
	$('#menu > ul > li').bind('mouseover', function() {
		$(this).addClass('active');
	});
		
	$('#menu > ul > li').bind('mouseout', function() {
		$(this).removeClass('active');
	});
});	