<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-banner').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-banner">
          <div class="row">
            <div class="col-lg-3 col-md-4 col-sm-12">
              <div class="table-responsive">
                <div class="list-group"> <a href="" class="list-group-item">Cras justo odio</a> <a href="" class="list-group-item">Dapibus ac facilisis in</a> <a href="" class="list-group-item">Morbi leo risus</a> <a href="" class="list-group-item">Porta ac consectetur ac</a> <a href="" class="list-group-item"><i class="fa fa-arrow-left"></i> Back</a> </div>
              </div>
            </div>
            <div class="col-lg-9 col-md-8 col-sm-12">
              <textarea name="code" rows="5" id="input-code" class="form-control"></textarea>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$(document).ready(function() {	
	$('#input-code').summernote({
		disableDragAndDrop: false,
		height: 300,
		codemirror: { // codemirror options
			theme: 'monokai'
		},	
		toolbar: [
			['style', ['style']],
			['font', ['bold', 'underline', 'clear']],
			['fontname', ['fontname']],
			['color', ['color']],
			['para', ['ul', 'ol', 'paragraph']],
			['table', ['table']],
			['insert', ['link', 'image', 'video']],
			['view', ['fullscreen', 'codeview', 'help']]
		],
		buttons: {
			image: function() {
				var ui = $.summernote.ui;

				// create button
				var button = ui.button({
					contents: '<i class="fa fa-image" />',
					tooltip: $.summernote.lang[$.summernote.options.lang].image.image,
					click: function () {
						$('#modal-image').remove();
					
						$.ajax({
							url: 'index.php?route=common/filemanager&token=' + getURLVar('token'),
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
//--></script> 
</div>
<?php echo $footer; ?>