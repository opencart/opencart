<div class="box">
  <div class="top"><img src="catalog/view/theme/default/image/icon_basket.png" alt="" /><?php echo $heading_title; ?></div>
  <div class="middle">
    <?php if ($products) { ?>
    <table cellpadding="2" cellspacing="0" style="width: 100%;">
      <?php foreach ($products as $product) { ?>
      <tr>
        <td valign="top" align="right"><?php echo $product['quantity']; ?>&nbsp;x&nbsp;</td>
        <td align="left" valign="top"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <div>
            <?php foreach ($product['option'] as $option) { ?>
            - <small style="color: #999;"><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
            <?php } ?>
          </div></td>
      </tr>
      <?php } ?>
    </table>
    <br />
    <div style="text-align: right;"><?php echo $text_subtotal; ?>&nbsp;<?php echo $subtotal; ?></div>
    <?php } else { ?>
    <div style="text-align: center;"><?php echo $text_empty; ?></div>
    <?php } ?>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
