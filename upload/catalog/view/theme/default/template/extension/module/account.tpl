<div class="list-group">
  <?php if (!$logged) { ?>
  <a href="<?php echo $login; ?>" class="list-group-item">{{ text_login; ?></a> <a href="<?php echo $register; ?>" class="list-group-item">{{ text_register }}</a> <a href="<?php echo $forgotten; ?>" class="list-group-item"><?php echo $text_forgotten }}</a>
  <?php } ?>
  <a href="<?php echo $account; ?>" class="list-group-item">{{ text_account }}</a>
  <?php if ($logged) { ?>
  <a href="<?php echo $edit; ?>" class="list-group-item">{{ text_edit }}</a> <a href="{{ password }}" class="list-group-item">{{ text_password }}</a>
  <?php } ?>
  <a href="<?php echo $address; ?>" class="list-group-item">{{ text_address }}</a> <a href="<?php echo $wishlist; ?>" class="list-group-item">{{ text_wishlist; ?></a> <a href="<?php echo $order; ?>" class="list-group-item">{{ text_order }}</a> <a href="{{ download }}" class="list-group-item">{{ text_download }}</a><a href="<?php echo $recurring; ?>" class="list-group-item">{{ text_recurring }}</a> <a href="<?php echo $reward; ?>" class="list-group-item">{{ text_reward }}</a> <a href="<?php echo $return; ?>" class="list-group-item">{{ text_return }}</a> <a href="<?php echo $transaction; ?>" class="list-group-item">{{ text_transaction }}</a> <a href="<?php echo $newsletter; ?>" class="list-group-item"><?php echo $text_newsletter }}</a>
  <?php if ($logged) { ?>
  <a href="<?php echo $logout; ?>" class="list-group-item">{{ text_logout }}</a>
  <?php } ?>
</div>
