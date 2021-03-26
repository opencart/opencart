$(document).ready(function() {
	// Override summernotes image manager
	$('[data-toggle=\'summernote\']').each(function() {
		var element = this;

		if ($(this).attr('data-lang') && $(this).attr('data-lang')!='en') {
			$('head').append('<script type="text/javascript" src="view/javascript/summernote/lang/summernote-' + $(this).attr('data-lang') + '-' + $(this).attr('data-lang').toUpperCase() + '.min.js"></script>');
		}

		$(element).summernote({
			lang: $(this).attr('data-lang') + '-' + $(this).attr('data-lang').toUpperCase(),
			disableDragAndDrop: true,
			height: 300,
			emptyPara: '',
			codemirror: { // codemirror options
				mode: 'text/html',
				htmlMode: true,
				lineNumbers: true,
				theme: 'monokai'
			},
			fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '30', '36', '48' , '64'],
			toolbar: [
				['style', ['style']],
				['font', ['bold', 'underline', 'clear']],
				['fontname', ['fontname']],
				['fontsize', ['fontsize']],
				['color', ['color']],
				['para', ['ul', 'ol', 'paragraph']],
				['table', ['table']],
				['insert', ['link', 'image', 'video']],
				['view', ['fullscreen', 'codeview', 'help']]
			],
			popover: {
				image: [
					['custom', ['imageAttributes']],
					['resize', ['resizeFull', 'resizeHalf', 'resizeQuarter', 'resizeNone']],
					['float', ['floatLeft', 'floatRight', 'floatNone']],
					['remove', ['removeMedia']]
				],
				table: [
					['add', ['addRowDown', 'addRowUp', 'addColLeft', 'addColRight']],
					['delete', ['deleteRow', 'deleteCol', 'deleteTable']]
				],
			},
			buttons: {
    			image: function() {
					var ui = $.summernote.ui;

					// create button
					var button = ui.button({
						contents: '<i class="note-icon-picture" />',
						tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
						click: function () {
							$('#modal-image').remove();

							$.ajax({
								url: 'index.php?route=common/filemanager&user_token=' + getURLVar('user_token'),
								dataType: 'html',
								beforeSend: function() {
									$('#button-image i').replaceWith('<i class="fa fa-circle-o-notch fa-spin"></i>');
									$('#button-image').prop('disabled', true);
								},
								complete: function() {
									$('#button-image i').replaceWith('<i class="fa fa-upload"></i>');
									$('#button-image').prop('disabled', false);
								},
								success: function(html) {
									$('body').append('<div id="modal-image" class="modal">' + html + '</div>');

									$('#modal-image').modal('show');

									$('#modal-image').delegate('a.thumbnail', 'click', function(e) {
										e.preventDefault();

										$(element).summernote('insertImage', $(this).attr('href'));

										$('#modal-image').modal('hide');
									});
								}
							});
						}
					});

					return button.render();
				}
  			}
		});
	});
});
