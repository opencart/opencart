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
        <fieldset>
          <legend>Extension Filter</legend>
          <div class="well">
            <div class="input-group">
              <select name="type" class="form-control input-lg">
                <?php foreach ($categories as $category) { ?>
                <option value="<?php echo $category['value']; ?>"><?php echo $category['text']; ?></option>
                <?php } ?>
              </select>
              <div class="input-group-btn">
                <button class="btn btn-primary btn-lg" type="button">Go!</button>
              </div>
            </div>
          </div>
        </fieldset>
        <div id="extension"></div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('select[name="type"]').bind('change', function() {
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
			$('#extension').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name="type"]').trigger('change');
//--></script> 
</div>
<?php echo $footer; ?> 