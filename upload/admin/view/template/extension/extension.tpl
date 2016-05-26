<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-puzzle-piece"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-store" data-toggle="tab"><?php echo $tab_store; ?></a></li>
          <li><a href="#tab-installer" data-toggle="tab"><?php echo $tab_installer; ?></a></li>
          <li><a href="#tab-downloaded" data-toggle="tab"><?php echo $tab_downloaded; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-store"></div>
          <div class="tab-pane" id="tab-installer"></div>
          <div class="tab-pane" id="tab-downloaded">
            <div class="well">
              <select name="type" class="form-control">
                <?php foreach ($categories as $category) { ?>
                <option value="<?php echo $category['value']; ?>"><?php echo $category['text']; ?></option>
                <?php } ?>
              </select>
            </div>
            <div id="extension"></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#tab-store').load('index.php?route=extension/store&token=<?php echo $token; ?>');

$('#tab-installer').load('index.php?route=extension/installer&token=<?php echo $token; ?>');

$('#tab-downloaded select[name="type"]').bind('change', function() {
	var node = this;
	
	$.ajax({
		url: 'index.php?route=extension/extension/' + $(this).val() + '&token=<?php echo $token; ?>',
		dataType: 'html',
		beforeSend: function() {
			$(node).prop('disabled', true);
		},
		complete: function() {
			$(node).prop('disabled', false);
		},
		success: function(html) {
			if (json['error']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}

			if (json['success']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

				$('#button-clear').prop('disabled', true);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#tab-downloaded input[name="type"]').trigger('change');



//--></script>
</div>
<?php echo $footer; ?> 