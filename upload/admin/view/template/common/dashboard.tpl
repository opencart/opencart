<?php echo $header; ?>
<div class="container">
  <?php if ($error_install) { ?>
  <div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <?php echo $error_install; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-eye-open icon-large"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-lg-3">
          <h2><i class="icon-money"></i> <?php echo $text_sale; ?></h2>
          <?php echo $sale_growth; ?> <?php echo $sale_total; ?> </div>
        <div class="col-lg-3">
          <h2><i class="icon-shopping-cart"></i> <?php echo $text_order; ?></h2>
          <?php echo $order_total; ?><br />
          <?php echo $order_growth; ?>%</span> </div>
        <div class="col-lg-3">
          <h2><i class="icon-user"></i> <?php echo $text_customer; ?></h2>
          <?php echo $customer_total; ?><br />
          <?php echo $customer_growth; ?>% </div>
        <div class="col-lg-3">
          <h2><i class="icon-globe"></i> <?php echo $text_marketing; ?></h2>
          Clicks: <?php echo $marketing_total; ?> / Orders: 1<br />
          +10% </div>
      </div>
      <br />
      <br />
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-sale" data-toggle="tab"><?php echo $tab_sale; ?></a></li>
        <li><a href="#tab-marketing" data-toggle="tab"><?php echo $tab_marketing; ?></a></li>
        <li><a href="#tab-online" data-toggle="tab"><?php echo $tab_online; ?></a></li>
        <li><a href="#tab-activity" data-toggle="tab"><?php echo $tab_activity; ?></a></li>
        <li></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab-sale">
          <div class="text-right">
            <div class="btn-group" data-toggle="buttons">
              <label class="btn btn-default active">
                <input type="radio" name="sale" value="day" />
                <?php echo $text_day; ?></label>
              <label class="btn btn-default">
                <input type="radio" name="sale" value="week" />
                <?php echo $text_week; ?></label>
              <label class="btn btn-default">
                <input type="radio" name="sale" value="month" />
                <?php echo $text_month; ?></label>
              <label class="btn btn-default">
                <input type="radio" name="sale" value="year" />
                <?php echo $text_year; ?></label>
            </div>
          </div>
          <div id="chart-sale" class="chart" style="width: 100%; height: 250px;"></div>
        </div>
        <div class="tab-pane" id="tab-marketing">
          <div class="text-right">
            <div class="btn-group" data-toggle="buttons">
              <label class="btn btn-default active">
                <input type="radio" name="marketing" value="day" />
                <?php echo $text_day; ?></label>
              <label class="btn btn-default">
                <input type="radio" name="marketing" value="week" />
                <?php echo $text_week; ?></label>
              <label class="btn btn-default">
                <input type="radio" name="marketing" value="month" />
                <?php echo $text_month; ?></label>
              <label class="btn btn-default">
                <input type="radio" name="marketing" value="year" />
                <?php echo $text_year; ?></label>
            </div>
          </div>
          <div id="chart-marketing" style="width: 100%; height: 250px;"></div>
        </div>
        <div class="tab-pane" id="tab-online">
          <div id="chart-online" class="chart" style="width: 100%; height: 250px;"></div>
        </div>
        <div class="tab-pane" id="tab-activity">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-left"><?php echo $column_action; ?></td>
                <td class="text-left"><?php echo $column_date_added; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($activities) { ?>
              <?php foreach ($activities as $activity) { ?>
              <tr>
                <td class="text-left"><?php echo $activity['action']; ?></td>
                <td class="text-left"><?php echo $activity['action']; ?></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td colspan="2" class="text-center"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.resize.min.js"></script> 
<script type="text/javascript"><!--
$('input[name=\'sale\']').on('change', function() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=common/dashboard/sale&token=<?php echo $token; ?>&range=' + this.value,
		dataType: 'json',
		success: function(json) {
			var option = {	
				shadowSize: 0,
				bars: { 
					show: true,
					fill: true,
					lineWidth: 1,
					barColor: '#000000'
				},
				grid: {
					backgroundColor: '#FFFFFF',
					hoverable: true
				},
				points: {
					show: false				
				},
				xaxis: {
					show: true,
            		ticks: json['xaxis']
				}
			}
			
			$.plot('#chart-sale', [json['order'], json['customer']], option);	
					
			$('#chart-sale').bind('plothover', function(event, pos, item) {
				$('.tooltip').remove();
			  
				if (item) {
					$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
					
					$('#tooltip').css({
						position: 'absolute',
						left: item.pageX - ($('#tooltip').outerWidth() / 2),
						top: item.pageY - $('#tooltip').outerHeight(),
						pointer: 'cusror'
					}).fadeIn('slow');	
					
					$('#chart-sale').css('cursor', 'pointer');		
			  	} else {
					$('#chart-sale').css('cursor', 'auto');
				}
			});
		}
	});
});

$('.active input[name=\'sale\']').trigger('change');

$('input[name=\'marketing\']').on('change', function() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=common/dashboard/marketing&token=<?php echo $token; ?>&range=' + this.value,
		dataType: 'json',
		success: function(json) {
			var option = {	
				shadowSize: 0,
				bars: { 
					show: true,
					fill: true,
					lineWidth: 1,
					barColor: '#000000'
				},
				grid: {
					backgroundColor: '#FFFFFF',
					hoverable: true
				},
				points: {
					show: false		
				},
				xaxis: {
            		ticks: json['xaxis']
				}
			}		
			
			$.plot('#chart-marketing', [json['click'], json['order']], option);
					
			$('#chart-marketing').bind('plothover', function(event, pos, item) {
				$('.tooltip').remove();
			  
				if (item) {
					$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
					
					$('#tooltip').css({
						position: 'absolute',
						left: item.pageX - ($('#tooltip').outerWidth() / 2),
						top: item.pageY - $('#tooltip').outerHeight(),
						pointer: 'cusror'
					}).fadeIn('slow');	
					
					$('#chart-marketing').css('cursor', 'pointer');		
				} else {
					$('#chart-marketing').css('cursor', 'auto');
				}
			});
		}
	});
});

$('.active input[name=\'marketing\']').trigger('change');

function timer() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=common/dashboard/online&token=<?php echo $token; ?>&range=' + this.value,
		dataType: 'json',		
		success: function(json) {
			var option = {	
				shadowSize: 0,
				colors: ['#B94A48'],
				lines: { 
					show: true,
					fill: true,
					lineWidth: 1,
					barColor: '#000000'
				},
				grid: {
					backgroundColor: '#FFFFFF',
					hoverable: true
				},
				points: {
					show: false		
				},	
				xaxis: {
					ticks: json['xaxis']
				},
			}		
			
			$.plot('#chart-online', [json['online']], option);
					
			$('#chart-online').bind('plothover', function(event, pos, item) {
				$('.tooltip').remove();
			  
				if (item) {
					$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
					
					$('#tooltip').css({
						position: 'absolute',
						left: item.pageX - ($('#tooltip').outerWidth() / 2),
						top: item.pageY - $('#tooltip').outerHeight(),
						pointer: 'cusror'
					}).fadeIn('slow');	
					
					$('#chart-online').css('cursor', 'pointer');		
				} else {
					$('#chart-online').css('cursor', 'auto');
				}
			});
		}
	});
	
	setTimeout(timer, 2000);
}

timer();
//--></script> 
<?php echo $footer; ?>