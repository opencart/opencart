<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="heading">
  <h1><?php echo $heading_title; ?></h1>
  <div class="buttons"><a onclick="openMyModal('<?php echo $invoice; ?>');" class="button"><span class="button_left button_invoice"></span><span class="button_middle"><?php echo $button_invoice; ?></span><span class="button_right"></span></a><a onclick="iprint('<?php echo $invoice; ?>'); " class="button"><span class="button_left button_print"></span><span class="button_middle"><?php echo $button_print; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span class="button_left button_back"></span><span class="button_middle"><?php echo $button_back; ?></span><span class="button_right"></span></a></div>
</div>
<div id="invoice">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
    <table>
      <thead>
        <tr>
          <td align="center" colspan="4"><?php echo $text_order_details; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td width="25%"><b><?php echo $text_order; ?></b></td>
          <td width="25%"><b><?php echo $text_date_added; ?></b></td>
          <td width="25%"><b><?php echo $text_payment_method; ?></b></td>
          <td width="25%"><b><?php echo $text_shipping_method; ?></b></td>
        </tr>
        <tr>
          <td><?php echo $order_id; ?></td>
          <td><?php echo $date_added; ?></td>
          <td><?php echo $payment_method; ?></td>
          <td><?php echo $shipping_method; ?></td>
        </tr>
      </tbody>
    </table>
    <table>
      <thead>
        <tr>
          <td align="center" colspan="3"><?php echo $text_contact_details; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td width="33.3%"><b><?php echo $text_email; ?></b></td>
          <td width="33.3%"><b><?php echo $text_telephone; ?></b></td>
          <td width="33.3%"><b><?php echo $text_fax; ?></b></td>
        </tr>
        <tr>
          <td><?php echo $email; ?></td>
          <td><?php echo $telephone; ?></td>
          <td><?php echo $fax; ?></td>
        </tr>
      </tbody>
    </table>
    <table>
      <thead>
        <tr>
          <td align="center" colspan="2"><?php echo $text_address_details; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><b><?php echo $text_payment_address; ?></b></td>
          <td><b><?php echo $text_shipping_address; ?></b></td>
        </tr>
        <tr>
          <td><?php echo $payment_address; ?></td>
          <td><?php echo $shipping_address; ?></td>
        </tr>
      </tbody>
    </table>
    <table>
      <thead>
        <tr>
          <td align="center" colspan="5"><?php echo $text_products; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
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
            <?php } ?>
          </td>
          <td><?php echo $product['model']; ?></td>
          <td align="right"><?php echo $product['quantity']; ?></td>
          <td align="right"><?php if (!$product['discount']) { ?>
            <?php echo $product['price']; ?>
            <?php } else { ?>
            <span class="price_old"><?php echo $product['price']; ?></span><br />
            <span class="price_new"><?php echo $product['discount']; ?></span>
            <?php } ?></td>
          <td align="right"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td align="right" colspan="4"><b><?php echo $total['title']; ?></b></td>
          <td align="right"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php if ($downloads) { ?>
    <table>
      <thead>
        <tr>
          <td align="center" colspan="3"><b><?php echo $text_downloads; ?></b></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><b><?php echo $column_download; ?></b></td>
          <td><b><?php echo $column_filename; ?></b></td>
          <td align="right"><b><?php echo $column_remaining; ?></b></td>
        </tr>
        <?php foreach ($downloads as $download) { ?>
        <tr>
          <td><?php echo $download['name']; ?></td>
          <td><?php echo $download['filename']; ?></td>
          <td align="right"><?php echo $download['remaining']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <?php } ?>
    <table>
      <thead>
        <tr>
          <td align="center" colspan="3"><?php echo $text_order_history; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($historys as $history) { ?>
        <tr>
          <td><b><?php echo $text_date_added; ?></b></td>
          <td><b><?php echo $text_status; ?></b></td>
          <td><b><?php echo $text_notify; ?></b></td>
        </tr>
        <tr>
          <td><?php echo $history['date_added']; ?></td>
          <td><?php echo $history['status']; ?></td>
          <td><?php echo $history['notify']; ?></td>
        </tr>
        <tr>
          <td colspan="3"><b><?php echo $text_comment; ?></b></td>
        </tr>
        <tr>
          <td colspan="3"><?php echo ($history['comment'] ? $history['comment'] : '&nbsp;'); ?></td>
        </tr>
        <tr>
          <td colspan="3">&nbsp;</td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    <table>
      <thead>
        <tr>
          <td align="center" colspan="2"><?php echo $text_update; ?></td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td><b><?php echo $entry_status; ?></b></td>
          <td><b><?php echo $entry_notify; ?></b></td>
        </tr>
        <tr>
          <td><select name="order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
          <td><?php if ($notify) { ?>
            <input type="checkbox" name="notify" value="1" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="notify" value="1" />
            <?php } ?></td>
        </tr>
        <tr>
          <td colspan="2"><b><?php echo $entry_comment; ?></b></td>
        </tr>
        <tr>
          <td colspan="2"><textarea name="comment" cols="40" rows="8" style="width: 99%"></textarea></td>
        </tr>
        <tr>
          <td align="right" colspan="2"><a onclick="$('#form').submit();" class="button"><span class="button_left button_save"></span><span class="button_middle"><?php echo $button_save; ?></span><span class="button_right"></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button" style="margin-left: 5px;"><span class="button_left button_cancel"></span><span class="button_middle"><?php echo $button_cancel; ?></span><span class="button_right"></span></a></td>
        </tr>
      </tbody>
    </table>
  </form>
</div>
<script type="text/javascript" src="view/javascript/jquery/modal/modal.js"></script>
<link rel="stylesheet" type="text/css" href="view/javascript/jquery/modal/modal.css" />
<script type="text/javascript"><!--
function openMyModal(source) {   
    modalWindow.windowId = 'myModal';   
    modalWindow.width    = 700;   
    modalWindow.height   = 600;   
    modalWindow.content  = '<iframe name="invoice" width="700" height="570" frameborder="0" scrolling="auto" allowtransparency="true" src="' + source + '"></iframe><div style="background: #303F4A; padding: 8px; text-align: right;"><a onclick="modalWindow.close();" style="color: #FFFFFF;"><u><?php echo $text_close; ?></u></a></div>';   
    modalWindow.open();   
};  

function iprint(source) {
	openMyModal(source);
	frames['invoice'].focus(); 
	frames['invoice'].print(); 
} 
//--></script>