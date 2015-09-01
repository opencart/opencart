<footer>
  <div class="container">
    <div class="row">
      <?php if ($informations) { ?>
      <div class="col-sm-3">
        <h5><?php echo $text_information; ?></h5>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
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
    </div>
    <hr>

            <div class="nav pull-right">
            <ul class="list-inline">
          <?php if ($followme_facebook) { ?><li><a href="http://facebook.com/<?php echo $followme_facebook; ?>"  target="_blank" title="<?php echo $text_follow_us; ?>&nbsp;<?php echo $name; ?>&nbsp;<?php echo $text_followmeon; ?>&nbsp;<?php echo $text_facebook; ?>"><i class="fa fa-facebook"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_facebook; ?></span></a></li><?php } ?>
          <?php if ($followme_twitter) { ?><li><a href="http://twitter.com/<?php echo $followme_twitter; ?>"  target="_blank" title="<?php echo $text_follow_us; ?>&nbsp;<?php echo $name; ?>&nbsp;<?php echo $text_followmeon; ?>&nbsp;<?php echo $text_twitter; ?>"><i class="fa fa-twitter"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_twitter; ?></span></a></li><?php } ?>
          <?php if ($followme_pinterest) { ?><li><a href="http://pinterest.com/<?php echo $followme_pinterest; ?>"  target="_blank" title="<?php echo $text_follow_us; ?>&nbsp;<?php echo $name; ?>&nbsp;<?php echo $text_followmeon; ?>&nbsp;<?php echo $text_pinterest; ?>"><i class="fa fa-pinterest"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_pinterest; ?></span></a></li><?php } ?>
          <?php if ($followme_google) { ?><li><a href="http://plus.google.com/<?php echo $followme_google; ?>"  target="_blank" title="<?php echo $text_follow_us; ?>&nbsp;<?php echo $name; ?>&nbsp;<?php echo $text_followmeon; ?>&nbsp;<?php echo $text_google; ?>"><i class="fa fa-google"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_google; ?></span></a></li><?php } ?>
          <?php if ($followme_linkedin) { ?><li><a href="http://linkedin.com/<?php echo $followme_linkedin; ?>"  target="_blank" title="<?php echo $text_follow_us; ?>&nbsp;<?php echo $name; ?>&nbsp;<?php echo $text_followmeon; ?>&nbsp;<?php echo $text_linkedin; ?>"><i class="fa fa-linkedin"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_linkedin; ?></span></a></li><?php } ?>
          </ul>
          </div>
            
    <p><?php echo $powered; ?></p> 
  </div>
</footer>

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//--> 

<!-- Theme created by Welford Media for OpenCart 2.0 www.welfordmedia.co.uk -->

	
					<script src="catalog/view/javascript/backtotop/js/easing.js" type="text/javascript"></script>
					<script src="catalog/view/javascript/backtotop/js/jquery.ui.totop.min.js" type="text/javascript"></script>
					<script type="text/javascript">
						$(document).ready(function() {
							/*
							var defaults = {
								containerID: 'toTop', // fading element id
								containerHoverID: 'toTopHover', // fading element hover id
								scrollSpeed: 1200,
								easingType: 'linear' 
							};
							*/
							
							$().UItoTop({ easingType: 'easeOutQuart' });
							
						});
					</script>
				
</body></html>