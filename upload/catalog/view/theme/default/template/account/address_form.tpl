<?php echo $header; ?>

<!-- Breadcrumb -->
<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    
    <li>
        <a href="<?php echo $breadcrumb['href']; ?>">
            <?php echo $breadcrumb['text']; ?>
        </a>
    </li>
    <?php } ?>
</ul>

<div class="row">

    <?php echo $column_left; ?>

    <div id="content" class="span9">

        <?php echo $content_top; ?>

        <h2><?php echo $text_edit_address; ?></h2>
        
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">

            <div class="content">

                <fieldset>

                    <div class="control-group">

                            <label class="control-label" for="firstname">
                                <span class="text-error">*</span> <?php echo $entry_firstname; ?>
                            </label>
                            <div class="controls">
                                <input type="text" name="firstname" value="<?php echo $firstname; ?>" />
                                <?php if ($error_firstname) { ?>
                                <div class="alert alert-error alert-form"><?php echo $error_firstname; ?></div>
                                <?php } ?>
                            </div> <!-- controls -->

                    </div> <!-- control-group -->

                    <div class="control-group">

                            <label class="control-label" for="firstname">
                                <span class="text-error">*</span> <?php echo $entry_lastname; ?>
                            </label>
                            <div class="controls">
                                <input type="text" name="lastname" value="<?php echo $lastname; ?>" />
                                <?php if ($error_lastname) { ?>
                                <div class="alert alert-error alert-form"><?php echo $error_lastname; ?></div>
                                <?php } ?>
                            </div> <!-- controls -->

                    </div> <!-- control-group -->


                    <div class="control-group">

                        <label class="control-label" for="firstname">
                            <?php echo $entry_company; ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="company" value="<?php echo $company; ?>" />
                        </div> <!-- controls -->

                    </div> <!-- control-group -->

                    <?php if ($company_id_display) { ?>
                    <div class="control-group">

                        <label class="control-label" for="firstname">
                            <?php echo $entry_company_id; ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="company_id" value="<?php echo $company_id; ?>" />
                            <?php if ($error_company_id) { ?>
                            <div class="alert alert-error alert-form"><?php echo $error_company_id; ?></div>
                            <?php } ?>
                        </div> <!-- controls -->

                    </div> <!-- control-group -->
                    <?php } ?>

                    <?php if ($tax_id_display) { ?>
                    <div class="control-group">

                        <label class="control-label" for="firstname">
                            <?php echo $entry_tax_id; ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="tax_id" value="<?php echo $tax_id; ?>" />
                            <?php if ($error_tax_id) { ?>
                            <div class="alert alert-error alert-form"><?php echo $error_tax_id; ?></div>
                            <?php } ?>
                        </div> <!-- controls -->

                    </div> <!-- control-group -->
                    <?php } ?>

                    <div class="control-group">

                        <label class="control-label" for="firstname">
                            <?php echo $entry_company; ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="address_1" value="<?php echo $address_1; ?>" />
                            <?php if ($error_address_1) { ?>
                            <div class="alert alert-error alert-form"><?php echo $error_address_1; ?></div>
                            <?php } ?>
                        </div> <!-- controls -->

                    </div> <!-- control-group -->

                    <div class="control-group">

                        <label class="control-label" for="firstname">
                            <?php echo $entry_address_2; ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="address_2" value="<?php echo $address_2; ?>" />
                        </div> <!-- controls -->

                    </div> <!-- control-group -->

                    <div class="control-group">

                        <label class="control-label" for="firstname">
                            <span class="text-error">*</span> <?php echo $entry_city; ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="city" value="<?php echo $city; ?>" />
                            <?php if ($error_city) { ?>
                            <div class="alert alert-error alert-form"><?php echo $error_city; ?></div>
                            <?php } ?>
                        </div> <!-- controls -->

                    </div> <!-- control-group -->

                    <div class="control-group">

                        <label class="control-label" for="firstname">
                            <span id="postcode-required" class="text-error">*</span> <?php echo $entry_postcode; ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="postcode" value="<?php echo $postcode; ?>" />
                            <?php if ($error_postcode) { ?>
                            <div class="alert alert-error alert-form"><?php echo $error_postcode; ?></div>
                            <?php } ?>
                        </div> <!-- controls -->

                    </div> <!-- control-group -->

                    <div class="control-group">

                        <label class="control-label" for="firstname">
                            <span class="text-error">*</span> <?php echo $entry_country; ?>
                        </label>
                        <div class="controls">
                            <select name="country_id">
                                <option value=""><?php echo $text_select; ?></option>
                                <?php foreach ($countries as $country) { ?>
                                <?php if ($country['country_id'] == $country_id) { ?>
                                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                            <?php if ($error_country) { ?>
                            <div class="alert alert-error alert-form"><?php echo $error_country; ?></div>
                            <?php } ?>
                        </div> <!-- controls -->

                    </div> <!-- control-group -->

                    <div class="control-group">

                        <label class="control-label" for="firstname">
                            <span class="text-error">*</span> <?php echo $entry_zone; ?>
                        </label>
                        <div class="controls">
                            <select name="zone_id">
                            </select>
                            <?php if ($error_zone) { ?>
                            <div class="alert alert-error alert-form"><?php echo $error_zone; ?></div>
                            <?php } ?>
                        </div> <!-- controls -->

                    </div> <!-- control-group -->

                    <?php if ($default) { ?>

                    <div class="control-group">
                        <div class="control-label">
                            <label class="control-label" for="firstname"><?php echo $entry_default; ?></label>
                        </div>

                        <div class="controls">
                            <label class="radio">
                                <input type="radio" name="default" value="1" checked="checked" />
                                <?php echo $text_yes; ?>
                            </label>

                            <label class="radio">
                                <input type="radio" name="default" value="0" />
                                <?php echo $text_no; ?>
                            </label>
                        </div>
                    </div>

                    <?php } else { ?>

                    <div class="control-group">
                        <div class="control-label">
                            <label for="firstname"><?php echo $entry_default; ?></label>
                        </div>
                        
                        <div class="controls">
                            <label class="radio">
                                <input type="radio" name="default" value="1" />
                                <?php echo $text_yes; ?>
                            </label>
                            
                            <label class="radio">
                                <input type="radio" name="default" value="0" checked="checked" />
                                <?php echo $text_no; ?>
                            </label>
                            
                        </div>
                    </div>
                    <?php } ?>

                </fieldset>                
            </div>

            <a href="<?php echo $back; ?>" class="btn"><?php echo $button_back; ?></a>
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary pull-right" />

        </form> <!-- form-horizontal -->

        <?php echo $content_bottom; ?>

    </div> <!-- content span9 -->

    <?php echo $column_right; ?>

</div> <!-- row -->




<script type="text/javascript"><!--
$('select[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=account/address/country&country_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('select[name=\'country_id\']').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},		
		complete: function() {
			$('.wait').remove();
		},			
		success: function(json) {
			if (json['postcode_required'] == '1') {
				$('#postcode-required').show();
			} else {
				$('#postcode-required').hide();
			}
			
			html = '<option value=""><?php echo $text_select; ?></option>';
			
			if (json['zone'] != '') {
				for (i = 0; i < json['zone'].length; i++) {
        			html += '<option value="' + json['zone'][i]['zone_id'] + '"';
	    			
					if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
	      				html += ' selected="selected"';
	    			}
	
	    			html += '>' + json['zone'][i]['name'] + '</option>';
				}
			} else {
				html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
			}
			
			$('select[name=\'zone_id\']').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('select[name=\'country_id\']').trigger('change');
//--></script> 
<?php echo $footer; ?>