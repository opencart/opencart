<div style="text-align: right; clear: both;">
  <form action="<?php echo $action; ?>" method="post" id="payment">
    <input type="hidden" name="cart" value="<?php echo $cart; ?>">
    <input type="hidden" name="signature" value="<?php echo $signature; ?>">
    <input type="image" name="Google Checkout" alt="Fast checkout through Google" src="<?php echo $button; ?>" height="46" width="180">
  </form>
</div>
