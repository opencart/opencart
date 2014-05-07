CKEDITOR.plugins.add('opencart', {
	//icons: 'image',
	init: function(editor) {
		editor.addCommand('OpenCart', {
			exec: function(editor) {
				$.ajax({
					url: 'index.php?route=common/filemanager&token=' + getURLVar('token') + '&ckeditor=' + editor.name,
					dataType: 'html',		
					success: function(html) {
						$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
						
						$('#modal-image').modal('show');
					}
				});
			}
		});
		
		editor.ui.addButton('OpenCart', {
			label: 'OpenCart',
        	command: 'OpenCart',
			icon: this.path + 'images/icon.png'
		});
    }
});