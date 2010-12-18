    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" id="checkout">
      <input type="hidden" name="sid" value="<?php echo $sid; ?>" />
      <input type="hidden" name="total" value="<?php echo $total; ?>" />
      <input type="hidden" name="cart_order_id" value="<?php echo $cart_order_id; ?>" />
      <input type="hidden" name="card_holder_name" value="<?php echo $card_holder_name; ?>" />
      <input type="hidden" name="street_address" value="<?php echo $street_address; ?>" />
      <input type="hidden" name="city" value="<?php echo $city; ?>" />
      <input type="hidden" name="state" value="<?php echo $state; ?>" />
      <input type="hidden" name="zip" value="<?php echo $zip; ?>" />
      <input type="hidden" name="country" value="<?php echo $country; ?>" />
      <input type="hidden" name="email" value="<?php echo $email; ?>" />
      <input type="hidden" name="phone" value="<?php echo $phone; ?>" />
      <input type="hidden" name="ship_street_address" value="<?php echo $ship_street_address; ?>" />
      <input type="hidden" name="ship_city" value="<?php echo $ship_city; ?>" />
      <input type="hidden" name="ship_state" value="<?php echo $ship_state; ?>" />
      <input type="hidden" name="ship_zip" value="<?php echo $ship_zip; ?>" />
      <input type="hidden" name="ship_country" value="<?php echo $ship_country; ?>" />
      <?php $i = 0; ?>
      <?php foreach ($products as $product) { ?>
      <input type="hidden" name="c_prod_<?php echo $i; ?>" value="<?php echo $product['product_id']; ?>,<?php echo $product['quantity']; ?>" />
      <input type="hidden" name="c_name_<?php echo $i; ?>" value="<?php echo $product['name']; ?>" />
      <input type="hidden" name="c_description_<?php echo $i; ?>" value="<?php echo $product['description']; ?>" />
      <input type="hidden" name="c_price_<?php echo $i; ?>" value="<?php echo $product['price']; ?>" />
      <?php $i++; ?>
      <?php } ?>
      <input type="hidden" name="id_type" value="1" />
      <?php if (isset($demo)) { ?>
      <input type="hidden" name="demo" value="<?php echo $demo; ?>" />
      <?php } ?>
      <input type="hidden" name="lang" value="<?php echo $lang; ?>" />
      <input type="hidden" name="return_url" value="<?php echo $return_url; ?>" />
    </form>
    <div class="buttons">
      <table>
        <tr>
          <td align="left"><a onclick="location = '<?php echo str_replace('&', '&amp;', $back); ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
          <td align="right"><a onclick="$('#checkout').submit();" class="button"><span><?php echo $button_confirm; ?></span></a></td>
        </tr>
      </table>
    </div>
