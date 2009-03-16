<div class="div1">
  <div class="div2"><a href="<?php echo $home; ?>"><img src="catalog/view/theme/default/image/logo.png" title="<?php echo $store; ?>" alt="<?php echo $store; ?>" /></a></div>
  <div class="div3"><?php echo $language; ?><?php echo $search; ?></div>
</div>
<div class="div4"><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
  <?php if (!$logged) { ?>
  <a href="<?php echo $login; ?>"><?php echo $text_login; ?></a>
  <?php } else { ?>
  <a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a>
  <?php } ?>
  <a href="<?php echo $cart; ?>"><?php echo $text_cart; ?></a><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></div>
