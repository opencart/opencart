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

              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">License (All) <span class="caret"></span></button>
              
              <ul class="dropdown-menu">
                <li class="dropdown-header">License</li>
                <li><a href="">License</a></li>
                <li><a href="free"><?php echo $text_free; ?></a></li>
                <li><a href="paid"><?php echo $text_paid; ?></a></li>
              </ul>

              <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Categories (Themes) <span class="caret"></span></button>
              
              <ul class="dropdown-menu">
                <li><a href="">All Categories</a></li>
                <li><a href="theme"><?php echo $text_theme; ?></a></li>
                <li><a href="payment"><?php echo $text_payment; ?></a></li>
                <li><a href="shipping"><?php echo $text_shipping; ?></a></li>
                <li><a href="module"><?php echo $text_module; ?></a></li>
                <li><a href="total"><?php echo $text_total; ?></a></li>
                <li><a href="feed"><?php echo $text_feed; ?></a></li>
                <li><a href="report"><?php echo $text_report; ?></a></li>
                <li><a href="other"><?php echo $text_other; ?></a></li>
              </ul>
              
              <button type="button" id="button-filter" class="btn btn-primary"><i class="fa fa-filter"></i></button>

            </div>
          </div>
        </div>
       </fieldset>
        <div id="store">
        
        
        
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-filter').bind('click', function(e) {
	var node = this;

	$.ajax({
		url: 'index.php?route=extension/store/store&token=<?php echo $token; ?>',
		dataType: 'html',
		beforeSend: function() {
			$(node).prop('disabled', true);
		},
		complete: function() {
			$(node).prop('disabled', false);
		},
		success: function(html) {
			$('#store').html(html);
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
