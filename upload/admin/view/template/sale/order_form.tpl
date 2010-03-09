<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background-image: url('view/image/order.png');"><?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="window.open('<?php echo $invoice; ?>');" class="button"><span><?php echo $button_invoice; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button"><span><?php echo $button_back; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div style="margin-bottom: 15px;">
        <div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_order_details; ?></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
          <table class="list">
            <thead>
              <tr>
                <td class="left" width="25%"><b><?php echo $text_order; ?></b></td>
                <td class="left" width="25%"><b><?php echo $text_date_added; ?></b></td>
                <td class="left" width="25%"><b><?php echo $text_payment_method; ?></b></td>
                <td class="left" width="25%"><b><?php echo $text_shipping_method; ?></b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left"><?php echo $order_id; ?></td>
                <td class="left"><?php echo $date_added; ?></td>
                <td class="left"><?php echo $payment_method; ?></td>
                <td class="left"><?php echo $shipping_method; ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div style="margin-bottom: 15px;">
        <div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_contact_details; ?></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
          <table class="list">
            <thead>
              <tr>
                <td class="left" width="33.3%"><b><?php echo $text_email; ?></b></td>
                <td class="left" width="33.3%"><b><?php echo $text_telephone; ?></b></td>
                <td class="left" width="33.3%"><b><?php echo $text_fax; ?></b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left"><?php echo $email; ?></td>
                <td class="left"><?php echo $telephone; ?></td>
                <td class="left"><?php echo $fax; ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div style="margin-bottom: 15px;">
        <div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_address_details; ?></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
          <table class="list">
            <thead>
              <tr>
                <td class="left"><b><?php echo $text_payment_address; ?></b></td>
                <td class="left"><b><?php echo $text_shipping_address; ?></b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left"><?php echo $payment_address; ?></td>
                <td class="left"><?php echo $shipping_address; ?></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <div style="margin-bottom: 15px;">
        <div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_products; ?></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
          <table class="list">
            <thead>
              <tr>
                <td class="left"><b><?php echo $column_product; ?></b></td>
                <td class="left"><b><?php echo $column_model; ?></b></td>
                <td class="right"><b><?php echo $column_quantity; ?></b></td>
                <td class="right"><b><?php echo $column_price; ?></b></td>
                <td class="right"><b><?php echo $column_total; ?></b></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product) { ?>
              <tr>
                <td><?php echo $product['name']; ?>
                  <?php foreach ($product['option'] as $option) { ?>
                  <br />
                  &nbsp;<small> - <?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
                  <?php } ?></td>
                <td><?php echo $product['model']; ?></td>
                <td class="right"><?php echo $product['quantity']; ?></td>
                <td class="right"><?php echo $product['price']; ?></td>
                <td class="right" width="1"><?php echo $product['total']; ?></td>
              </tr>
              <?php } ?>
              <?php foreach ($totals as $total) { ?>
              <tr>
                <td class="right" colspan="4"><b><?php echo $total['title']; ?></b></td>
                <td class="right"><?php echo $total['text']; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php if ($order_comment) { ?>
      <div style="margin-bottom: 15px;">
        <div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_order_comment; ?></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
          <table class="list">
            <tr>
              <td class="left"><?php echo $order_comment; ?></td>
            </tr>
          </table>
        </div>
      </div>
      <?php } ?>
      <?php if ($downloads) { ?>
      <div style="margin-bottom: 15px;">
        <div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_downloads; ?></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
          <table class="list">
            <thead>
              <tr>
                <td class="left"><b><?php echo $column_download; ?></b></td>
                <td class="left"><b><?php echo $column_filename; ?></b></td>
                <td class="right"><b><?php echo $column_remaining; ?></b></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($downloads as $download) { ?>
              <tr>
                <td class="left"><?php echo $download['name']; ?></td>
                <td class="left"><?php echo $download['filename']; ?></td>
                <td class="right"><?php echo $download['remaining']; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
      <?php } ?>
      <div style="margin-bottom: 15px;">
        <div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_order_history; ?></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
          <table class="list">
            <?php foreach ($historys as $history) { ?>
            <thead>
              <tr>
                <td class="left"><b><?php echo $text_date_added; ?></b></td>
                <td class="left"><b><?php echo $text_status; ?></b></td>
                <td class="left"><b><?php echo $text_notify; ?></b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left"><?php echo $history['date_added']; ?></td>
                <td class="left"><?php echo $history['status']; ?></td>
                <td class="left"><?php echo $history['notify']; ?></td>
              </tr>
            </tbody>
            <thead>
              <tr>
                <td class="left" colspan="3"><b><?php echo $text_comment; ?></b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left" colspan="3"><?php echo ($history['comment'] ? $history['comment'] : '&nbsp;'); ?></td>
              </tr>
            </tbody>
            <?php } ?>
          </table>
        </div>
      </div>
      <div style="margin-bottom: 15px;">
        <div style="background: #547C96; color: #FFF; border-bottom: 1px solid #8EAEC3; padding: 5px; font-size: 14px; font-weight: bold;"><?php echo $text_update; ?></div>
        <div style="background: #FCFCFC; border: 1px solid #8EAEC3; padding: 10px;">
          <table class="list">
            <thead>
              <tr>
                <td class="left"><b><?php echo $entry_status; ?></b></td>
                <td class="left"><b><?php echo $entry_notify; ?></b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left"><select name="order_status_id">
                    <?php foreach ($order_statuses as $order_status) { ?>
                    <?php if ($order_status['order_status_id'] == $order_status_id) { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                    <?php } else { ?>
                    <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                    <?php } ?>
                  </select></td>
                <td class="left"><?php if ($notify) { ?>
                  <input type="checkbox" name="notify" value="1" checked="checked" />
                  <?php } else { ?>
                  <input type="checkbox" name="notify" value="1" />
                  <?php } ?></td>
              </tr>
            <thead>
              <tr>
                <td class="left" colspan="2"><b><?php echo $entry_comment; ?></b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td class="left" colspan="2"><textarea name="comment" cols="40" rows="8" style="width: 99%"><?php echo $comment; ?></textarea></td>
              </tr>
              <tr>
                <td class="right" colspan="2"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location='<?php echo $cancel; ?>';" class="button" style="margin-left: 5px;"><span><?php echo $button_cancel; ?></span></a></td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>