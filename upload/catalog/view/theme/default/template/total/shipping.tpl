<div>
  <div class="cart-heading"><?php echo $heading_title; ?></div>
  <div class="cart-content">
    <p><?php echo $text_shipping; ?></p>
    <table id="shipping">
      <tr>
        <td><span class="required">*</span> <?php echo $entry_country; ?></td>
        <td><select name="country_id" onchange="$('select[name=\'zone_id\']').load('index.php?route=total/shipping/zone&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');">
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($countries as $country) { ?>
            <?php if ($country['country_id'] == $country_id) { ?>
            <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_zone; ?></td>
        <td><select name="zone_id">
          </select></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_postcode; ?></td>
        <td><input type="text" name="postcode" value="<?php echo $postcode; ?>" /></td>
      </tr>
    </table>
    <a id="button-quote" class="button"><span><?php echo $button_quote; ?></span></a>
    <div id="quote" style="display: none;"></div>
    <input type="hidden" name="shipping_method" value="<?php echo $code; ?>" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-quote').bind('click', function() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=total/shipping/quote',
		data: 'country_id=' + $('select[name=\'country_id\']').val() + '&zone_id=' + $('select[name=\'zone_id\']').val() + '&postcode=' + encodeURIComponent($('input[name=\'postcode\']').val()),
		dataType: 'json',		
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-quote').attr('disabled', true);
			$('#button-quote').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-quote').attr('disabled', false);
			$('.wait').remove();
		},		
		success: function(json) {
			$('.error').remove();

			if (json['redirect']) {
				location = json['redirect'];
			}
						
			if (json['error']) {
				if (json['error']['warning']) {
					$('#basket').before('<div class="warning">' + json['error']['warning'] + '</div>');
				}
				
				if (json['error']['country']) {
					$('#shipping select[name=\'country_id\'] + br').after('<span class="error">' + json['error']['country'] + '</span>');
				}	
				
				if (json['error']['zone']) {
					$('#shipping select[name=\'zone_id\'] + br').after('<span class="error">' + json['error']['zone'] + '</span>');
				}
				
				if (json['error']['postcode']) {
					$('#shipping input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}					
			}
			
			if (json['shipping_methods']) {
				html  = '<br />';
				html += '<table width="100%" cellpadding="3">';
				
				for (i in json['shipping_methods']) {
					html += '<tr>';
					html += '  <td colspan="3"><b>' + json['shipping_methods'][i]['title'] + '</b></td>';
					html += '</tr>';
				
					if (!json['shipping_methods'][i]['error']) {
						for (j in json['shipping_methods'][i]['quote']) {
							html += '<tr>';
							
							if (json['shipping_methods'][i]['quote'][j]['code'] == $('input[name=\'shipping_method\']').attr('value')) {
								html += '<td width="1"><input type="radio" name="shipping_method" value="' + json['shipping_methods'][i]['quote'][j]['code'] + '" id="' + json['shipping_methods'][i]['quote'][j]['code'] + '" checked="checked" /></td>';
							} else {
								html += '<td width="1"><input type="radio" name="shipping_method" value="' + json['shipping_methods'][i]['quote'][j]['code'] + '" id="' + json['shipping_methods'][i]['quote'][j]['code'] + '" /></td>';
							}
								
							html += '  <td><label for="' + json['shipping_methods'][i]['quote'][j]['code'] + '">' + json['shipping_methods'][i]['quote'][j]['title'] + '</label></td>';
							html += '  <td width="1"><label for="' + json['shipping_methods'][i]['quote'][j]['code'] + '">' + json['shipping_methods'][i]['quote'][j]['text'] + '</label></td>';
							html += '</tr>';
						}		
					} else {
						html += '<tr>';
						html += '  <td colspan="3"><div class="error">' + json['shipping_methods'][i]['error'] + '</div></td>';
						html += '</tr>	';						
					}
				}
				
				html += '</table>';
				html += '<br /><a id="button-shipping" class="button"><span><?php echo $button_shipping; ?></span></a>';				
		
				$('#quote').html(html);	
			
				$('#quote').slideDown('slow');
			}
		}
	});
});

$('#button-shipping').live('click', function() {
	$.ajax({
		type: 'POST',
		url: 'index.php?route=total/shipping/calculate',
		data: 'shipping_method=' + $('input[name=\'shipping_method\']:checked').attr('value'),
		dataType: 'json',		
		beforeSend: function() {
			$('.warning').remove();
			$('#button-shipping').attr('disabled', true);
			$('#button-shipping').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},
		complete: function() {
			$('#button-shipping').attr('disabled', false);
			$('.wait').remove();
		},		
		success: function(json) {
			if (json['error']) {
				$('#shipping').before('<div class="warning">' + json['error'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
			}
			
			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});
//--></script> 
<script type="text/javascript"><!--
$('select[name=\'zone_id\']').load('index.php?route=total/shipping/zone&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
//--></script> 