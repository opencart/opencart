<div class="box">
  <div class="top" style="background: url('catalog/view/image/icon_currency.png') 8px 8px no-repeat; padding-left: 30px;"><?php echo $heading_title; ?></div>
  <div class="middle" style="text-align: center;">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="currency_form">
      <select name="currency" onchange="$('#currency_form').submit();">
        <?php foreach ($currencies as $currency) { ?>
        <?php if ($currency['code'] == $default) { ?>
        <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
    </form>
  </div>
  <div class="bottom"></div>
</div>
