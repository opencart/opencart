{{ header }}
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> {{ success }}</div>
  <?php } ?>
  <div class="row">{{ column_left }}
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="{{ class }}">{{ content_top }}
      <h2>{{ text_my_account }}</h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $edit; ?>">{{ text_edit }}</a></li>
        <li><a href="{{ password }}">{{ text_password }}</a></li>
        <li><a href="<?php echo $address; ?>">{{ text_address }}</a></li>
        <li><a href="<?php echo $wishlist; ?>">{{ text_wishlist }}</a></li>
      </ul>
      <?php if ($credit_cards) { ?>
      <h2>{{ text_credit_card }}</h2>
      <ul class="list-unstyled">
        <?php foreach ($credit_cards as $credit_card) { ?>
        <li><a href="<?php echo $credit_card['href']; ?>"><?php echo $credit_card['name']; ?></a></li>
        <?php } ?>
      </ul>
      <?php } ?>
      <h2>{{ text_my_orders }}</h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $order; ?>">{{ text_order }}</a></li>
        <li><a href="{{ download }}">{{ text_download }}</a></li>
        <?php if ($reward) { ?>
        <li><a href="<?php echo $reward; ?>">{{ text_reward }}</a></li>
        <?php } ?>
        <li><a href="<?php echo $return; ?>">{{ text_return }}</a></li>
        <li><a href="<?php echo $transaction; ?>">{{ text_transaction }}</a></li>
        <li><a href="<?php echo $recurring; ?>">{{ text_recurring }}</a></li>
      </ul>
      <h2>{{ text_my_newsletter }}</h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $newsletter; ?>">{{ text_newsletter }}</a></li>
      </ul>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }} 