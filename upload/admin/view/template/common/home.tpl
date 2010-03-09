<?php echo $header; ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/home.png');"><?php echo $heading_title; ?></h1>
  </div>
  <div class="content">
    <div style="display: inline-block; width: 100%; margin-bottom: 15px; clear: both;">
      <div style="float: left; width: 49%;">
        <div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_overview; ?></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; height: 180px;">
          <table cellpadding="2" style="width: 100%;">
            <tr>
              <td width="80%"><?php echo $text_total_sale; ?></td>
              <td align="right"><?php echo $total_sale; ?></td>
            <tr>
              <td><?php echo $text_total_sale_year; ?></td>
              <td align="right"><?php echo $total_sale_year; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_total_order; ?></td>
              <td align="right"><?php echo $total_order; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_total_customer; ?></td>
              <td align="right"><?php echo $total_customer; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_total_customer_approval; ?></td>
              <td align="right"><?php echo $total_customer_approval; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_total_product; ?></td>
              <td align="right"><?php echo $total_product; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_total_review; ?></td>
              <td align="right"><?php echo $total_review; ?></td>
            </tr>
            <tr>
              <td><?php echo $text_total_review_approval; ?></td>
              <td align="right"><?php echo $total_review_approval; ?></td>
            </tr>
          </table>
        </div>
      </div>
      <div style="float: right; width: 49%;">
        <div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3;">
          <div style="width: 100%; display: inline-block;">
            <div style="float: left; font-size: 14px; font-weight: bold; padding: 7px 0px 0px 5px; line-height: 12px;"><?php echo $text_statistics; ?></div>
            <div style="float: right; font-size: 12px; padding: 2px 5px 0px 0px;"><?php echo $entry_range; ?>
              <select id="range" onchange="getSalesChart(this.value)" style="margin: 2px 3px 0 0;">
                <option value="day"><?php echo $text_day; ?></option>
                <option value="week"><?php echo $text_week; ?></option>
                <option value="month"><?php echo $text_month; ?></option>
                <option value="year"><?php echo $text_year; ?></option>
              </select>
            </div>
          </div>
        </div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px; height: 49%;"">
          <div id="report" style="width: 400px; height: 180px; margin: auto;"></div>
        </div>
      </div>
    </div>
    <div>
      <div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_latest_10_orders; ?></div>
      <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
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
            <?php foreach ($orders as $order) { ?>
            <tr>
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
            <tr>
              <td class="center" colspan="6"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<!--[if IE]>
<script type="text/javascript" src="view/javascript/jquery/flot/excanvas.js"></script>
<![endif]-->
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script>
<script type="text/javascript"><!--
function getSalesChart(range) {
	$.ajax({
		type: 'GET',
		url: 'index.php?route=common/home/chart&range=' + range,
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
<?php echo $footer; ?>