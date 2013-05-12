<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons">
          <button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
          <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <?php if ($customer_id) { ?>
          <li><a href="#tab-history" data-toggle="tab"><?php echo $tab_history; ?></a></li>
          <li><a href="#tab-transaction" data-toggle="tab"><?php echo $tab_transaction; ?></a></li>
          <li><a href="#tab-reward" data-toggle="tab"><?php echo $tab_reward; ?></a></li>
          <?php } ?>
          <li><a href="#tab-ip" data-toggle="tab"><?php echo $tab_ip; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <div class="tabbable tabs-left">
              <ul class="nav nav-tabs" id="address">
                <li class="active"><a href="#tab-customer" data-toggle="tab"><?php echo $tab_general; ?></a></li>
                <?php $address_row = 1; ?>
                <?php foreach ($addresses as $address) { ?>
                <li><a href="#tab-address<?php echo $address_row; ?>" data-toggle="tab"><i class="icon-minus-sign" onclick="$('#address a[href=\'#tab-address<?php echo $address_row; ?>\']').parent().remove(); $('#tab-address<?php echo $address_row; ?>').remove();"></i> <?php echo $tab_address . ' ' . $address_row; ?></a></li>
                <?php $address_row++; ?>
                <?php } ?>
                <li id="address-add"><a onclick="addAddress();"><i class="icon-plus-sign"></i> <?php echo $button_add_address; ?></a></li>
              </ul>
              <div class="tab-content">
                <div class="tab-pane active" id="tab-customer">
                  <div class="control-group">
                    <label class="control-label" for="input-firstname"><span class="required">*</span> <?php echo $entry_firstname; ?></label>
                    <div class="controls">
                      <input type="text" name="firstname" value="<?php echo $firstname; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname" />
                      <?php if ($error_firstname) { ?>
                      <span class="error"><?php echo $error_firstname; ?></span>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-lastname"><span class="required">*</span> <?php echo $entry_lastname; ?></label>
                    <div class="controls">
                      <input type="text" name="lastname" value="<?php echo $lastname; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname" />
                      <?php if ($error_lastname) { ?>
                      <span class="error"><?php echo $error_lastname; ?></span>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-email"><span class="required">*</span> <?php echo $entry_email; ?></label>
                    <div class="controls">
                      <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" />
                      <?php if ($error_email) { ?>
                      <span class="error"><?php echo $error_email; ?></span>
                      <?php  } ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-telephone"><span class="required">*</span> <?php echo $entry_telephone; ?></label>
                    <div class="controls">
                      <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" />
                      <?php if ($error_telephone) { ?>
                      <span class="error"><?php echo $error_telephone; ?></span>
                      <?php  } ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-fax"><?php echo $entry_fax; ?></label>
                    <div class="controls">
                      <input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" />
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                    <div class="controls">
                      <input type="password" name="password" value="<?php echo $password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" />
                      <?php if ($error_password) { ?>
                      <span class="error"><?php echo $error_password; ?></span>
                      <?php  } ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-confirm"><?php echo $entry_confirm; ?></label>
                    <div class="controls">
                      <input type="password" name="confirm" value="<?php echo $confirm; ?>" placeholder="<?php echo $entry_confirm; ?>" id="input-confirm" />
                      <?php if ($error_confirm) { ?>
                      <span class="error"><?php echo $error_confirm; ?></span>
                      <?php  } ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-newsletter"><?php echo $entry_newsletter; ?></label>
                    <div class="controls">
                      <select name="newsletter" id="input-newsletter">
                        <?php if ($newsletter) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-customer-group"><?php echo $entry_customer_group; ?></label>
                    <div class="controls">
                      <select name="customer_group_id" id="input-customer-group">
                        <?php foreach ($customer_groups as $customer_group) { ?>
                        <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                        <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                    <div class="controls">
                      <select name="status" id="input-status">
                        <?php if ($status) { ?>
                        <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                        <option value="0"><?php echo $text_disabled; ?></option>
                        <?php } else { ?>
                        <option value="1"><?php echo $text_enabled; ?></option>
                        <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                </div>
                <?php $address_row = 1; ?>
                <?php foreach ($addresses as $address) { ?>
                <div class="tab-pane" id="tab-address<?php echo $address_row; ?>">
                  <input type="hidden" name="address[<?php echo $address_row; ?>][address_id]" value="<?php echo $address['address_id']; ?>" />
                  <div class="control-group">
                    <label class="control-label" for="input-firstname<?php echo $address_row; ?>"><span class="required">*</span> <?php echo $entry_firstname; ?></label>
                    <div class="controls">
                      <input type="text" name="address[<?php echo $address_row; ?>][firstname]" value="<?php echo $address['firstname']; ?>" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname<?php echo $address_row; ?>" />
                      <?php if (isset($error_address_firstname[$address_row])) { ?>
                      <span class="error"><?php echo $error_address_firstname[$address_row]; ?></span>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-lastname<?php echo $address_row; ?>"><span class="required">*</span> <?php echo $entry_lastname; ?></label>
                    <div class="controls">
                      <input type="text" name="address[<?php echo $address_row; ?>][lastname]" value="<?php echo $address['lastname']; ?>" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname<?php echo $address_row; ?>" />
                      <?php if (isset($error_address_lastname[$address_row])) { ?>
                      <span class="error"><?php echo $error_address_lastname[$address_row]; ?></span>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-company<?php echo $address_row; ?>"><?php echo $entry_company; ?></label>
                    <div class="controls">
                      <input type="text" name="address[<?php echo $address_row; ?>][company]" value="<?php echo $address['company']; ?>" placeholder="<?php echo $entry_company; ?>" id="input-company<?php echo $address_row; ?>" />
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-address-1<?php echo $address_row; ?>"><span class="required">*</span> <?php echo $entry_address_1; ?></label>
                    <div class="controls">
                      <input type="text" name="address[<?php echo $address_row; ?>][address_1]" value="<?php echo $address['address_1']; ?>" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1<?php echo $address_row; ?>" />
                      <?php if (isset($error_address_address_1[$address_row])) { ?>
                      <span class="error"><?php echo $error_address_address_1[$address_row]; ?></span>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-address-2<?php echo $address_row; ?>"><?php echo $entry_address_2; ?></label>
                    <div class="controls">
                      <input type="text" name="address[<?php echo $address_row; ?>][address_2]" value="<?php echo $address['address_2']; ?>" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2<?php echo $address_row; ?>" />
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-city<?php echo $address_row; ?>"><span class="required">*</span> <?php echo $entry_city; ?></label>
                    <div class="controls">
                      <input type="text" name="address[<?php echo $address_row; ?>][city]" value="<?php echo $address['city']; ?>" placeholder="<?php echo $entry_city; ?>" id="input-city<?php echo $address_row; ?>" />
                      <?php if (isset($error_address_city[$address_row])) { ?>
                      <span class="error"><?php echo $error_address_city[$address_row]; ?></span>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-postcode<?php echo $address_row; ?>"> <span id="postcode-required<?php echo $address_row; ?>" class="required">*</span> <?php echo $entry_postcode; ?></label>
                    <div class="controls">
                      <input type="text" name="address[<?php echo $address_row; ?>][postcode]" value="<?php echo $address['postcode']; ?>" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode<?php echo $address_row; ?>" />
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-country<?php echo $address_row; ?>"><span class="required">*</span> <?php echo $entry_country; ?></label>
                    <div class="controls">
                      <select name="address[<?php echo $address_row; ?>][country_id]" id="input-country<?php echo $address_row; ?>" onchange="country(this, '<?php echo $address_row; ?>', '<?php echo $address['zone_id']; ?>');">
                        <option value=""><?php echo $text_select; ?></option>
                        <?php foreach ($countries as $country) { ?>
                        <?php if ($country['country_id'] == $address['country_id']) { ?>
                        <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                      <?php if (isset($error_address_country[$address_row])) { ?>
                      <span class="error"><?php echo $error_address_country[$address_row]; ?></span>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <label class="control-label" for="input-zone<?php echo $address_row; ?>"><span class="required">*</span> <?php echo $entry_zone; ?></label>
                    <div class="controls">
                      <select name="address[<?php echo $address_row; ?>][zone_id]" id="input-zone<?php echo $address_row; ?>">
                      </select>
                      <?php if (isset($error_address_zone[$address_row])) { ?>
                      <span class="error"><?php echo $error_address_zone[$address_row]; ?></span>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="control-group">
                    <div class="control-label"><?php echo $entry_default; ?></div>
                    <div class="controls">
                      <label class="radio">
                        <?php if (($address['address_id'] == $address_id) || !$addresses) { ?>
                        <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="radio" name="address[<?php echo $address_row; ?>][default]" value="<?php echo $address_row; ?>" />
                        <?php } ?>
                      </label>
                    </div>
                  </div>
                </div>
                <?php $address_row++; ?>
                <?php } ?>
              </div>
            </div>
          </div>
          <?php if ($customer_id) { ?>
          <div class="tab-pane" id="tab-history">
            <div id="history"></div>
            <div class="control-group">
              <label class="control-label" for="input-comment"><?php echo $entry_comment; ?></label>
              <div class="controls">
                <textarea name="comment" cols="40" rows="8" placeholder="<?php echo $entry_comment; ?>" id="input-comment" class="input-xxlarge"></textarea>
              </div>
            </div>
            <button id="button-history" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_history; ?></button>
          </div>
          <div class="tab-pane" id="tab-transaction">
            <div id="transaction"></div>
            <div class="control-group">
              <label class="control-label" for="input-transaction-description"><?php echo $entry_description; ?></label>
              <div class="controls">
                <input type="text" name="description" value="" placeholder="<?php echo $entry_description; ?>" id="input-transaction-description" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-amount"><?php echo $entry_amount; ?></label>
              <div class="controls">
                <input type="text" name="amount" value="" placeholder="<?php echo $entry_amount; ?>" id="input-amount" />
              </div>
            </div>
            <button id="button-transaction" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_transaction; ?></button>
          </div>
          <div class="tab-pane" id="tab-reward">
            <div id="reward"></div>
            <div class="control-group">
              <label class="control-label" for="input-reward-description"><?php echo $entry_description; ?></label>
              <div class="controls">
                <input type="text" name="description" value="" placeholder="<?php echo $entry_description; ?>" id="input-reward-description" />
              </div>
            </div>
            <div class="control-group">
              <label class="control-label" for="input-points"><?php echo $entry_points; ?></label>
              <div class="controls">
                <input type="text" name="points" value="" placeholder="<?php echo $entry_points; ?>" id="input-points" />
                
                <a data-toggle="tooltip" title="<?php echo $help_points; ?>"><i class="icon-info-sign"></i></a>
                
                </div>
            </div>
            <button id="button-reward" class="btn"><i class="icon-plus-sign"></i> <?php echo $button_add_reward; ?></button>
          </div>
          <?php } ?>
          <div class="tab-pane" id="tab-ip">
            <table class="table table-striped table-bordered table-hover">
              <thead>
                <tr>
                  <td class="left"><?php echo $column_ip; ?></td>
                  <td class="right"><?php echo $column_total; ?></td>
                  <td class="left"><?php echo $column_date_added; ?></td>
                  <td class="right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($ips) { ?>
                <?php foreach ($ips as $ip) { ?>
                <tr>
                  <td class="left"><a href="http://www.geoiptool.com/en/?IP=<?php echo $ip['ip']; ?>" target="_blank"><?php echo $ip['ip']; ?></a></td>
                  <td class="right"><a href="<?php echo $ip['filter_ip']; ?>" target="_blank"><?php echo $ip['total']; ?></a></td>
                  <td class="left"><?php echo $ip['date_added']; ?></td>
                  <td class="right"><?php if ($ip['ban_ip']) { ?>
                    <button value="<?php echo $ip['ip']; ?>" class="btn btn-mini button-ban-add"><i class="icon-minus-sign"></i> <?php echo $text_remove_ban_ip; ?></button>
                    <?php } else { ?>
                    <button value="<?php echo $ip['ip']; ?>" class="btn btn-mini"><i class="icon-plus-sign"></i> <?php echo $text_add_ban_ip; ?></button>
                    <?php } ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('select[name=\'customer_group_id\']').on('change', function() {

});

$('select[name=\'customer_group_id\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
var address_row = <?php echo $address_row; ?>;

function addAddress() {	
	html  = '<div class="tab-pane" id="tab-address' + address_row + '">';
	html += '  <input type="hidden" name="address[' + address_row + '][address_id]" value="" />';

	html += '  <div class="control-group">';
	html += '    <label class="control-label" for="input-firstname' + address_row + '"><span class="required">*</span> <?php echo $entry_firstname; ?></label>';
	html += '    <div class="controls"><input type="text" name="address[' + address_row + '][firstname]" value="" placeholder="<?php echo $entry_firstname; ?>" id="input-firstname' + address_row + '" /></div>';
	html += '  </div>'; 
	
	html += '  <div class="control-group">';
	html += '    <label class="control-label" for="input-lastname' + address_row + '"><span class="required">*</span> <?php echo $entry_lastname; ?></label>';
	html += '    <div class="controls"><input type="text" name="address[' + address_row + '][lastname]" value="" placeholder="<?php echo $entry_lastname; ?>" id="input-lastname' + address_row + '" /></div>';
	html += '  </div>'; 
	
	html += '  <div class="control-group">';
	html += '    <label class="control-label" for="input-company' + address_row + '"><?php echo $entry_company; ?></label>';
	html += '    <div class="controls"><input type="text" name="address[' + address_row + '][company]" value="" placeholder="<?php echo $entry_company; ?>" id="input-company' + address_row + '" /></div>';
	html += '  </div>'; 
	
	html += '  <div class="control-group">';
	html += '    <label class="control-label" for="input-address-1' + address_row + '"><span class="required">*</span> <?php echo $entry_address_1; ?></label>';
	html += '    <div class="controls"><input type="text" name="address[' + address_row + '][address_1]" value="" placeholder="<?php echo $entry_address_1; ?>" id="input-address-1' + address_row + '" /></div>';
	html += '  </div>'; 		
	
	html += '  <div class="control-group">';
	html += '    <label class="control-label" for="input-address-2' + address_row + '"><?php echo $entry_address_2; ?></label>';
	html += '    <div class="controls"><input type="text" name="address[' + address_row + '][address_2]" value="" placeholder="<?php echo $entry_address_2; ?>" id="input-address-2' + address_row + '" /></div>';
	html += '  </div>'; 
	
	html += '  <div class="control-group">';
	html += '    <label class="control-label" for="input-city' + address_row + '"><span class="required">*</span> <?php echo $entry_city; ?></label>';
	html += '    <div class="controls"><input type="text" name="address[' + address_row + '][city]" value="" placeholder="<?php echo $entry_city; ?>" id="input-city' + address_row + '" /></div>';
	html += '  </div>'; 
	
	html += '  <div class="control-group">';
	html += '    <label class="control-label" for="input-postcode' + address_row + '"><span id="postcode-required' + address_row + '" class="required">*</span> <?php echo $entry_postcode; ?></label>';
	html += '    <div class="controls"><input type="text" name="address[' + address_row + '][postcode]" value="" placeholder="<?php echo $entry_postcode; ?>" id="input-postcode' + address_row + '" /></div>';
	html += '  </div>'; 

	html += '  <div class="control-group">';
	html += '    <label class="control-label" for="input-country' + address_row + '"><span class="required">*</span> <?php echo $entry_country; ?></label>';
	html += '    <div class="controls"><select name="address[' + address_row + '][country_id]" id="input-country' + address_row + '" onchange="country(this, \'' + address_row + '\', \'0\');">';
    html += '         <option value=""><?php echo $text_select; ?></option>';
    <?php foreach ($countries as $country) { ?>
    html += '         <option value="<?php echo $country['country_id']; ?>"><?php echo addslashes($country['name']); ?></option>';
    <?php } ?>
    html += '      </select></div>';
	html += '  </div>'; 

	html += '  <div class="control-group">';
	html += '    <label class="control-label" for="input-zone' + address_row + '"><span class="required">*</span> <?php echo $entry_zone; ?></label>';
	html += '    <div class="controls"><select name="address[' + address_row + '][zone_id]" id="input-zone' + address_row + '"><option value="false"><?php echo $this->language->get('text_none'); ?></option></select></div>';
	html += '  </div>'; 

	html += '  <div class="control-group">';
	html += '    <div class="control-label"><?php echo $entry_default; ?></div>';
	html += '    <div class="controls"><label class="radio"><input type="radio" name="address[' + address_row + '][default]" value="1" /></label></div>';
	html += '  </div>'; 
								
    html += '</div>';
	
	$('#tab-general .tab-content').append(html);
	
	$('select[name=\'address[' + address_row + '][country_id]\']').trigger('change');	
	
	$('#address-add').before('<li><a href="#tab-address' + address_row + '" data-toggle="tab"><i class="icon-minus-sign" onclick="$(\'#address a:first\').tab(\'show\'); $(\'a[href=\\\'#tab-address' + address_row + '\\\']\').parent().remove(); $(\'#tab-address' + address_row + '\').remove();"></i> <?php echo $tab_address; ?> ' + address_row + '</a></li>');
	
	$('#address a[href=\'#tab-address' + address_row + '\']').tab('show');
	
	address_row++;
}
//--></script> 
<script type="text/javascript"><!--
function country(element, index, zone_id) {
  if (element.value != '') {
		$.ajax({
			url: 'index.php?route=sale/customer/country&token=<?php echo $token; ?>&country_id=' + element.value,
			dataType: 'json',
			beforeSend: function() {
				$('select[name=\'address[' + index + '][country_id]\']').after(' <i class="icon-spinner icon-spin"></i>');
			},
			complete: function() {
				$('.icon-spinner').remove();
			},			
			success: function(json) {
				if (json['postcode_required'] == '1') {
					$('#postcode-required' + index).show();
				} else {
					$('#postcode-required' + index).hide();
				}
				
				html = '<option value=""><?php echo $text_select; ?></option>';
				
				if (json['zone'] != '') {
					for (i = 0; i < json['zone'].length; i++) {
						html += '<option value="' + json['zone'][i]['zone_id'] + '"';
						
						if (json['zone'][i]['zone_id'] == zone_id) {
							html += ' selected="selected"';
						}
		
						html += '>' + json['zone'][i]['name'] + '</option>';
					}
				} else {
					html += '<option value="0"><?php echo $text_none; ?></option>';
				}
				
				$('select[name=\'address[' + index + '][zone_id]\']').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
}

$('select[name$=\'[country_id]\']').trigger('change');
//--></script> 
<script type="text/javascript"><!--
$('#history .pagination a').on('click', function() {
	$('#history').load(this.href);
	
	return false;
});			

$('#history').load('index.php?route=sale/customer/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-history').on('click', function() {
	$.ajax({
		url: 'index.php?route=sale/customer/history&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
		type: 'post',
		dataType: 'html',
		data: 'comment=' + encodeURIComponent($('#tab-history textarea[name=\'comment\']').val()),
		beforeSend: function() {
			$('.alert').remove();
			
			$('#button-history i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-history').prop('disabled', true);
		},
		complete: function() {
			$('#button-history i').replaceWith('<i class="icon-plus-sign"></i>');
			$('#button-history').prop('disabled', false);
		},
		success: function(html) {
			$('#history').html(html);
			
			$('#tab-history input[name=\'comment\']').val('');
		}
	});
});
//--></script> 
<script type="text/javascript"><!--
$('#transaction .pagination a').on('click', function() {
	$('#transaction').load(this.href);
	
	return false;
});			

$('#transaction').load('index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-transaction').on('click', function() {
	$.ajax({
		url: 'index.php?route=sale/customer/transaction&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
		type: 'post',
		dataType: 'html',
		data: 'description=' + encodeURIComponent($('#tab-transaction input[name=\'description\']').val()) + '&amount=' + encodeURIComponent($('#tab-transaction input[name=\'amount\']').val()),
		beforeSend: function() {
			$('.alert').remove();
			
			$('#button-transaction i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-transaction').prop('disabled', true);
		},
		complete: function() {
			$('#button-transaction i').replaceWith('<i class="icon-plus-sign"></i>');
			$('#button-transaction').prop('disabled', false);
		},
		success: function(html) {
			$('#transaction').html(html);
			
			$('#tab-transaction input[name=\'amount\']').val('');
			$('#tab-transaction input[name=\'description\']').val('');
		}
	});
});
//--></script> 
<script type="text/javascript"><!--
$('#reward .pagination a').on('click', function() {
	$('#reward').load(this.href);
	
	return false;
});			

$('#reward').load('index.php?route=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>');

$('#button-reward').on('click', function() {	
	$.ajax({
		url: 'index.php?route=sale/customer/reward&token=<?php echo $token; ?>&customer_id=<?php echo $customer_id; ?>',
		type: 'post',
		dataType: 'html',
		data: 'description=' + encodeURIComponent($('#tab-reward input[name=\'description\']').val()) + '&points=' + encodeURIComponent($('#tab-reward input[name=\'points\']').val()),
		beforeSend: function() {
			$('.alert').remove();
			
			$('#button-reward i').replaceWith('<i class="icon-spinner icon-spin"></i>');
			$('#button-reward').prop('disabled', true);

		},
		complete: function() {
			$('#button-reward i').replaceWith('<i class="icon-plus-sign"></i>');
			$('#button-reward').prop('disabled', false);
		},
		success: function(html) {
			$('#reward').html(html);
								
			$('#tab-reward input[name=\'points\']').val('');
			$('#tab-reward input[name=\'description\']').val('');
		}
	});
});

$('.ban-ip').on('click', function() {
	
function addBanIP(ip) {
	var id = ip.replace(/\./g, '-');
	
	$.ajax({
		url: 'index.php?route=sale/customer/addbanip&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'ip=' + encodeURIComponent(ip),
		beforeSend: function() {
			$('.success, .warning').remove();
			
			$('#button-ban-id i').replaceWith('<i class="icon-spinner icon-spin"></i>');
		},
		complete: function() {
			
		},			
		success: function(json) {
			$('.attention').remove();
			
			if (json['error']) {
				 $('.box').before('<div class="alert alert-error" style="display: none;">' + json['error'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
						
			if (json['success']) {
                $('.box').before('<div class="alert alert-success" style="display: none;">' + json['success'] + '</div>');
				
				$('.success').fadeIn('slow');
				
				$('#' + id).replaceWith('<a id="' + id + '" onclick="removeBanIP(\'' + ip + '\');"><?php echo $text_remove_ban_ip; ?></a>');
			}
		}
	});	
});

function removeBanIP(ip) {
	var id = ip.replace(/\./g, '-');
	
	$.ajax({
		url: 'index.php?route=sale/customer/removebanip&token=<?php echo $token; ?>',
		type: 'post',
		dataType: 'json',
		data: 'ip=' + encodeURIComponent(ip),
		beforeSend: function() {
			$('.alert').remove();
		},	
		success: function(json) {
			$('.attention').remove();
			
			if (json['error']) {
				 $('.box').before('<div class="alert alert-error" style="display: none;">' + json['error'] + '</div>');
				
				$('.warning').fadeIn('slow');
			}
			
			if (json['success']) {
				 $('.box').before('<div class="alert alert-success" style="display: none;">' + json['success'] + '</div>');
				
				$('.success').fadeIn('slow');
				
				$('#' + id).replaceWith('<a id="' + id + '" onclick="addBanIP(\'' + ip + '\');"><?php echo $text_add_ban_ip; ?></a>');
			}
		}
	});	
};
//--></script> 
<?php echo $footer; ?>