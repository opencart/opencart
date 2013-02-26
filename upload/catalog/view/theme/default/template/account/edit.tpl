<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <h2><?php echo $text_your_details; ?></h2>
  <div class="content">
    <table class="form">
      <tr>
        <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
        <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" /></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
        <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" /></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_email; ?></td>
        <td><input type="text" name="email" value="<?php echo $email; ?>" /></td>
      </tr>
      <tr>
        <td><span class="required">*</span> <?php echo $entry_telephone; ?></td>
        <td><input type="text" name="telephone" value="<?php echo $telephone; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_fax; ?></td>
        <td><input type="text" name="fax" value="<?php echo $fax; ?>" /></td>
      </tr>
    </table>
  </div>
  <div class="buttons">
    <div class="left"><a href="<?php echo $back; ?>" class="button"><?php echo $button_back; ?></a></div>
    <div class="right">
      <input type="button" value="<?php echo $button_continue; ?>" id="button-edit" class="button" />
    </div>
  </div>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('#button-edit').on('click', function() {
	$.ajax({
		url: 'index.php?route=account/edit/save',
		type: 'post',
		data: $('input[type=\'text\'], input[type=\'password\'], input[type=\'checkbox\']:checked, input[type=\'radio\']:checked, input[type=\'hidden\'], select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-edit').attr('disabled', true);
			$('#button-edit').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		},	
		complete: function() {
			$('#button-edit').attr('disabled', false); 
			$('.loading').remove();
		},			
		success: function(json) {
			$('.warning, .error').remove();
						
			if (json['redirect']) {
				location = json['redirect'];				
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#notification').html('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
					$('.warning').fadeIn('slow');
				}
				
				if (json['error']['firstname']) {
					$('input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}
				
				if (json['error']['lastname']) {
					$('input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}	
				
				if (json['error']['email']) {
					$('input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
				}
				
				if (json['error']['telephone']) {
					$('input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}	
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});
//--></script> 
<?php echo $footer; ?>