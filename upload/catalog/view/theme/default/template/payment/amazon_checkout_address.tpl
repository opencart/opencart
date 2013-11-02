<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <h2><?php echo $heading_address; ?></h2>
    <div style="float: left" id="amazon-address-widget"></div>
    <div style="float: left; width: 58%" class="shipping-methods"></div>
    <div style="clear: both;"></div>
    <div class="buttons" style="margin-top: 15px">
      <a href="<?php echo $cart ?>" class="button left"><span><?php echo $text_cart ?></span></a>
      <a class="button right" id="continue-button"><span><?php echo $text_continue ?></span></a>
    </div>
    <input type="hidden" name="addressSelected" value="0" />
  <?php echo $content_bottom; ?>
</div>
<script type="text/javascript"><!--
  $(document).ready(function(){
    $('#continue-button').click(function(){
      $('div.warning').remove();

      if ($('input[name="addressSelected"]').val() == '0') {
        $('#amazon-address-widget').before('<div class="warning"><?php echo $error_shipping_address ?></div>');
      } else if($('input[name="shipping_method"]:checked').length == 0) {
        $('#amazon-address-widget').before('<div class="warning"><?php echo $error_shipping ?></div>');
      } else {
        $.ajax({
          url: 'index.php?route=payment/amazon_checkout/set_shipping',
          type: 'post',
          data: $('input[name="shipping_method"]:checked'),
          dataType: 'json',
          success: function(json) {
            location = json['redirect'];
          },
          error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        });
      }
    });

    new CBA.Widgets.AddressWidget({
      merchantId: '<?php echo $merchant_id ?>',
      displayMode: 'edit',
      onAddressSelect: function(widget) {
        $('input[name="addressSelected"]').val('1');
        $('div.warning').remove();
        $('div.shipping-methods').html('');

        $.get('<?php echo $shipping_quotes ?>', {}, function(data) {

          $('.shipping-methods').html('');

          if (data.error) {
            $('#amazon-address-widget').before('<div class="warning">' + data.error + '</div>');
          } elseif (data.quotes) {
            var html = '';
            html += '<table class="radio">';

            $.each(data.quotes, function(code, shippingMethod){
              html += '<tr><td colspan="3"><b>' + shippingMethod.title + '</b></td></tr>';

              $.each(shippingMethod.quote, function(i, quote){
                html += '<tr>';

                if (data.selected == quote.code) {
                  html += ' <td><input type="radio" name="shipping_method" value="' + quote.code + '" id="' + quote.code + '" checked="checked" /></td>';
                } else {
                  html += ' <td><input type="radio" name="shipping_method" value="' + quote.code + '" id="' + quote.code + '" /></td>';
                }

                html += ' <td><label for="' + quote.code + '">' + quote.title + '</label></td>';
                html += ' <td style="text-align: right;"><label for="' + quote.code + '">' + quote.text + '</label></td>';
                html += '</tr>';
              });
            });

            html += '</table>';
            $('.shipping-methods').html(html);

            if ($('input[name="shipping_method"]:checked').length == 0) {
              $('input[name="shipping_method"]:first').attr('checked', 'checked');
            }
          }
        }, 'json');
      }
    }).render('amazon-address-widget');
  });
//--></script>
<?php echo $footer; ?>