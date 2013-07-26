<?php if ($error_warning) { ?>
<div class="alert alert-warning"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<div class="row-fluid">
  <div class="span12">
    <?php if ($shipping_methods) { ?>
    <p><?php echo $text_shipping_method; ?></p>
    <?php foreach ($shipping_methods as $shipping_method) { ?>
    <p><strong><?php echo $shipping_method['title']; ?></strong></p>
    <p>
      <?php if (!$shipping_method['error']) { ?>
      <?php foreach ($shipping_method['quote'] as $quote) { ?>
    <p>
      <label class="radio">
        <?php if ($quote['code'] == $code || !$code) { ?>
        <?php $code = $quote['code']; ?>
        <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" checked="checked" />
        <?php } else { ?>
        <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" />
        <?php } ?>
        <?php echo $quote['title']; ?> - <?php echo $quote['text']; ?></label>
    </p>
    <?php } ?>
    <?php } else { ?>
    <div class="alert alert-error"><?php echo $shipping_method['error']; ?></div>
    <?php } ?>
    <?php } ?>
    <?php } ?>
    <p><strong><?php echo $text_comments; ?></strong></p>
    <p>
      <textarea name="comment" rows="8" style="width: 98%"><?php echo $comment; ?></textarea>
    </p>
    <div class="buttons">
      <div class="pull-right">
        <input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
      </div>
    </div>
  </div>
</div>
