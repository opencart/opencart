<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_install) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_install; ?></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-eye-open"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <div class="row-fluid">
        <div class="span3">
          <div class="well  stats">
            <ul class="statistic statistic-red">
              <li style="height:80%"></li>
              <li style="height:40%"></li>
              <li style="height:50%"></li>
              <li style="height:20%"></li>
              <li style="height:10%"></li>
            </ul>
            <div class="detail">
              <h5><?php echo $total_sale; ?></h5>
              <?php echo $text_total_sale; ?></div>
          </div>
        </div>
        <div class="span3">
          <div class="well">
            <ul class="statistic statistic-grey">
              <li style="height:60%"></li>
              <li style="height:40%"></li>
              <li style="height:20%"></li>
              <li style="height:70%"></li>
              <li style="height:80%"></li>
              <li style="height:60%"></li>
              <li style="height:40%"></li>
            </ul>
            <div class="detail">
              <h5><?php echo $total_order; ?></h5>
              <?php echo $text_total_order; ?></div>
          </div>
        </div>
        <div class="span3">
          <div class="well stats clearfix">
            <ul class="statistic statistic-blue">
              <li style="height:80%"></li>
              <li style="height:60%"></li>
              <li style="height:40%"></li>
              <li style="height:20%"></li>
              <li style="height:10%"></li>
              <li style="height:5%"></li>
              <li style="height:15%"></li>
            </ul>
            <div class="detail">
              <h5><?php echo $total_customer; ?></h5>
              <?php echo $text_total_customer; ?></div>
          </div>
        </div>
        <div class="span3">
          <div class="well stats">
            <ul class="statistic statistic-blue">
              <li style="height:80%"></li>
              <li style="height:60%"></li>
              <li style="height:40%"></li>
              <li style="height:20%"></li>
              <li style="height:10%"></li>
              <li style="height:5%"></li>
              <li style="height:15%"></li>
            </ul>
            <div class="detail">
              <h5><?php echo $total_online; ?></h5>
              <?php echo $text_total_online; ?></div>
          </div>
        </div>
      </div>
      <div class="row-fluid">
        <div class="span6">
          <h2>Statistics</h2>
        </div>
        <div class="span6">
          <div class="btn-group" data-toggle="buttons-radio">
            <button class="btn active" name="range" value="day"><?php echo $text_day; ?></button>
            <button class="btn" name="range" value="week"><?php echo $text_week; ?></button>
            <button class="btn" name="range" value="month"><?php echo $text_month; ?></button>
            <button class="btn" name="range" value="year"><?php echo $text_year; ?></button>
          </div>
        </div>
      </div>
      <div class="row-fluid">
        <div class="span12">
          <div id="report" style="height: 250px; margin-bottom: 20px;"></div>
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
		url: 'index.php?route=common/home/chart&token=<?php echo $token; ?>&range=' + this.value,
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
				colors: [ '#2872bd', '#666666', '#feb900', '#128902', '#c6c12f'],
				xaxis: {
            		ticks: json.xaxis
				}
			}
			
			var placeholder = $('#report');
			
			$.plot(placeholder, [json.order, json.customer], option);		   
		}
	});	
})

$('.btn-group .active').trigger('click');
//--></script> 
<?php echo $footer; ?>