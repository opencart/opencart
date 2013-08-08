<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="icon-ok-sign"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-list"></i> <?php echo $heading_title; ?></h1>
      <div class="buttons"> <a href="<?php echo $insert; ?>" class="btn"><i class="icon-plus"></i> <?php echo $button_insert; ?></a>
        <button type="submit" form="form-marketing" class="btn"><i class="icon-trash"></i> <?php echo $button_delete; ?></button>
      </div>
    </div>
    <form action="" method="post" enctype="multipart/form-data" id="form-marketing">
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <td width="1" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
            <td class="text-left"><?php if ($sort == 'm.name') { ?>
              <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
              <?php } ?></td>
            <td class="text-left"><?php if ($sort == 'm.code') { ?>
              <a href="<?php echo $sort_code; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_code; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_code; ?>"><?php echo $column_code; ?></a>
              <?php } ?></td>
            <td class="text-right"><?php echo $column_clicks; ?></td>
            <td class="text-right"><?php echo $column_orders; ?></td>
            <td class="text-left"><?php if ($sort == 'm.date_added') { ?>
              <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
              <?php } else { ?>
              <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
              <?php } ?></td>
            <td class="text-right"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr class="filter">
            <td></td>
            <td><input type="text" name="filter_name" value="<?php echo $filter_name; ?>" class="input-medium" /></td>
            <td><input type="text" name="filter_code" value="<?php echo $filter_code; ?>" class="input-medium" /></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><input type="date" name="filter_date_added" value="<?php echo $filter_date_added; ?>" class="input-medium" /></td>
            <td align="right"><button type="button" id="button-filter" class="btn btn-default pull-right"><i class="icon-search"></i> <?php echo $button_filter; ?></button></td>
          </tr>
          <?php if ($marketings) { ?>
          <?php foreach ($marketings as $marketing) { ?>
          <tr>
            <td class="text-center"><?php if ($marketing['selected']) { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $marketing['marketing_id']; ?>" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="selected[]" value="<?php echo $marketing['marketing_id']; ?>" />
              <?php } ?></td>
            <td class="text-left"><?php echo $marketing['name']; ?></td>
            <td class="text-left"><?php echo $marketing['code']; ?></td>
            <td class="text-right"><?php echo $marketing['clicks']; ?></td>
            <td class="text-right"><?php echo $marketing['orders']; ?></td>
            <td class="text-left"><?php echo $marketing['date_added']; ?></td>
            <td class="text-right"><?php foreach ($marketing['action'] as $action) { ?>
              [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
              <?php } ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </form>    <div class="row">
      <div class="col-lg-6 text-left"><?php echo $pagination; ?></div>
      <div class="col-lg-6 text-right"><?php echo $results; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=marketing/marketing&token=<?php echo $token; ?>';
	
	var filter_name = $('input[name=\'filter_name\']').val();
	
	if (filter_name) {
		url += '&filter_name=' + encodeURIComponent(filter_name);
	}
	
	var filter_code = $('input[name=\'filter_code\']').val();
	
	if (filter_code) {
		url += '&filter_code=' + encodeURIComponent(filter_code);
	}
		
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
	
	location = url;
});
//--></script> 
<?php echo $footer; ?>