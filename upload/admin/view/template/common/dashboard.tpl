<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <h1><i class="fa fa-dashboard"></i> <?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_install) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_install; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="row">
      <div class="col-sm-3">
        <div class="tile tile-red">
          <div class="tile-body">
            <span class="pull-right"><i class="fa fa-shopping-cart fa-4x"></i></span>
            <?php if ($order_percentage > 0) { ?>
            <span class="label label-success pull-right">+<?php echo $order_percentage; ?>%</span>
            <?php } else { ?>
            <span class="label label-danger pull-right"><?php echo $order_percentage; ?>%</span>
            <?php } ?>
            <h3><?php echo $text_new_order; ?> <?php echo $order_total; ?></h3>
          </div>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="tile tile-yellow">
          <div class="tile-heading"><?php echo $text_total_sale; ?></div>
          <div class="tile-body">
          <i class="fa fa-credit-card fa-3x"></i> <span class="label <?php echo $class; ?> pull-right"><?php echo $sale_percentage; ?>%</span>
            <h3 class="pull-right"><?php echo $sale_total; ?></h3>
          </div>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="tile tile-blue">
          <div class="tile-heading"><?php echo $text_new_customer; ?> <?php echo $customer_total; ?></div>
          <div class="tile-body"><i class="fa fa-user fa-3x"></i>
            <?php if ($customer_percentage > 0) { ?>
            <span class="label label-success pull-right">+<?php echo $customer_percentage; ?>%</span>
            <?php } else { ?>
            <span class="label label-danger pull-right"><?php echo $customer_percentage; ?>%</span>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="col-sm-3">
        <div class="tile tile-green">
          <div class="tile-heading"><?php echo $text_online; ?></div>
          <div class="tile-body"><i class="fa fa-eye fa-3x"></i>
            <h3><?php echo $online_total; ?></h3>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <div class="pull-right">
              <div class="btn-group" data-toggle="buttons">
                <button type="button" class="btn dropdown-toggle" data-toggle="dropdown"> <i class="fa fa-calendar"></i></button>
                <ul id="range" class="dropdown-menu dropdown-menu-right">
                  <li><a href="day"><?php echo $text_day; ?></a></li>
                  <li><a href="week"><?php echo $text_week; ?></a></li>
                  <li class="active"><a href="month"><?php echo $text_month; ?></a></li>
                  <li><a href="year"><?php echo $text_year; ?></a></li>
                </ul>
              </div>
            </div>
            <h1 class="panel-title"><i class="fa fa-bar-chart-o fa-lg"></i> <?php echo $text_analytics; ?></h1>
          </div>
          <div class="panel-body">
            <div id="chart-sale" class="chart" style="width: 100%; height: 175px;"></div>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h1 class="panel-title"><i class="fa fa-eye fa-lg"></i> <?php echo $text_online; ?></h1>
          </div>
          <div class="panel-body">
            <div id="chart-online" class="chart" style="width: 100%; height: 175px;"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h1 class="panel-title"><i class="fa fa-calendar-o fa-lg"></i> <?php echo $text_activity; ?></h1>
          </div>
          <ul class="list-group">
            <?php if ($activities) { ?>
            <?php foreach ($activities as $activity) { ?>
            <li class="list-group-item"><?php echo $activity['comment']; ?><br />
              <small class="text-muted"><i class="fa fa-clock-o"></i> <?php echo $activity['date_added']; ?></small></li>
            <?php } ?>
            <?php } else { ?>
            <li class="list-group-item text-center"><?php echo $text_no_results; ?></li>
            <?php } ?>
          </ul>
        </div>
      </div>
      <div class="col-sm-8">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h1 class="panel-title"><i class="fa fa-shopping-cart fa-lg"></i> <?php echo $text_last_order; ?></h1>
          </div>
          <div class="table-responsive">
            <table class="table">
              <thead>
                <tr>
                  <td class="text-right"><?php echo $column_order_id; ?></td>
                  <td><?php echo $column_customer; ?></td>
                  <td><?php echo $column_status; ?></td>
                  <td><?php echo $column_date_added; ?></td>
                  <td class="text-right"><?php echo $column_total; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($orders) { ?>
                <?php foreach ($orders as $order) { ?>
                <tr>
                  <td class="text-right"><?php echo $order['order_id']; ?></td>
                  <td><?php echo $order['customer']; ?></td>
                  <td><?php echo $order['status']; ?></td>
                  <td><?php echo $order['date_added']; ?></td>
                  <td class="text-right"><?php echo $order['total']; ?></td>
                  <td class="text-right"><a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.resize.min.js"></script> 
<script type="text/javascript"><!--
$('#range a').on('click', function(e) {
	e.preventDefault();
	
	$(this).parent().parent().find('li').removeClass('active');
	
	$(this).parent().addClass('active');
	
	// Sales
	$.ajax({
		type: 'get',
		url: 'index.php?route=common/dashboard/sale&token=<?php echo $token; ?>&range=' + $(this).attr('href'),
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
		},
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	});
});

$('#range .active a').trigger('click');

function online() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=common/dashboard/online&token=<?php echo $token; ?>',
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
		},
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	});
	
	setTimeout(online, 5000);
}

online();
//--></script> 
<?php echo $footer; ?>