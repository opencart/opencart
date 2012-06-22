<form action="<?php echo $action; ?>" method="post">
  <input type="hidden" name="instId" value="<?php echo $merchant; ?>" />
  <input type="hidden" name="cartId" value="<?php echo $order_id; ?>" />
  <input type="hidden" name="amount" value="<?php echo $amount; ?>" />
  <input type="hidden" name="currency" value="<?php echo $currency; ?>" />
  <input type="hidden" name="desc" value="<?php echo $description; ?>" />
  <input type="hidden" name="name" value="<?php echo $name; ?>" />
  <input type="hidden" name="address" value="<?php echo $address; ?>" />
  <input type="hidden" name="postcode" value="<?php echo $postcode; ?>" />
  <input type="hidden" name="country" value="<?php echo $country; ?>" />
  <input type="hidden" name="tel" value="<?php echo $telephone; ?>" />
  <input type="hidden" name="email" value="<?php echo $email; ?>" />
  <input type="hidden" name="testMode" value="<?php echo $test; ?>" />
  <div class="buttons">
    <div class="right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="button" />
    </div>
  </div>
</form>
