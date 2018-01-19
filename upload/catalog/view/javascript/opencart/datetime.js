$('.date').datetimepicker({
	language: '{{ datepicker }}',
	pickTime: false
});

$('.datetime').datetimepicker({
	language: '{{ datepicker }}',
	pickDate: true,
	pickTime: true
});

$('.time').datetimepicker({
	language: '{{ datepicker }}',
	pickDate: false
});