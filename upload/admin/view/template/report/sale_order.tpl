<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="panel">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-bar-chart icon-large"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <div class="well">
        <div class="row-fluid">
          <div class="span4">
            <div class="control-group">
              <label class="control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
              <div class="controls">
                <input type="date" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="input-date-start" class="input-medium" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
              <div class="controls">
                <input type="date" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="input-date-end" class="input-medium" />
              </div>
            </div>
          </div>
          <div class="span4">
            <div class="control-group">
              <label class="control-label" for="input-group"><?php echo $entry_group; ?></label>
              <div class="controls">
                <select name="filter_group" id="input-group">
                  <?php foreach ($groups as $group) { ?>
                  <?php if ($group['value'] == $filter_group) { ?>
                  <option value="<?php echo $group['value']; ?>" selected="selected"><?php echo $group['text']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $group['value']; ?>"><?php echo $group['text']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
              <div class="controls">
                <select name="filter_order_status_id" id="input-status">
                  <option value="0"><?php echo $text_all_status; ?></option>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="span4">
            <button type="button" id="button-filter" class="btn"><i class="icon-search"></i> <?php echo $button_filter; ?></button>
          </div>
        </div>
      </div>
      <table class="table table-striped table-bordered table-hover">
        <thead>
          <tr>
            <td class="left"><?php echo $column_date_start; ?></td>
            <td class="left"><?php echo $column_date_end; ?></td>
            <td class="right"><?php echo $column_orders; ?></td>
            <td class="right"><?php echo $column_products; ?></td>
            <td class="right"><?php echo $column_tax; ?></td>
            <td class="right"><?php echo $column_total; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($orders) { ?>
          <?php foreach ($orders as $order) { ?>
          <tr>
            <td class="left"><?php echo $order['date_start']; ?></td>
            <td class="left"><?php echo $order['date_end']; ?></td>
            <td class="right"><?php echo $order['orders']; ?></td>
            <td class="right"><?php echo $order['products']; ?></td>
            <td class="right"><?php echo $order['tax']; ?></td>
            <td class="right"><?php echo $order['total']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <div class="row-fluid">
        <div class="span6"><?php echo $pagination; ?></div>
        <div class="span6">
          <div class="results"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=report/sale_order&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').val();
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').val();
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_group = $('select[name=\'filter_group\']').val();
	
	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').val();
	
	if (filter_order_status_id != 0) {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	location = url;
});
//--></script> 
<?php echo $footer; ?>