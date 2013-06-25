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
      <div class="row-fluid" style="margin-bottom: 20px;">
        <div class="span3">
          <div class="stats">
            <div><i class="icon-usd"></i></div>
            <div>
              <h5>TOTAL SALES</h5>
              $100,0000<br />
              +10%</div>
          </div>
        </div>
        <div class="span3">
          <div class="stats">
            <div><i class="icon-shopping-cart"></i></div>
            <div>
              <h5><?php echo $text_order; ?></h5>
              $100,0000<br />
              +10% </div>
          </div>
        </div>
        <div class="span3">
          <div class="stats">
            <div><i class="icon-user"></i></div>
            <div>
              <h5><?php echo $text_customer; ?></h5>
              5200<br />
              +10%</div>
          </div>
        </div>
        <div class="span3">
          <div class="stats">
            <div><i class="icon-globe"></i></div>
            <div>
              <h5>Marketing</h5>
              $100,0000<br />
              +10% </div>
          </div>
        </div>
      </div>
      <div class="row-fluid">
        <div class="span6"><h5>Marketing</h5>
          <div class="btn-group pull-right" data-toggle="buttons-radio">
            <button class="btn btn-small active" value="day"><?php echo $text_day; ?></button>
            <button class="btn btn-small" value="week"><?php echo $text_week; ?></button>
            <button class="btn btn-small" value="month"><?php echo $text_month; ?></button>
            <button class="btn btn-small" value="year"><?php echo $text_year; ?></button>
          </div>
        </div>
        <div class="span6"><h5>Marketing</h5>
          <div class="btn-group pull-right" data-toggle="buttons-radio">
            <button class="btn btn-small active" value="day"><?php echo $text_day; ?></button>
            <button class="btn btn-small" value="week"><?php echo $text_week; ?></button>
            <button class="btn btn-small" value="month"><?php echo $text_month; ?></button>
            <button class="btn btn-small" value="year"><?php echo $text_year; ?></button>
          </div>
        </div>        
      </div>
      <div class="row-fluid">
        <div class="span6">
        
          <div id="statistics" style="height: 200px; margin-bottom: 20px;"></div>
        </div>
        <div class="span6">
          <div id="online" style="height: 150px; margin-bottom: 20px;"></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/flot/jquery.flot.resize.min.js"></script> 
<script type="text/javascript"><!--
$('.btn-group button').on('click', function() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=common/dashboard/statistics&token=<?php echo $token; ?>&range=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('.btn-group button').prop('disabled', true);
		},
		complete: function() {
			$('.btn-group button').prop('disabled', false);
		},		
		success: function(json) {
			var option = {	
				shadowSize: 0,
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
					show: true				
				},
				xaxis: {
            		ticks: json.xaxis
				}
			}
			
			$.plot('#statistics', [json.order, json.customer], option);	
					
			$('#statistics').bind('plothover', function(event, pos, item) {
				$('.tooltip').remove();
			  
				if (item) {
					$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
					
					$('#tooltip').css({
						position: 'absolute',
						left: item.pageX - ($('#tooltip').outerWidth() / 2),
						top: item.pageY - $('#tooltip').outerHeight(),
						pointer: 'cusror'
					}).fadeIn('slow');	
					
					$('#statistics').css('cursor', 'pointer');		
			  	} else {
					$('#statistics').css('cursor', 'auto');
				}
			});
		}
	});	
})

$('.btn-group .active').trigger('click');


	
function online() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=common/dashboard/online&token=<?php echo $token; ?>',
		dataType: 'json',
		success: function(json) {
			var option = {	
				shadowSize: 0,
				colors: ['#FF0000'],
				lines: { 
					show: true,
					fill: true,
					lineWidth: 1
					
				},
				grid: {
					backgroundColor: '#FFFFFF',
					hoverable: true
				},
				points: {
					show: true				
				}
			}		
			
			$.plot('#online', [json.online], option);
					
			$('#online').bind('plothover', function(event, pos, item) {
				$('.tooltip').remove();
			  
				if (item) {
					$('<div id="tooltip" class="tooltip top in"><div class="tooltip-arrow"></div><div class="tooltip-inner">' + item.datapoint[1].toFixed(2) + '</div></div>').prependTo('body');
					
					$('#tooltip').css({
						position: 'absolute',
						left: item.pageX - ($('#tooltip').outerWidth() / 2),
						top: item.pageY - $('#tooltip').outerHeight(),
						pointer: 'cusror'
					}).fadeIn('slow');	
					
					$('#online').css('cursor', 'pointer');		
				} else {
					$('#online').css('cursor', 'auto');
				}
			});
			
			setTimeout(online, 1000);
		}
	});
}

online();
//--></script> 
<?php echo $footer; ?>