CKEDITOR.plugins.add('image_custom', {
	//icons: 'image',
	init: function(editor) {
		editor.addCommand('InsertImage', {
			exec: function(editor) {
			
				$.ajax({
					url: 'index.php?route=common/filemanager&token=' + getURLVar('token'),
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
	
	
							
				//editor.insertHtml('<img src="http://localhost/opencart/upload/image/cache/catalog/demo/macbook_1-80x80.jpg" alt="" title="" />');
				
				//alert(editor.getData());
				
				//alert('hi');
				//addCKEditorImage();
				//addCKEditorImage();
				/*
				$('#modal-image').remove();
				
				$.ajax({
					url: 'index.php?route=common/filemanager',
					dataType: 'html',			
					success: function(html) {
						$('body').append('<div id="modal-image" class="modal">' + html + '</div>');
						
						$('#modal-image').modal('show');
					}
				});
				*/				
			}
		});
		
		editor.ui.addButton('InsertImage', {
			label: editor.lang.image.upload,
        	command: 'InsertImage',
			icon: this.path + 'images/image_add.png'
		});
    }
});