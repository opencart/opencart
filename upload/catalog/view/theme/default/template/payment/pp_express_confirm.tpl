<?php echo $header; ?>
<div class="container">

  <?php if ($attention) { ?>
  <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>

  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?></h1>

      <?php if ($coupon || $voucher || $reward || $shipping) { ?>
      <h2><?php echo $text_next; ?></h2>
      <p><?php echo $text_next_choice; ?></p>
      <div class="panel-group" id="accordion"><?php echo $coupon; ?></div>
      <?php } ?>
    
      <?php if($has_shipping) { ?>
        <?php if(!isset($shipping_methods)) { ?>
          <div class="warning"><?php echo $error_no_shipping ?></div>
        <?php } else { ?>
          <form action="<?php echo $action_shipping; ?>" method="post" id="shipping_form">
            <div class="panel-body">
                <?php foreach ($shipping_methods as $shipping_method) { ?>
                  <p><strong><?php echo $shipping_method['title']; ?></strong></p>

                  <?php if (!$shipping_method['error']) { ?>
                    <?php foreach ($shipping_method['quote'] as $quote) { ?>
                      <div class="radio">
                        <label>
                          <?php if ($quote['code'] == $code || !$code) { ?>
                            <?php $code = $quote['code']; ?>
                            <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked" />
                          <?php } else { ?>
                            <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
                          <?php } ?>
                          <?php echo $quote['title']; ?>
                        </label>
                      </div>
                    <?php } ?>
                  <?php } else { ?>
                    <div class="warning"><?php echo $shipping_method['error']; ?></div>
                  <?php } ?>
              <?php } ?>
            </div>
          </form>
        <?php } ?>
      <?php } ?>


      <div class="table-responsive">
    <table class="table table-bordered">
      <thead>
      <tr>
        <td class="text-left"><?php echo $column_name; ?></td>
        <td class="text-left"><?php echo $column_model; ?></td>
        <td class="text-center"><?php echo $column_quantity; ?></td>
        <td class="text-right"><?php echo $column_price; ?></td>
        <td class="text-right"><?php echo $column_total; ?></td>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($products as $product) { ?>
        <?php if ($product['recurring']): ?>
          <tr>
            <td colspan="5" style="border:none;">
              <image src="catalog/view/theme/default/image/reorder.png" alt="" title="" style="float:left;" /><span style="float:left;line-height:18px; margin-left:10px;">
              <strong><?php echo $text_recurring_item ?></strong>
              <?php echo $product['profile_description'] ?>
            </td>
          </tr>
        <?php endif; ?>
      <tr>
        <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          <small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?>
          <?php if ($product['recurring']): ?>
          <br />
          <small> - <?php echo $text_payment_profile ?>: <?php echo $product['profile_name'] ?></small>
          <?php endif; ?>
        </td>
        <td class="text-left"><?php echo $product['model']; ?></td>
        <td class="text-center"><?php echo $product['quantity']; ?></td>
        <td class="text-right"><?php echo $product['price']; ?></td>
        <td class="text-right"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td class="text-left"><?php echo $voucher['description']; ?></td>
        <td class="text-left"></td>
        <td class="text-center">1</td>
        <td class="text-right"><?php echo $voucher['amount']; ?></td>
        <td class="text-right"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
      </tbody>
      <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td colspan="4" class="text-right"><b><?php echo $total['title']; ?>:</b></td>
        <td class="text-right"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
      </tfoot>
    </table>
  </div>

      <div class="buttons">
        <div class="pull-right"><a href="<?php echo $action_confirm; ?>" class="btn btn-primary"><?php echo $button_confirm; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>

<script type="text/javascript"><!--
$("input[name='shipping_method']").change( function() {
  $('#shipping_form').submit();
});

$('input[name=\'next\']').bind('change', function() {
  $('.cart-discounts > div').hide();

  $('#' + this.value).show();
});
//--></script>