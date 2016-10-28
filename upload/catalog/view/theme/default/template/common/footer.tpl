<footer>
  <div id="info" class="container">
    <div class="row">
      <?php if ($informations) { ?>
      <?php /*
       <div class="col-sm-3">
      */ ?>

      <div class="col-sm-12">
        <?php /*>
        <h5><?php echo $text_information; ?></h5>
        <*/ ?>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
          <li><a href="<?php echo $contact ?>">Contact Us</a></li>
        </ul>
      </div>
      <?php } ?>

      <?php /*>
      <div class="col-sm-3">
        <h5><?php echo $text_service; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_extra; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-3">
        <h5><?php echo $text_account; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
      </div>
     <*/ ?>

    </div>

    <?php /*>
    <hr>
    <p><?php echo $powered; ?></p>
    <*/ ?>

    <ul class="list-unstyled">
      <li><a href="http://facebook.com/xlightca" target="_blank"><i class="fa fa-facebook"></i></a></li>
      <li><a href="http://twitter.com/xlightca" target="_blank"><i class="fa fa-twitter"></i></a></li>
      <li><a href="https://instagram.com/xlightca" target="_blank"><i class="fa fa-instagram"></i></a></li>
      <!-- change the link terry 2016.09.12 start-->
      <li class="pull-right">Xlight © 2016 | <a href="http://bbs.xlight.ca/sso" target="_blank" class="sso"><i class="fa fa-commenting"></i>XFans</a></li>
      <!-- change the link terry 2016.09.12 end-->
    </ul>
  </div>
</footer>


</body></html>