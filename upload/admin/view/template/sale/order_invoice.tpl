<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $language; ?>" xml:lang="<?php echo $language; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<link rel="stylesheet" type="text/css" href="view/stylesheet/invoice.css" />
</head>
<body>
<h1><?php echo $text_invoice; ?></h1>
<div class="div1">
  <table width="100%">
    <tr>
      <td><?php echo $store; ?><br />
        <?php echo $address; ?><br />
        <?php echo $text_telephone; ?> <?php echo $telephone; ?><br />
        <?php if ($fax) { ?>
        <?php echo $text_fax; ?> <?php echo $fax; ?><br />
        <?php } ?>
        <?php echo $email; ?><br />
        <?php echo $website; ?></td>
      <td align="right" valign="top"><table>
          <tr>
            <td><b><?php echo $text_invoice_date; ?></b></td>
            <td><?php echo $date_added; ?></td>
          </tr>
          <tr>
            <td><b><?php echo $text_invoice_no; ?></b></td>
            <td><?php echo $order_id; ?></td>
          </tr>
        </table></td>
    </tr>
  </table>
</div>
<table class="address">
  <tr class="heading">
    <td width="50%"><b><?php echo $text_to; ?></b></td>
    <td width="50%"><b><?php echo $text_ship_to; ?></b></td>
  </tr>
  <tr>
    <td><?php echo $payment_address; ?></td>
    <td><?php echo $shipping_address; ?></td>
  </tr>
</table>
<table class="product">
  <tr class="heading">
    <td><b><?php echo $column_product; ?></b></td>
    <td><b><?php echo $column_model; ?></b></td>
    <td align="right"><b><?php echo $column_quantity; ?></b></td>
    <td align="right"><b><?php echo $column_price; ?></b></td>
    <td align="right"><b><?php echo $column_total; ?></b></td>
  </tr>
  <?php foreach ($products as $product) { ?>
  <tr>
    <td><?php echo $product['name']; ?>
      <?php foreach ($product['option'] as $option) { ?>
      <br />
      &nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
      <?php } ?></td>
    <td><?php echo $product['model']; ?></td>
    <td align="right"><?php echo $product['quantity']; ?></td>
    <td align="right"><?php echo $product['price']; ?></td>
    <td align="right"><?php echo $product['total']; ?></td>
  </tr>
  <?php } ?>
  <?php foreach ($totals as $total) { ?>
  <tr>
    <td align="right" colspan="4"><b><?php echo $total['title']; ?></b></td>
    <td align="right"><?php echo $total['text']; ?></td>
  </tr>
  <?php } ?>
</table>
</body>
</html>