<?php if ($methods) { ?>
<b style="margin-bottom: 2px; display: block;"><?php echo $text_payment_method; ?></b>
<div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
  <p><?php echo $text_payment_methods; ?></p>
  <table width="536" cellpadding="3">
    <?php foreach ($methods as $method) { ?>
    <tr>
      <td width="1"><?php if ($method['id'] == $default) { ?>
        <input type="radio" name="payment_method" value="<?php echo $method['id']; ?>" id="<?php echo $method['id']; ?>" checked="checked" style="margin: 0px;" />
        <?php } else { ?>
        <input type="radio" name="payment_method" value="<?php echo $method['id']; ?>" id="<?php echo $method['id']; ?>" style="margin: 0px;" />
        <?php } ?></td>
      <td><label for="<?php echo $method['id']; ?>" style="cursor: pointer;"><?php echo $method['title']; ?></label></td>
    </tr>
    <?php } ?>
  </table>
</div>
<?php } ?>
