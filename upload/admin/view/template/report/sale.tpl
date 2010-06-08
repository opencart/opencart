<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/report.png');"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
    <div style="background: #E7EFEF; border: 1px solid #C6D7D7; padding: 3px; margin-bottom: 15px;">
      <table width="100%" cellspacing="0" cellpadding="6">
        <tr>
          <td><?php echo $entry_date_start; ?><br />
            <input type="text" name="filter_date_start" value="<?php echo $filter_date_start; ?>" id="date_start" size="12" style="margin-top: 4px;" /></td>
          <td><?php echo $entry_date_end; ?><br />
            <input type="text" name="filter_date_end" value="<?php echo $filter_date_end; ?>" id="date_end" size="12" style="margin-top: 4px;" /></td>
          <td><?php echo $entry_group; ?><br />
            <select name="filter_group" style="margin-top: 4px;">
              <?php foreach ($groups as $groups) { ?>
              <?php if ($groups['value'] == $filter_group) { ?>
              <option value="<?php echo $groups['value']; ?>" selected="selected"><?php echo $groups['text']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $groups['value']; ?>"><?php echo $groups['text']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <td><?php echo $entry_status; ?><br />
            <select name="filter_order_status_id" style="margin-top: 4px;">
              <option value="0"><?php echo $text_all_status; ?></option>
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <td align="right"><a onclick="filter();" class="button"><span><?php echo $button_filter; ?></span></a></td>
        </tr>
      </table>
    </div>
    <table class="list">
      <thead>
        <tr>
          <td class="left"><?php echo $column_date_start; ?></td>
          <td class="left"><?php echo $column_date_end; ?></td>
          <td class="right"><?php echo $column_orders; ?></td>
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
          <td class="right"><?php echo $order['total']; ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <div class="pagination"><?php echo $pagination; ?></div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=report/sale&token=<?php echo $token; ?>';
	
	var filter_date_start = $('input[name=\'filter_date_start\']').attr('value');
	
	if (filter_date_start) {
		url += '&filter_date_start=' + encodeURIComponent(filter_date_start);
	}

	var filter_date_end = $('input[name=\'filter_date_end\']').attr('value');
	
	if (filter_date_end) {
		url += '&filter_date_end=' + encodeURIComponent(filter_date_end);
	}
		
	var filter_group = $('select[name=\'filter_group\']').attr('value');
	
	if (filter_group) {
		url += '&filter_group=' + encodeURIComponent(filter_group);
	}
	
	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');
	
	if (filter_order_status_id) {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}	

	location = url;
}
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('#date_start').datepicker({dateFormat: 'yy-mm-dd'});
	
	$('#date_end').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>