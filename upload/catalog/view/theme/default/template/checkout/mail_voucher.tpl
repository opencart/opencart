<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/1999/REC-html401-19991224/strict.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title><?php echo $title; ?></title>
</head>
<body>
<table style="font-family: Verdana,sans-serif; font-size: 11px; color: #374953; width: 600px;">
  <tr>
    <td align="left"><a href="<?php echo $store_url; ?>" title="<?php echo $store_name; ?>"><img src="<?php echo $logo; ?>" alt="<?php echo $store_name; ?>" style="border: none;" ></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left"><?php echo $mail_greeting; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left" style="background-color: #069; color: #FFF; font-size: 12px; font-weight: bold; padding: 0.5em 1em;"><?php echo $mail_order_detail; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left"><?php echo $mail_order_id; ?> <span style="color: #069; font-weight: bold;"><?php echo $order_id; ?></span><br />
      <?php echo $mail_date_added; ?> <?php echo $date_added; ?><br >
      <?php echo $mail_payment_method; ?> <strong><?php echo $payment_method; ?></strong><br />
      <?php echo $mail_shipping_method; ?> <strong><?php echo $shipping_method; ?></strong><br />
	  <br />
	  <?php echo $mail_email; ?> <strong><?php echo $email; ?></strong><br />
	  <?php echo $mail_telephone; ?> <strong><?php echo $telephone; ?></strong><br />
	  <?php echo $mail_ip; ?> <strong><?php echo $ip; ?></strong>
	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td><table style="width: 100%; font-family: Verdana,sans-serif; font-size: 11px; color: #FFFFFF;">
        <tr style="background-color: #CCCCCC; text-transform: uppercase;">
          <th style="text-align: left; padding: 0.3em;"><?php echo $mail_shipping_address; ?></th>
          <th style="text-align: left; padding: 0.3em;"><?php echo $mail_payment_address; ?></th>
        </tr>
        <tr>
          <td style="padding: 0.3em; background-color: #EEEEEE; color: #000;"><?php echo $shipping_address; ?></td>
          <td style="padding: 0.3em; background-color: #EEEEEE; color: #000;"><?php echo $payment_address; ?></td>
        </tr>
      </table></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left" style="background-color: #069; color: #FFF; font-size: 12px; font-weight: bold; padding: 0.5em 1em;"></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <?php if ($comment) { ?>
  <tr>
    <td align="left" style="background-color: #069; color: #FFF; font-size: 12px; font-weight: bold; padding: 0.5em 1em;"><?php echo $mail_comment; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left"><?php echo $comment; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <?php } ?>
  <?php if ($customer_id) { ?>
  <tr>
    <td align="left" style="background-color: #069; color: #FFF; font-size: 12px; font-weight: bold; padding: 0.5em 1em;"><?php echo $mail_invoice; ?></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <tr>
    <td align="left"><a href="<?php echo $invoice; ?>"><?php echo $invoice; ?></a></td>
  </tr>
  <tr>
    <td>&nbsp;</td>
  </tr>
  <?php } ?>
  <tr>
    <td align="center" style="font-size: 10px; border-top: 1px solid #069;"><a href="<?php echo $store_url; ?>" style="color: #069; font-weight: bold; text-decoration: none;"><?php echo $store_name; ?></a> <?php echo $mail_powered_by; ?> <a href="http://www.opencart.com" style="text-decoration: none; color: #374953;">OpenCart</a></td>
  </tr>
</table>
</body>
</html>
