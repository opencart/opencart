<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <table class="list">
    <thead>
      <tr>
        <td class="left" colspan="2"><?php echo $text_order_detail; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="left" style="width: 50%;"><?php if ($invoice_no) { ?>
          <b><?php echo $text_invoice_no; ?></b> <?php echo $invoice_no; ?><br />
          <?php } ?>
          <b><?php echo $text_order_id; ?></b> #<?php echo $order_id; ?><br />
          <b><?php echo $text_date_added; ?></b> <?php echo $date_added; ?></td>
        <td class="left"><b><?php echo $text_payment_method; ?></b> <?php echo $payment_method; ?><br />
          <?php if ($shipping_method) { ?>
          <b><?php echo $text_shipping_method; ?></b> <?php echo $shipping_method; ?>
          <?php } ?></td>
      </tr>
    </tbody>
  </table>
  <table class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $text_payment_address; ?></td>
        <?php if ($shipping_address) { ?>
        <td class="left"><?php echo $text_shipping_address; ?></td>
        <?php } ?>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="left"><?php echo $payment_address; ?></td>
        <?php if ($shipping_address) { ?>
        <td class="left"><?php echo $shipping_address; ?></td>
        <?php } ?>
      </tr>
    </tbody>
  </table>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="order">
    <table class="list">
      <thead>
        <tr>
          <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
          <td class="left"><?php echo $column_name; ?></td>
          <td class="left"><?php echo $column_model; ?></td>
          <td class="right"><?php echo $column_quantity; ?></td>
          <td class="right"><?php echo $column_price; ?></td>
          <td class="right"><?php echo $column_total; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td style="text-align: center; vertical-align: middle;"><?php if ($product['selected']) { ?>
            <input type="checkbox" name="selected[]" value="<?php echo $product['order_product_id']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="selected[]" value="<?php echo $product['order_product_id']; ?>" />
            <?php } ?></td>
          <td class="left"><?php echo $product['name']; ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?></td>
          <td class="left"><?php echo $product['model']; ?></td>
          <td class="right"><?php echo $product['quantity']; ?></td>
          <td class="right"><?php echo $product['price']; ?></td>
          <td class="right"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <tfoot>
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td colspan="4"></td>
          <td class="right"><b><?php echo $total['title']; ?>:</b></td>
          <td class="right"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </tfoot>
    </table>
    <div class="buttons">
      <div class="right"><?php echo $text_action; ?>
        <select name="action" onchange="$('#order').submit();">
          <option value=""><?php echo $text_selected; ?></option>
          <option value="reorder"><?php echo $text_reorder; ?></option>
          <option value="return"><?php echo $text_return; ?></option>
        </select>
      </div>
    </div>
  </form>
  <?php if ($comment) { ?>
  <table class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $text_comment; ?></td>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td class="left"><?php echo $comment; ?></td>
      </tr>
    </tbody>
  </table>
  <?php } ?>
  <?php if ($histories) { ?>
  <h2><?php echo $text_history; ?></h2>
  <table class="list">
    <thead>
      <tr>
        <td class="left"><?php echo $column_date_added; ?></td>
        <td class="left"><?php echo $column_status; ?></td>
        <td class="left"><?php echo $column_comment; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($histories as $history) { ?>
      <tr>
        <td class="left"><?php echo $history['date_added']; ?></td>
        <td class="left"><?php echo $history['status']; ?></td>
        <td class="left"><?php echo $history['comment']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <?php } ?>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_continue; ?></span></a></div>
  </div>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?> 