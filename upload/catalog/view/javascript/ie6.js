$(document).ready(function() {
	$('#menu > ul > li').bind('mouseover', function() {
		$(this).addClass('active');
	});
	
	
	$('#menu > ul > li').bind('mouseout', function() {
		$(this).removeClass('active');
	});
});	