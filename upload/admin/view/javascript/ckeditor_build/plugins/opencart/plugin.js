CKEDITOR.plugins.add('opencart', {
	init: function(editor) {
		editor.addCommand('OpenCart', {
			exec: function(editor) {
				$.ajax({
					url: 'index.php?route=common/filemanager&user_token=' + getURLVar('user_token') + '&ckeditor=' + editor.name,
					dataType: 'html',
					success: function(html) {
						$('body').append(html);

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