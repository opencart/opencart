<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-globe"></i> <?php echo $heading_title; ?></h3>
  </div>
  <div class="panel-body">
    <div id="vmap" style="width: 100%; height: 260px;"></div>
  </div>
</div>
<link type="text/css" href="view/javascript/jquery/jqvmap/jqvmap.css" rel="stylesheet" media="screen" />
<script type="text/javascript" src="view/javascript/jquery/jqvmap/jquery.vmap.js"></script> 
<script type="text/javascript" src="view/javascript/jquery/jqvmap/maps/jquery.vmap.world.js"></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$.ajax({
		url: 'index.php?route=extension/dashboard/map/map&token=<?php echo $token; ?>',
		dataType: 'json',
		success: function(json) {
			data = [];
						
			for (i in json) {
				data[i] = json[i]['total'];
			}
					
			$('#vmap').vectorMap({
				map: 'world_en',
				backgroundColor: '#FFFFFF',
				borderColor: '#FFFFFF',
				color: '#9FD5F1',
				hoverOpacity: 0.7,
				selectedColor: '#666666',
				enableZoom: true,
				showTooltip: true,
				values: data,
				normalizeFunction: 'polynomial',
				onLabelShow: function(event, label, code) {
					if (json[code]) {
						label.html('<strong>' + label.text() + '</strong><br />' + '<?php echo $text_order; ?> ' + json[code]['total'] + '<br />' + '<?php echo $text_sale; ?> ' + json[code]['amount']);
					}
				}
			});			
		},
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
	});			
});
//--></script> 