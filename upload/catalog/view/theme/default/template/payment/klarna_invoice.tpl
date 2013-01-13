<?php if ($address_match) { ?>
<form action="<?php echo html_entity_decode($klarna_send) ?>" method="post" id="klarna-payment-form">
  <div id="payment" class="content">
    <div> <img src="https://cdn.klarna.com/public/images/<?php echo $klarna_country_code ?>/badges/v1/invoice/<?php echo $klarna_country_code ?>_invoice_badge_std_blue.png?width=150&eid=<?php echo $merchant ?>"/> </div>
    <table class="form">
      <tr>
        <td colspan="2"><b><?php echo $text_additional; ?></b></td>
      </tr>
      <?php if (!$is_company || $country_code == 'DEU' || $country_code == 'NLD') { ?>
      <tr>
        <td><?php if ($country_code == 'DEU' || $country_code == 'NLD') { ?>
          <?php echo $entry_birthday ?>
          <?php } else { ?>
          <?php echo $entry_pno ?>
          <?php } ?></td>
        <td><?php if ($country_code == 'DEU' || $country_code == 'NLD') { ?>
          <select name="pno_day">
            <option value=""><?php echo $text_day ?></option>
            <?php for($i = 1; $i < 32; $i++) { ?>
            <option value="<?php echo $i ?>"><?php printf('%02d', $i) ?></option>
            <?php } ?>
          </select>
          <select name="pno_month">
            <option value=""><?php echo $text_month ?></option>
            <?php for($i = 1; $i < 13; $i++) { ?>
            <option value="<?php echo $i ?>"><?php printf('%02d', $i) ?></option>
            <?php } ?>
          </select>
          <select name="pno_year">
            <option value=""><?php echo $text_year ?></option>
            <?php for($i = date('Y'); $i >= 1900; $i-- ) { ?>
            <option value="<?php echo $i ?>"><?php echo $i ?></option>
            <?php } ?>
          </select>
          <?php } else { ?>
          <input type="text" name="pno" alt="<?php echo $help_pno ?>" />
          <?php } ?></td>
      </tr>
      <?php } elseif (empty($company_id)) { ?>
      <tr>
        <td><?php echo $entry_company ?></td>
        <td><input type="text" name="pno" alt="<?php echo $help_company_id ?>" value="<?php $company_id ?>" /></td>
      </tr>
      <?php } ?>
      <?php if ($country_code == 'DEU' || $country_code == 'NLD') { ?>
      <tr>
        <td><?php echo $entry_gender ?></td>
        <td><input type="radio" name="gender" value="1" id="male" />
          <label for="male"><?php echo $text_male; ?></label>
          <input type="radio" name="gender" value="0" id="female" />
          <label for="female"><?php echo $text_female; ?></label></td>
      </tr>
      <tr>
        <td><label for="input_street"><?php echo $entry_street ?></label></td>
        <td><input type="text" name="street" id="input_street" alt="<?php echo $help_street ?>" value="<?php echo $street ?>" /></td>
      </tr>
      <tr>
        <td><label for="input_house_no"><?php echo $entry_house_no ?></label></td>
        <td><input type="text" name="house_no" id="input_house_no" alt="<?php echo $help_house_number ?>" value="<?php echo $street_number ?>" /></td>
      </tr>
      <?php } ?>
      <?php if ($country_code == 'NLD') { ?>
      <tr>
        <td><label for="input_house_ext"><?php echo $entry_house_ext ?></label></td>
        <td><input type="text" name="house_ext" id="input_house_ext" alt="<?php echo $help_house_extension ?>" value="<?php echo $street_extension ?>" /></td>
      </tr>
      <?php } ?>
      <tr>
        <td><label for="input_phone_no"><?php echo $entry_phone_no ?></label></td>
        <td><input type="text" name="phone_no" id="input_phone_no" alt="<?php echo $help_phone_number ?>" value="<?php echo $phone_number ?>" /></td>
      </tr>
      <?php if ($country_code == 'DEU') { ?>
      <tr>
        <td colspan="2"><input type="checkbox" name="deu_toc" value="1" />
          
          <!-- No point moving this to language file as this is displayed only to German customers --> 
          Mit der Übermittlung der für die Abwicklung des Rechnungskaufes und einer Identitäts - und Bonitätsprüfung erforderlichen 
          Daten an Klarna bin ich einverstanden. Meine <a href="https://online.klarna.com/consent_de.yaws" target="_blank">Einwilligung</a> kann ich jederzeit mit Wirkung für die Zukunft widerrufen. </td>
      </tr>
      <?php } ?>
    </table>
  </div>
</form>
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
<?php } else { ?>
<div class="warning"> <?php echo $error_address_match ?> </div>
<?php } ?>
<script type="text/javascript">
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
    
    $('#button-confirm').bind('click', function() {

        $('.warning, .error').remove();

        <?php if ($country_code == 'DEU') { ?>
        if (!$('input[name="deu_toc"]').is(':checked')) {
            $('#payment').before("<div class=\"warning\"><?php echo $error_deu_toc ?></div>");
            return;
        }
        
        <?php } ?>

        $.ajax({
            url: 'index.php?route=payment/klarna_invoice/send',
            type: 'post',
            data: $('#klarna-payment-form').serialize(),
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
                if (json['error']) {
                    $('#payment').before('<div class="warning">' + json['error'] + '</div>');
                }

                if (json['redirect']) {
                    location = json['redirect'];
                }
            }
        });
    });

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


</script>