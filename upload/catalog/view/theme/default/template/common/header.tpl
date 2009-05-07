<div class="div1">
  <div class="div2"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $store; ?>" alt="<?php echo $store; ?>" /></a></div>
  <div class="div3"><?php echo $language; ?><?php echo $search; ?></div>
</div>
<div class="div4">
  <div class="div5"><a href="<?php echo $home; ?>"><img src="catalog/view/theme/default/image/icon_home.png" alt="" /><?php echo $text_home; ?></a><a href="<?php echo $special; ?>"><img src="catalog/view/theme/default/image/icon_special.png" alt="" /><?php echo $text_special; ?></a>
    <?php if (!$logged) { ?>
    <a href="<?php echo $login; ?>"><img src="catalog/view/theme/default/image/icon_login.png" alt="" /><?php echo $text_login; ?></a>
    <?php } else { ?>
    <a href="<?php echo $logout; ?>"><img src="catalog/view/theme/default/image/icon_logout.png" alt="" /><?php echo $text_logout; ?></a>
    <?php } ?>
    <a href="<?php echo $account; ?>"><img src="catalog/view/theme/default/image/icon_account.png" alt="" /><?php echo $text_account; ?></a></div>
  <div class="div6"><a href="<?php echo $checkout; ?>"><img src="catalog/view/theme/default/image/icon_checkout.png" alt="" /><?php echo $text_checkout; ?></a><a href="<?php echo $cart; ?>"><img src="catalog/view/theme/default/image/icon_basket.png" alt="" /><?php echo $text_cart; ?></a></div>
</div>
