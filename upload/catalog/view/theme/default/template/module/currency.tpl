<div class="box">
  <div class="top"><img src="catalog/view/theme/default/image/icon_currency.png" alt="" /><?php echo $heading_title; ?></div>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="currency_form">
    <div class="middle" style="text-align: center;">
      <select name="currency_code" onchange="$('#currency_form').submit();">
        <?php foreach ($currencies as $currency) { ?>
        <?php if ($currency['status']) { ?>
        <?php if ($currency['code'] == $default) { ?>
        <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
        <?php } ?>
        <?php } ?>
        <?php } ?>
      </select>
      <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
    </div>
  </form>
  <div class="bottom">&nbsp;</div>
</div>
