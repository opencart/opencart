<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error) { ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php } ?>
  <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
    <table width="100%">
      <tr>
        <td valign="top"><?php if ($shipping_method) { ?>
          <b><?php echo $text_shipping_method; ?></b><br />
          <?php echo $shipping_method; ?><br />
          <a href="<?php echo $checkout_shipping; ?>"><?php echo $text_change; ?></a><br />
          <br />
          <?php } ?>
          <b><?php echo $text_payment_method; ?></b><br />
          <?php echo $payment_method; ?><br />
          <a href="<?php echo $checkout_payment; ?>"><?php echo $text_change; ?></a></td>
        <td valign="top"><?php if ($shipping_address) { ?>
          <b><?php echo $text_shipping_address; ?></b><br />
          <?php echo $shipping_address; ?><br />
          <a href="<?php echo $checkout_shipping_address; ?>"><?php echo $text_change; ?></a>
          <?php } ?></td>
        <td><b><?php echo $text_payment_address; ?></b><br />
          <?php echo $payment_address; ?><br />
          <a href="<?php echo $checkout_payment_address; ?>"><?php echo $text_change; ?></a></td>
      </tr>
    </table>
  </div>
  <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
    <table width="100%">
      <tr>
        <th align="left"><?php echo $column_product; ?></th>
        <th align="left"><?php echo $column_model; ?></th>
        <th align="right"><?php echo $column_quantity; ?></th>
        <th align="right"><?php echo $column_price; ?></th>
        <th align="right"><?php echo $column_total; ?></th>
      </tr>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td align="left" valign="top"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
          <?php } ?></td>
        <td align="left" valign="top"><?php echo $product['model']; ?></td>
        <td align="right" valign="top"><?php echo $product['quantity']; ?></td>
        <td align="right" valign="top"><?php if (!$product['discount']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <u style="color: #F00; text-decoration: line-through;"><?php echo $product['price']; ?></u><br />
          <?php echo $product['discount']; ?>
          <?php } ?></td>
        <td align="right" valign="top"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
    </table>
    <br />
    <div style="width: 100%; display: inline-block;">
      <table style="float: right; display: inline-block;">
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td align="right"><?php echo $total['title']; ?></td>
          <td align="right"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </table>
      <br />
    </div>
  </div>
  <div class="buttons">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="coupon">
      <table width="100%" style="border-collapse: collapse;">
        <tr>
          <td><?php echo $entry_coupon; ?></td>
          <td class="right" width="1"><input type="text" name="coupon" value="<?php echo $coupon; ?>" /></td>
          <td class="right" width="1"><a onclick="$('#coupon').submit();" class="button"><span><?php echo $button_update; ?></span></a></td>
        </tr>
      </table>
    </form>
  </div>
  <?php if ($comment) { ?>
  <b style="margin-bottom: 3px; display: block;"><?php echo $text_comment; ?></b>
  <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><?php echo $comment; ?></div>
  <?php } ?>
  <div id="payment"><?php echo $payment; ?></div>
</div>
<div class="bottom">&nbsp;</div>
