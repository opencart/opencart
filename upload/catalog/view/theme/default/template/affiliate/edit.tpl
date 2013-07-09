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

<?php if ($error_warning) { ?>
<div class="alert alert-error"><?php echo $error_warning; ?></div>
<?php } ?>


<div class="row">

    <?php echo $column_left; ?>

    <div id="content" class="span9">

        <?php echo $content_top; ?>

        <h2><?php echo $heading_title; ?></h2>

        <form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

            <!-- Your Personal Details -->
            
            <h3><?php echo $text_your_details; ?></h3>
            
            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_firstname; ?></label>
                <div class="controls">
                    <input type="text" name="firstname" value="<?php echo $firstname; ?>" />

                    <?php if ($error_firstname) { ?>
                        <div class="alert alert-error alert-form"><?php echo $error_firstname; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_lastname; ?></label>
                <div class="controls">
                    <input type="text" name="lastname" value="<?php echo $lastname; ?>" />

                    <?php if ($error_lastname) { ?>
                        <div class="alert alert-error alert-form"><?php echo $error_lastname; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_email; ?></label>
                <div class="controls">
                    <input type="text" name="email" value="<?php echo $email; ?>" />

                    <?php if ($error_email) { ?>
                        <div class="alert alert-error alert-form"><?php echo $error_email; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_telephone; ?></label>
                <div class="controls">
                    <input type="text" name="telephone" value="<?php echo $telephone; ?>" />

                    <?php if ($error_telephone) { ?>
                    <div class="alert alert-error alert-form"><?php echo $error_telephone; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><?php echo $entry_fax; ?></label>
                <div class="controls">
                    <input type="text" name="fax" value="<?php echo $fax; ?>" />
                </div>
            </div>

            <!-- Your Address -->

            <h3><?php echo $text_your_address; ?></h3>

            <div class="control-group">
                <label class="control-label"><?php echo $entry_company; ?></label>
                <div class="controls">
                    <input type="text" name="company" value="<?php echo $company; ?>" />
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><?php echo $entry_website; ?></label>
                <div class="controls">
                    <input type="text" name="website" value="<?php echo $website; ?>" />
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_address_1; ?></label>
                <div class="controls">
                    <input type="text" name="address_1" value="<?php echo $address_1; ?>" />

                    <?php if ($error_address_1) { ?>
                        <div class="alert alert-error alert-form"><?php echo $error_address_1; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><?php echo $entry_address_2; ?></label>
                <div class="controls">
                    <input type="text" name="address_2" value="<?php echo $address_2; ?>" />
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_city; ?></label>
                <div class="controls">
                    <input type="text" name="city" value="<?php echo $city; ?>" />

                    <?php if ($error_city) { ?>
                        <div class="alert alert-error alert-form"><?php echo $error_city; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span id="postcode-required" class="text-error">*</span> <?php echo $entry_postcode; ?></label>
                <div class="controls">
                    <input type="text" name="postcode" value="<?php echo $postcode; ?>" />

                    <?php if ($error_postcode) { ?>
                        <div class="alert alert-error alert-form"><?php echo $error_postcode; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_country; ?></label>
                <div class="controls">
                    <select name="country_id">
                        <option value="false"><?php echo $text_select; ?></option>
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
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_zone; ?></label>
                <div class="controls">
                    <select name="zone_id"></select>

                    <?php if ($error_zone) { ?>
                        <div class="alert alert-error alert-form"><?php echo $error_zone; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="buttons clearfix">
                <div class="pull-left">
                    <a href="<?php echo $back; ?>" class="btn"><?php echo $button_back; ?></a>
                </div>
                <div class="pull-right">
                    <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
                </div>
                
            </div>

        </form>

        <?php echo $content_bottom; ?>

    </div>

    <?php echo $column_right; ?>

</div>

<script type="text/javascript"><!--
$('select[name=\'country_id\']').bind('change', function() {
	$.ajax({
		url: 'index.php?route=affiliate/edit/country&country_id=' + this.value,
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