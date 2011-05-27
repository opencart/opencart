$(document).ready(function() {
	$('#menu > ul > li > a').bind('mouseover', function() {
		$(this).addClass('active');
	});
	
	
	$('#menu > ul > li > a').bind('mouseout', function() {
		$(this).removeClass('active');
	});
});	