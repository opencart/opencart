CKEDITOR.plugins.add('opencart', {
	//icons: 'image',
	init: function(editor) {
		editor.addCommand('OpenCart', {
			exec: function(editor) {
				$.ajax({
					url: 'index.php?route=common/filemanager&token=' + getURLVar('token') + '&ckeditor=' + editor.name,
					dataType: 'html',	
					beforeSend: function() {
						$('#button-upload i').replaceWith('<i class="fa fa-spinner fa-spin"></i>');
						$('#button-upload').prop('disabled', true);
					},
					complete: function() {
						$('#button-upload i').replaceWith('<i class="fa fa-upload"></i>');
						$('#button-upload').prop('disabled', false);
					},				
					success: function(html) {
						$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
						
						$('#modal-image').modal('show');
					}
				});
			}
		});
		
		editor.ui.addButton('OpenCart', {
			label: editor.lang.image,
        	command: 'OpenCart',
			icon: this.path + 'images/image_add.png'
		});
    }
});