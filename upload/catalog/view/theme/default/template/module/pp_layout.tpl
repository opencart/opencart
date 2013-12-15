<a href="<?php echo $payment_url; ?>" title="PayPal Express Checkout" style="text-decoration:none;">
    <?php if($is_mobile == true) { ?>
        <img src="catalog/view/theme/default/image/paypal_express_mobile.png" style="margin:10px; float:right;" alt="PayPal Express Checkout" title="PayPal Express Checkout" />
    <?php }else{ ?>
        <img src="https://www.paypalobjects.com/en_GB/i/btn/btn_xpressCheckout.gif" alt="PayPal Express Checkout" style="float:right;" />
    <?php } ?>
</a>