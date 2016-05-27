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
                
                <?php if ($type == $category['value']) { ?>
                <option value="<?php echo $category['value']; ?>" selected="selected"><?php echo $category['text']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $category['value']; ?>"><?php echo $category['text']; ?></option>
                <?php } ?>
                
                <?php } ?>
              </select>
              <div class="input-group-btn">
                <button type="button" id="button-filter" class="btn btn-primary btn-lg">Go!</button>
              </div>
            </div>
          </div>
        </fieldset>
        <div id="extension"></div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	$.ajax({
		url: 'index.php?route=extension/extension/' + $('select[name="type"]').val() + '&token=<?php echo $token; ?>',
		dataType: 'html',
		beforeSend: function() {
			$('#button-filter').button('loading');
		},
		complete: function() {
			$('#button-filter').button('reset');
		},
		success: function(html) {
			$('#extension').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#button-filter').trigger('click');
//--></script> 
</div>
<?php echo $footer; ?> 