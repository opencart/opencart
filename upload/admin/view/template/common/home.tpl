<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="tabs"><a tab="#tab_sale"><?php echo $tab_stats; ?></a><a tab="#tab_order"><?php echo $tab_order; ?></a><a tab="#tab_customer"><?php echo $tab_customer; ?></a></div>
<div id="tab_sale" class="page">
  <div style="text-align: right; padding: 8px; background: #E7EFEF; margin-bottom: 15px;"><?php echo $entry_range; ?>
    <select id="range" onchange="getSalesChart(this.value)" style="margin: 0; padding: 0;">
      <option value="day"><?php echo $text_day; ?></option>
      <option value="week"><?php echo $text_week; ?></option>
      <option value="month"><?php echo $text_month; ?></option>
      <option value="year"><?php echo $text_year; ?></option>
    </select>
  </div>
  <div id="report" style="width: 100%; height: 350px;"></div>
</div>
<div id="tab_order" class="page">
  <table class="list">
    <thead>
      <tr>
        <td class="right"><?php echo $column_order; ?></td>
        <td class="left"><?php echo $column_name; ?></td>
        <td class="left"><?php echo $column_status; ?></td>
        <td class="left"><?php echo $column_date_added; ?></td>
        <td class="right"><?php echo $column_total; ?></td>
        <td class="right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($orders) { ?>
      <?php $class = 'odd'; ?>
      <?php foreach ($orders as $order) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <tr class="<?php echo $class; ?>">
        <td class="right"><?php echo $order['order_id']; ?></td>
        <td class="left"><?php echo $order['name']; ?></td>
        <td class="left"><?php echo $order['status']; ?></td>
        <td class="left"><?php echo $order['date_added']; ?></td>
        <td class="right"><?php echo $order['total']; ?></td>
        <td class="right"><?php foreach ($order['action'] as $action) { ?>
          [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
          <?php } ?></td>
      </tr>
      <?php } ?>
      <?php } else { ?> 
      <tr class="even">
        <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div id="tab_customer" class="page">
  <table class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $column_lastname; ?></td>
        <td class="left"><?php echo $column_firstname; ?></td>
        <td class="left"><?php echo $column_status; ?></td>
        <td class="left"><?php echo $column_date_added; ?></td>
        <td class="right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($customers) { ?>
      <?php $class = 'odd'; ?>
      <?php foreach ($customers as $customer) { ?>
      <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
      <tr class="<?php echo $class; ?>">
        <td class="left"><?php echo $customer['lastname']; ?></td>
        <td class="left"><?php echo $customer['firstname']; ?></td>
        <td class="left"><?php echo $customer['status']; ?></td>
        <td class="left"><?php echo $customer['date_added']; ?></td>
        <td class="right"><?php foreach ($customer['action'] as $action) { ?>
          [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
          <?php } ?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr class="even">
        <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<!--[if IE]><script type="text/javascript" src="view/javascript/jquery/flot/excanvas.js"></script><![endif]-->
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script>
<script type="text/javascript"><!--
function getSalesChart(range) {
	$.ajax({
		type: 'GET',
		url: 'index.php?route=common/home/report&range=' + range,
		dataType: 'json',
		async: false,
		success: function(json) {
			var option = {	
				shadowSize: 0,
				lines: { 
					show: true,
					fill: true,
					lineWidth: 1
				},
				grid: {
					backgroundColor: '#FFFFFF'
				},	
				xaxis: {
            		ticks: json.xaxis
				}
			}

			$.plot($('#report'), [json.order, json.customer], option);
		}
	});
}

getSalesChart($('#range').val());
//--></script>
<script type="text/javascript"><!--
$.tabs('.tabs a'); 
//--></script>