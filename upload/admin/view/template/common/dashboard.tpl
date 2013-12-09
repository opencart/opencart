<?php echo $header; ?>
<div id="content" class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_install) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_install; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } else { ?>
  <?php } ?>
  <div class="alert alert-info"><i class="fa fa-thumbs-o-up"></i> <?php echo $text_welcome; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <div class="row">
    <div class="col-sm-3">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-4"><span class="text-muted"><i class="fa fa-shopping-cart fa-4x"></i></span></div>
            <div class="col-xs-8"> <span class="lead text-success"><?php echo $order_total; ?></span>
              <div class="label label-success">+23%</div>
              <br />
              <?php echo $text_order; ?></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-4"><span class="text-muted"><i class="fa fa-user fa-4x"></i></span></div>
            <div class="col-xs-8"><?php echo $customer_total; ?><br />
              <?php echo $text_customer; ?></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-4"><span class="text-muted"><i class="fa fa-credit-card fa-4x"></i></span></div>
            <div class="col-xs-8"><?php echo $text_sale; ?><br />
              <?php echo $sale_total; ?></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-sm-3">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="row">
            <div class="col-xs-4"><span class="text-muted"><i class="fa fa-bar-chart-o fa-4x"></i></span></div>
            <div class="col-xs-8"><?php echo $marketing_total; ?><br />
              <?php echo $text_marketing; ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-12">
      <div class="panel panel-default">
        <div class="panel-heading">
          <div class="pull-right">
            <div class="btn-group" data-toggle="buttons">
              <label class="btn btn-default active">
                <input type="radio" name="range" value="day" />
                <?php echo $text_day; ?></label>
              <label class="btn btn-default">
                <input type="radio" name="range" value="week" />
                <?php echo $text_week; ?></label>
              <label class="btn btn-default">
                <input type="radio" name="range" value="month" />
                <?php echo $text_month; ?></label>
              <label class="btn btn-default">
                <input type="radio" name="srange" value="year" />
                <?php echo $text_year; ?></label>
            </div>
          </div>
          <h1 class="panel-title"><i class="fa fa-bar-chart-o"></i> <?php echo $text_analytics; ?></h1>
        </div>
        <div class="panel-body">
          <div class="row">
            <div class="col-sm-5">
              <p class="text-primary">Sales Analytics</p>
              <div id="chart-sale" class="chart" style="width: 100%; height: 175px;"></div>
            </div>
            <div class="col-sm-7">
              <p class="text-primary">Marketing Analytics</p><strong></strong>
              <div id="chart-marketing" style="width: 100%; height: 100px;"></div>
              
              <div id="chart-online" class="chart" style="width: 100%; height: 100px;"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-sm-4">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h1 class="panel-title"><i class="fa fa-calendar-o"></i> <?php echo $text_activity; ?></h1>
        </div>
        <ul class="list-group">
          <?php if ($activities) { ?>
          <?php foreach ($activities as $activity) { ?>
          <li class="list-group-item"> <?php echo $activity['comment']; ?><br />
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
          <h1 class="panel-title"><i class="fa fa-shopping-cart"></i> <?php echo $heading_title; ?></h1>
        </div>
        <div class="panel-body">
          <table class="table table-hover">
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
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.resize.min.js"></script> 
<script type="text/javascript"><!--
$('input[name=\'range\']').on('change', function() {
	// Sales
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

	// Marketing
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

$('.active input[name=\'range\']').trigger('change');

$('#button-refresh').on('click', function() {
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
		}
	});
});
//--></script> 
<?php echo $footer; ?>