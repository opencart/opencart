<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
    <table width="100%">
      <tr>
        <td valign="top"><b><?php echo $text_order; ?></b><br />
          #<?php echo $order_id; ?><br />
          <br />
          <b><?php echo $text_email; ?></b><br />
          <?php echo $email; ?><br />
          <br />
          <b><?php echo $text_telephone; ?></b><br />
          <?php echo $telephone; ?><br />
          <br />
          <?php if ($fax) { ?>
          <b><?php echo $text_fax; ?></b><br />
          <?php echo $fax; ?><br />
          <br />
          <?php } ?>
          <?php if ($shipping_method) { ?>
          <b><?php echo $text_shipping_method; ?></b><br />
          <?php echo $shipping_method; ?><br />
          <br />
          <?php } ?>
          <b><?php echo $text_payment_method; ?></b><br />
          <?php echo $payment_method; ?></td>
        <td valign="top"><?php if ($shipping_address) { ?>
          <b><?php echo $text_shipping_address; ?></b><br />
          <?php echo $shipping_address; ?><br />
          <?php } ?></td>
        <td valign="top"><b><?php echo $text_payment_address; ?></b><br />
          <?php echo $payment_address; ?><br /></td>
      </tr>
    </table>
  </div>
  <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
    <table width="100%">
      <tr>
        <th align="left"><?php echo $text_product; ?></th>
        <th align="left"><?php echo $text_model; ?></th>
        <th align="right"><?php echo $text_quantity; ?></th>
        <th align="right"><?php echo $text_price; ?></th>
        <th align="right"><?php echo $text_total; ?></th>
      </tr>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td align="left" valign="top"><?php echo $product['name']; ?>
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
    </div>
  </div>
  <?php if ($comment) { ?>
  <b style="margin-bottom: 3px; display: block;"><?php echo $text_comment; ?></b>
  <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><?php echo $comment; ?></div>
  <?php } ?>
  <b style="margin-bottom: 3px; display: block;"><?php echo $text_order_history; ?></b>
  <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
    <table width="100%">
      <tr>
        <th align="left"><?php echo $column_date_added; ?></th>
        <th align="left"><?php echo $column_status; ?></th>
        <th align="left"><?php echo $column_comment; ?></th>
      </tr>
      <?php foreach ($historys as $history) { ?>
      <tr>
        <td valign="top"><?php echo $history['date_added']; ?></td>
        <td valign="top"><?php echo $history['status']; ?></td>
        <td valign="top"><?php echo $history['comment']; ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
  <div class="buttons">
    <table>
      <tr>
        <td align="right"><a onclick="location='<?php echo $continue; ?>'" class="button"><span><?php echo $button_continue; ?></span></a></td>
      </tr>
    </table>
  </div>
</div>
<div class="bottom"></div>
