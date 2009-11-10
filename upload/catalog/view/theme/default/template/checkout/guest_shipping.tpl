<?php if ($methods) { ?>
<b style="margin-bottom: 2px; display: block;"><?php echo $text_shipping_method; ?></b>
<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
  <p><?php echo $text_shipping_methods; ?></p>
  <table width="536" cellpadding="3">
    <?php foreach ($methods as $method) { ?>
    <tr>
      <td colspan="3"><b><?php echo $method['title']; ?></b></td>
    </tr>
    <?php if (!$method['error']) { ?>
    <?php foreach ($method['quote'] as $quote) { ?>
    <tr>
      <td width="1"><label for="<?php echo $quote['id']; ?>">
          <?php if ($quote['id'] == $default) { ?>
          <input type="radio" name="shipping_method" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>" checked="checked" style="margin: 0px;" />
          <?php } else { ?>
          <input type="radio" name="shipping_method" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>" style="margin: 0px;" />
          <?php } ?>
        </label></td>
      <td><label for="<?php echo $quote['id']; ?>" style="cursor: pointer;"><?php echo $quote['title']; ?></label></td>
      <td align="right"><label for="<?php echo $quote['id']; ?>" style="cursor: pointer;"><?php echo $quote['text']; ?></label></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td colspan="2"><div class="error"><?php echo $method['error']; ?></div></td>
    </tr>
    <?php } ?>
    <?php } ?>
  </table>
</div>
<?php } ?>
