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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-puzzle-piece"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="input-group">
            <input type="text" name="search" value="" placeholder="Search for extensions" class="form-control" />
            <div class="input-group-btn">
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">License <span class="caret"></span></button>
              <ul class="dropdown-menu">
                <?php foreach ($categories as $category) { ?>
                <li><a href="#">All Categories</a></li>
                <?php } ?>
              </ul>
            
            
            
              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">All Categories <span class="caret"></span></button>
              <ul class="dropdown-menu">
                <?php foreach ($categories as $category) { ?>
                <li><a href="#">All Categories</a></li>
                <?php } ?>
              </ul>
              <button type="button" class="btn btn-primary"><i class=""></i></button>
            </div>
          </div>
        </div>
        Sort
        <div id="store"></div>
      </div>
    </div>
  </div>
  
  
		$data['text_license'] = $this->language->get('text_list');
		$data['text_free'] = $this->language->get('text_list');
		$data['text_commercial'] = $this->language->get('text_list');
		$data['text_category'] = $this->language->get('text_list');
		$data['text_theme'] = $this->language->get('text_list');
		$data['text_payment'] = $this->language->get('text_list');
		$data['text_shipping'] = $this->language->get('text_list');
		$data['text_module'] = $this->language->get('text_list');
		$data['text_total'] = $this->language->get('text_list');
		$data['text_feed'] = $this->language->get('text_list');
		$data['text_report'] = $this->language->get('text_list');
		$data['text_other'] = $this->language->get('text_list');
  
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