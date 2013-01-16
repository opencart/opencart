<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div id="payment"><b><?php echo $text_payment_option; ?></b>
  <div class="content">
    <table class="radio">
      <?php foreach ($payment_options as $payment_option) { ?>
      <tr class="highlight">
        <td><input type="radio" name="code" value="<?php echo $payment_option['code']; ?>" id="plan-id<?php echo $payment_option['code']; ?>" /></td>
        <td><label for="plan-id<?php echo $payment_option['code']; ?>"><?php echo $payment_option['title']; ?>
            <?php if ($iso_code_3 == 'NLD') { ?>
            <img src="catalog/view/theme/default/image/klarna_nld_banner.png" />
            <?php } ?>
          </label></td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <b><?php echo $text_additional; ?></b>
  <div class="content">
    <div style="float: right;"><img src="https://cdn.klarna.com/public/images/<?php echo $iso_code_2; ?>/badges/v1/account/<?php echo $iso_code_2; ?>_account_badge_std_blue.png?width=150&eid=<?php echo $merchant; ?>" /></div>
    <table class="form">
      <?php if (!$company) { ?>
      <?php if ($iso_code_3 == 'DEU' || $iso_code_3 == 'NLD') { ?>
      <tr>
        <td><?php echo $entry_birthday; ?></td>
        <td><select name="pno_day">
            <option value=""><?php echo $text_day; ?></option>
            <?php foreach ($days as $day) { ?>
            <option value="<?php echo $day['value']; ?>"><?php echo $day['text']; ?></option>
            <?php } ?>
          </select>
          <select name="pno_month">
            <option value=""><?php echo $text_month; ?></option>
            <?php foreach ($months as $month) { ?>
            <option value="<?php echo $month['value']; ?>"><?php echo $month['text']; ?></option>
            <?php } ?>
          </select>
          <select name="pno_year">
            <option value=""><?php echo $text_year; ?></option>
            <?php foreach ($years as $year) { ?>
            <option value="<?php echo $year['value']; ?>"><?php echo $year['text']; ?></option>
            <?php } ?>
          </select></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td><?php echo $entry_pno; ?></td>
        <td><input type="text" name="pno" value="" /></td>
      </tr>
      <?php } ?>
      <?php } elseif (!$company_id) { ?>
      <tr>
        <td><?php echo $entry_company; ?></td>
        <td><input type="text" name="pno" value="" /></td>
      </tr>
      <?php } ?>
      <?php if ($iso_code_3 == 'DEU' || $iso_code_3 == 'NLD') { ?>
      <tr>
        <td><?php echo $entry_gender; ?></td>
        <td><input type="radio" name="gender" value="1" id="male" />
          <label for="male"><?php echo $text_male; ?></label>
          <input type="radio" name="gender" value="0" id="female" />
          <label for="female"><?php echo $text_female; ?></label></td>
      </tr>
      <tr>
        <td><?php echo $entry_street; ?></td>
        <td><input type="text" name="street" value="<?php echo $street; ?>" /></td>
      </tr>
      <tr>
        <td><?php echo $entry_house_no; ?></td>
        <td><input type="text" name="house_no" value="<?php echo $street_number; ?>" /></td>
      </tr>
      <?php } ?>
      <?php if ($iso_code_3 == 'NLD') { ?>
      <tr>
        <td><?php echo $entry_house_ext; ?></td>
        <td><input type="text" name="house_ext" value="<?php echo $street_extension; ?>" /></td>
      </tr>
      <?php } ?>
      <tr>
        <td><?php echo $entry_phone_no; ?></td>
        <td><input type="text" name="phone_no" value="<?php echo $phone_number; ?>" /></td>
      </tr>
      <?php if ($iso_code_3 == 'DEU') { ?>
      <tr>
        <td colspan="2"><input type="checkbox" name="deu_toc" value="1" />
          Mit der Übermittlung der für die Abwicklung des Rechnungskaufes und einer Identitäts - und Bonitätsprüfung erforderlichen 
          Daten an Klarna bin ich einverstanden. Meine <a href="https://online.klarna.com/consent_de.yaws" target="_blank">Einwilligung</a> kann ich jederzeit mit Wirkung für die Zukunft widerrufen.</td>
      </tr>
      <?php } ?>
    </table>
  </div>
</div>
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_confirm; ?>" id="button-confirm" class="button" />
  </div>
</div>
<div class="klarna_baloon" id="klarna_baloon" style="display: none">
  <div class="klarna_baloon_top"></div>
  <div class="klarna_baloon_middle" id="klarna_baloon_content">
    <div></div>
  </div>
  <div class="klarna_baloon_bottom"></div>
</div>
<a id="klarna_toc_link">test</a>
<script type="text/javascript"><!--
$('#button-confirm').bind('click', function() {
	$.ajax({
		url: 'index.php?route=payment/klarna_account/send',
		type: 'post',
		data: $('#payment :input'),
		dataType: 'json',		
		beforeSend: function() {
			$('#button-confirm').attr('disabled', true);
			$('#payment').before('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-confirm').attr('disabled', false);
			$('.attention').remove();
		},		
		success: function(json) {
			$('.warning, .error').remove();			
			
			if (json['error']) {
				$('#payment').before('<div class="warning">' + json['error'] + '</div>');
			}

			if (json['redirect']) {
				location = json['redirect'];
			}
		}
	});
});

$.getScript('http://cdn.klarna.com/public/kitt/toc/v1.0/js/klarna.terms.min.js', function(){
	var terms = new Klarna.Terms.Account({  
		el: 'klarna_toc_link', 
		eid: <?php echo $merchant ?>,             
		country: '<?php echo strtolower($iso_code_2); ?>',
	});
});

$(document).ready(function(){
    $('#payment table.form input[type="text"]').focusin(function () {
        var field = $(this);
        hideBaloon(function (){
            var position = field.offset();
            var value = field.attr('alt');

            if (!value) {
                return false;
            }

            $('#klarna_baloon_content div').html(value);

            var top = position.top - $('#klarna_baloon').height();

            if (top < 0) {
                top = 10;
            } 

            position.top = top;

            var left = (position.left + field.width()) - ($('#klarna_baloon').width());

            position.left = left;

            $('#klarna_baloon').css(position);

            $('#klarna_baloon').fadeIn('fast');
        });

    }).focusout(function () {
        hideBaloon();
    });
	
	function hideBaloon (callback) {
		if ($('#klarna_baloon').is(":visible")) {
			$('#klarna_baloon').fadeOut('fast', function (){
				if (callback) {
					callback();
				}
				return true;
			});
		} else {
			if(callback) {
				callback();
			}
			
			return true;
		}
	}


<?php if ($iso_code_3 == 'DNK') { ?>
$('#payment input[name=\'code\']').change(function(){
	if ($(this).attr('value') == '-1') {
		$('input[name="yearly_salary"]').prop('disabled', true);
	} else {
		$('input[name="yearly_salary"]').prop('disabled', false);
	}
});
<?php } ?>
    


});
//--></script>