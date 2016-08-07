<?php if ($error_warning) { ?>
<div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<p><?php echo $text_payment_method; ?></p>
<?php foreach ($payment_methods as $payment_method) { ?>
<div class="radio">
  <label>
    <?php if ($payment_method['code'] == $code || !$code) { ?>
    <?php $code = $payment_method['code']; ?>
    <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" checked="checked" />
    <?php } else { ?>
    <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" />
    <?php } ?>
    <?php echo $payment_method['title']; ?>
    <!--<?php if ($payment_method['terms']) { ?>
    (<?php echo $payment_method['terms']; ?>)
    <?php } ?>-->
  </label>
</div>
<?php } ?>
<?php } ?>

<?php if ($modules) { ?>
  <h2><?php echo $text_next; ?></h2>
  <p><?php echo $text_next_choice; ?></p>
  <ul class="nav nav-tabs" role="tablist">
    <?php foreach ($module_names as $module_name) { ?>
      <li role="presentation">
        <a href="#<?php echo $module_name; ?>" aria-controls="home" role="tab" data-toggle="tab" aria-expanded="false"><?php echo $module_name; ?></a>
      </li>
    <?php } ?>
  </ul>
  <div class="tab-content">
    <?php foreach ($modules as $module) { ?>
      <?php echo $module; ?>
    <?php } ?>
  </div>
<?php } ?>
<br />
<p><strong><?php echo $text_comments; ?></strong></p>
<p>
  <textarea name="comment" rows="8" class="form-control"><?php echo $comment; ?></textarea>
</p>
<?php if ($text_agree) { ?>
<div class="buttons">
  <div class="pull-right"><?php echo $text_agree; ?>
    <?php if ($agree) { ?>
    <input type="checkbox" name="agree" value="1" checked="checked" />
    <?php } else { ?>
    <input type="checkbox" name="agree" value="1" />
    <?php } ?>
    &nbsp;
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<?php } else { ?>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
  </div>
</div>
<?php } ?>
<script>
  $(function () {
    // tab,默认选中第一个
    $('ul.nav-tabs > li:first').addClass('active');
    $('.tab-content > [class="tab-pane"]').removeClass('active');
    $('.tab-content > [class="tab-pane"]:first').addClass('active');
  })
</script>