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
        <li><a href="<?php echo $payment; ?>">{{ text_payment }}</a></li>
      </ul>
      <h2>{{ text_my_tracking }}</h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $tracking; ?>">{{ text_tracking }}</a></li>
      </ul>
      <h2>{{ text_my_transactions }}</h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $transaction; ?>">{{ text_transaction }}</a></li>
      </ul>
      {{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }}