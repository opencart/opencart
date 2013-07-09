<?php if ($error_warning) { ?>
<div class="alert alert-warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($shipping_methods) { ?>
<p><?php echo $text_shipping_method; ?></p>

<div class="shipping-options">
  <?php foreach ($shipping_methods as $shipping_method) { ?>

  <p><strong><?php echo $shipping_method['title']; ?></strong></p>

  <?php if (!$shipping_method['error']) { ?>
  <?php foreach ($shipping_method['quote'] as $quote) { ?>

    <label class="radio" for="<?php echo $quote['code']; ?>">
        <?php if ($quote['code'] == $code || !$code) { ?>
            <?php $code = $quote['code']; ?>
            <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked" />
        <?php } else { ?>
            <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
        <?php } ?>

        <?php echo $quote['title']; ?> - <?php echo $quote['text']; ?>
    </label>

  <?php } ?>
  <?php } else { ?>

    <div class="alert alert-error"><?php echo $shipping_method['error']; ?></div>

  <?php } ?>
  <?php } ?>
</div>

<?php } ?>
<strong><?php echo $text_comments; ?></strong>
<textarea name="comment" rows="8" style="width: 98%;"><?php echo $comment; ?></textarea>
<br />
<br />
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-method" class="btn" />
  </div>
  <div class="clearfix"></div>
</div>
