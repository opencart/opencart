<?php if ($error_warning) { ?>
<div class="alert alert-warning"><?php echo $error_warning; ?></div>
<?php } ?>
<p><img src="https://cdn.klarna.com/public/images/<?php echo $iso_code_2; ?>/badges/v1/account/<?php echo $iso_code_2; ?>_account_badge_std_blue.png?width=150&eid=<?php echo $merchant; ?>" /></p>
<form id="payment" class="form-horizontal">
  <fieldset>
    <legend><?php echo $text_payment_option; ?></legend>
    <?php foreach ($payment_options as $payment_option) { ?>
    <div class="radio">
      <label>
        <?php if (!isset($code)) { ?>
        <?php $code = $payment_option['code']; ?>
        <input type="radio" name="code" value="<?php echo $payment_option['code']; ?>" checked="checked" />
        <?php } else { ?>
        <input type="radio" name="code" value="<?php echo $payment_option['code']; ?>" />
        <?php } ?>
        <?php if ($iso_code_3 == 'NLD') { ?>
        <img src="catalog/view/theme/default/image/klarna_nld_banner.png" />
        <?php } ?>
        <?php echo $payment_option['title']; ?></label>
    </div>
    <?php } ?>
  </fieldset>
  <br />
  <fieldset>
    <legend><?php echo $text_additional; ?></legend>
    <?php if (!$company) { ?>
    <?php if ($iso_code_3 == 'DEU' || $iso_code_3 == 'NLD') { ?>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-dob"><?php echo $entry_dob; ?></label>
      <div class="col-sm-3">
        <select name="pno_day" id="input-dob" class="form-control">
          <option value=""><?php echo $text_day; ?></option>
          <?php foreach ($days as $day) { ?>
          <option value="<?php echo $day['value']; ?>"><?php echo $day['text']; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-3">
        <select name="pno_month" class="form-control">
          <option value=""><?php echo $text_month; ?></option>
          <?php foreach ($months as $month) { ?>
          <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
          <?php } ?>
        </select>
      </div>
      <div class="col-sm-3">
        <select name="pno_year" class="form-control">
          <option value=""><?php echo $text_year; ?></option>
          <?php foreach ($years as $year) { ?>
          <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
          <?php } ?>
        </select>
      </div>
    </div>
    <?php } else { ?>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-pno"><?php echo $entry_pno; ?></label>
      <div class="col-sm-10">
        <input type="text" name="pno" value="" id="input-pno" class="form-control" />
      </div>
    </div>
    <?php } ?>
    <?php } else { ?>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-company"><?php echo $entry_company; ?></label>
      <div class="col-sm-10">
        <input type="text" name="pno" value="" id="input-company" class="form-control" />
      </div>
    </div>
    <?php } ?>
    <?php if ($iso_code_3 == 'DEU' || $iso_code_3 == 'NLD') { ?>
    <div class="form-group required">
      <label class="col-sm-2 control-label"><?php echo $entry_gender; ?></label>
      <div class="col-sm-10">
        <label class="radio-inline">
          <input type="radio" name="gender" value="1" />
          <?php echo $text_male; ?></label>
        <label class="radio-inline">
          <input type="radio" name="gender" value="0" />
          <?php echo $text_female; ?></label>
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-street"><?php echo $entry_street; ?></label>
      <div class="col-sm-10">
        <input type="text" name="street" value="<?php echo $street; ?>" id="input-street" class="form-control" />
      </div>
    </div>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-house-no"><?php echo $entry_house_no; ?></label>
      <div class="col-sm-10">
        <input type="text" name="house_no" value="<?php echo $street_number; ?>" id="input-house-no" class="form-control" />
      </div>
    </div>
    <?php } ?>
    <?php if ($iso_code_3 == 'NLD') { ?>
    <div class="form-group">
      <label class="col-sm-2 control-label" for="input-house-ext"><?php echo $entry_house_ext; ?></label>
      <div class="col-sm-10">
        <input type="text" name="house_ext" value="<?php echo $street_extension; ?>" id="input-house-ext" class="form-control" />
      </div>
    </div>
    <?php } ?>
    <div class="form-group required">
      <label class="col-sm-2 control-label" for="input-phone-no"><?php echo $entry_phone_no; ?></label>
      <div class="col-sm-10">
        <input type="text" name="phone_no" value="<?php echo $phone_number; ?>" id="input-phone-no" class="form-control" />
      </div>
    </div>
    <?php if ($iso_code_3 == 'DEU') { ?>
    <div class="radio">
      <input type="checkbox" name="deu_terms" value="1" />
      Mit der Übermittlung der für die Abwicklung des Rechnungskaufes und einer Identitäts - und Bonitätsprüfung erforderlichen
      Daten an Klarna bin ich einverstanden. Meine <a href="https://online.klarna.com/consent_de.yaws" target="_blank">Einwilligung</a> kann ich jederzeit mit Wirkung für die Zukunft widerrufen.</div>
    <?php } ?>
  </fieldset>
</form>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
	$.ajax({
		url: 'index.php?route=extension/payment/klarna_account/send',
		type: 'post',
		data: $('#payment input[type=\'text\'], #payment input[type=\'checkbox\']:checked, #payment input[type=\'radio\']:checked, #payment select'),
		dataType: 'json',
		cache: false,
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},
		success: function(json) {
			$('.alert').remove();

			if (json['error']) {
				$('#payment').before('<div class="alert alert-danger">' + json['error'] + '</div>');
			}

			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});
//--></script>
