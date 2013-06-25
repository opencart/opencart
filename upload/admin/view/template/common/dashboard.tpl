<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_install) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_install; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-eye-open"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <div class="row-fluid">
        <div class="span3">
          <div class="stats">
            <div><i class="icon-usd"></i></div>
            <div>
              <h5><?php echo $text_sale; ?></h5>
              <?php echo $total_sale; ?><br />
              +10%</div>
          </div>
        </div>
        <div class="span3">
          <div class="stats">
            <div><i class="icon-shopping-cart"></i></div>
            <div>
              <h5><?php echo $text_order; ?></h5>
              <?php echo $total_order; ?><br />
              +10% </div>
          </div>
        </div>
        <div class="span3">
          <div class="stats">
            <div><i class="icon-user"></i></div>
            <div>
              <h5><?php echo $text_customer; ?></h5>
              <?php echo $total_customer; ?><br />
              +10%</div>
          </div>
        </div>
        <div class="span3">
          <div class="stats">
            <div><i class="icon-globe"></i></div>
            <div>
              <h5><?php echo $text_marketing; ?></h5>
              <?php echo $total_marketing; ?><br />
              +10% </div>
          </div>
        </div>
      </div>
      <div class="row-fluid">
        <div class="offset3 span3">
          <div class="stats">
            <div><i class="icon-group"></i></div>
            <div>
              <h5><?php echo $text_online; ?></h5>
              <?php echo $total_online; ?><br />
              +10%</div>
          </div>
        </div>
        <div class="span3">
          <div class="stats">
            <div><i class="icon-shopping-cart"></i></div>
            <div>
              <h5><?php echo $text_order; ?></h5>
              $100,0000<br />
              <span class="badge badge-success">+10%</span></div>
          </div>
        </div>
      </div>
      <div class="row-fluid">
        <div class="span6">
          <div id="button-sale" class="btn-group pull-right" data-toggle="buttons-radio">
            <button class="btn btn-small active" value="day"><?php echo $text_day; ?></button>
            <button class="btn btn-small" value="week"><?php echo $text_week; ?></button>
            <button class="btn btn-small" value="month"><?php echo $text_month; ?></button>
            <button class="btn btn-small" value="year"><?php echo $text_year; ?></button>
          </div>
        </div>
        <div class="span6">
          <div id="button-marketing" class="btn-group pull-right" data-toggle="buttons-radio">
            <button class="btn btn-small active" value="day"><?php echo $text_day; ?></button>
            <button class="btn btn-small" value="week"><?php echo $text_week; ?></button>
            <button class="btn btn-small" value="month"><?php echo $text_month; ?></button>
            <button class="btn btn-small" value="year"><?php echo $text_year; ?></button>
          </div>
        </div>
      </div>
      <div class="row-fluid">
        <div class="span6">
          <div id="chart-sale" style="height: 200px;"></div>
        </div>
        <div class="span6">
          <div id="chart-marketing" style="height: 200px;"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.resize.min.js"></script> 
<script type="text/javascript"><!--
$('#button-sale button').on('click', function() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=common/dashboard/sale&token=<?php echo $token; ?>&range=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#button-sale button').prop('disabled', true);
		},
		complete: function() {
			$('#button-sale button').prop('disabled', false);
		},		
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
            		ticks: json.xaxis
				}
			}
			
			$.plot('#chart-sale', [json.order, json.customer], option);	
					
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

$('#button-sale .active').trigger('click');

$('#button-marketing button').on('click', function() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=common/dashboard/marketing&token=<?php echo $token; ?>',
		dataType: 'json',
		beforeSend: function() {
			$('#button-marketing button').prop('disabled', true);
		},
		complete: function() {
			$('#button-marketing button').prop('disabled', false);
		},		
		success: function(json) {
			var option = {	
				shadowSize: 0,
				bars: { 
					show: true,
					fill: true,
					lineWidth: 1
					
				},
				grid: {
					backgroundColor: '#FFFFFF',
					hoverable: true
				},
				points: {
					show: false		
				}
			}		
			
			$.plot('#chart-marketing', [json.click, json.sale], option);
					
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

$('#button-marketing .active').trigger('click');
//--></script> 
<?php echo $footer; ?>