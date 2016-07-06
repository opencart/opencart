<footer>
  <div class="container">
    <div class="row">
      <?php if ($informations) { ?>
      <div class="col-sm-3">
        <h5>{{ text_information }}</h5>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="col-sm-3">
        <h5>{{ text_service }}</h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $contact; ?>">{{ text_contact }}</a></li>
          <li><a href="<?php echo $return; ?>">{{ text_return }}</a></li>
          <li><a href="<?php echo $sitemap; ?>">{{ text_sitemap }}</a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5>{{ text_extra }}</h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $manufacturer; ?>">{{ text_manufacturer }}</a></li>
          <li><a href="<?php echo $voucher; ?>">{{ text_voucher }}</a></li>
          <li><a href="<?php echo $affiliate; ?>">{{ text_affiliate }}</a></li>
          <li><a href="<?php echo $special; ?>">{{ text_special }}</a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5>{{ text_account }}</h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>">{{ text_account }}</a></li>
          <li><a href="<?php echo $order; ?>">{{ text_order }}</a></li>
          <li><a href="<?php echo $wishlist; ?>">{{ text_wishlist }}</a></li>
          <li><a href="<?php echo $newsletter; ?>">{{ text_newsletter }}</a></li>
        </ul>
      </div>
    </div>
    <hr>
    <p><?php echo $powered; ?></p>
  </div>
</footer>

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->

<!-- Theme created by Welford Media for OpenCart 2.0 www.welfordmedia.co.uk -->

</body></html>