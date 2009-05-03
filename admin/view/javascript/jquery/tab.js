$.tabs = function(selector, start) {
	$(selector).each(function(i, element) {
		$($(element).attr('tab')).css('display', 'none');
		
		$(element).click(function() {
			$(selector).each(function(i, element) {
				$(element).removeClass('selected');
				
				$($(element).attr('tab')).css('display', 'none');
			});
			
			$(this).addClass('selected');
			
			$($(this).attr('tab')).css('display', 'block');
		});
	});
	
	if (!start) {
		start = $(selector + ':first').attr('tab');
	}

	$(selector + '[tab=\'' + start + '\']').trigger('click');
};