<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
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
      <h1><?php echo $heading_title; ?>
        <?php /*
        <?php if ($weight) { ?>
        &nbsp;(<?php echo $weight; ?>)
        <?php } ?>
        */?>

      </h1>
      <form id="cart_form" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-center"><?php echo $column_image; ?></td>
                <td class="text-left"><?php echo $column_name; ?></td>
                <td class="text-left"><?php echo $column_model; ?></td>
                <td class="text-left"><?php echo $column_quantity; ?></td>
                <td class="text-right"><?php echo $column_price; ?></td>
                <td class="text-right"><?php echo $column_total; ?></td>
                <td class="text-right"><?php echo $column_operation ?></td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($products as $product) { ?>
              <tr>
                <td class="text-center"><?php if ($product['thumb']) { ?>
                  <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                  <?php } ?></td>
                <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                  <?php if (!$product['stock']) { ?>
                  <span class="text-danger">***</span>
                  <?php } ?>
                  <?php if ($product['option']) { ?>
                  <?php foreach ($product['option'] as $option) { ?>
                  <br />
                  <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                  <?php } ?>
                  <?php } ?>
                  <!--<?php if ($product['reward']) { ?>
                  <br />
                  <small><?php echo $product['reward']; ?></small>
                  <?php } ?>-->
                  <?php if ($product['recurring']) { ?>
                  <br />
                  <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
                  <?php } ?></td>
                <td class="text-left"><?php echo $product['model']; ?></td>
                <td class="text-left"><div class="btn-block">
                    <div class="input-group spinner" data-trigger="spinner">
                      <input type="text" name="quantity[<?php echo $product['cart_id']; ?>]" class="form-control text-center" value="<?php echo $product['quantity']; ?>" data-rule="quantity" data-min="1" data-max="999">
                      <div class="input-group-addon">
                        <a href="javascript:;" type="submit" class="spin-up" data-spin="up"><i class="fa fa-caret-up"></i></a>
                        <a href="javascript:;" type="submit" class="spin-down" data-spin="down"><i class="fa fa-caret-down"></i></a>
                      </div>
                    </div>
                    <span class="unit_desc"><?php echo $text_unit; ?></span>

                    <?php /*
                    <input type="number" min="1" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="form-control" />
                    */ ?>
                    <span class="input-group-btn">

                    <?php /*
                    <button type="submit" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></button>
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"><i class="fa fa-times-circle"></i></button>
                    */ ?>

                    </span>
                </td>
                <td class="text-right">
                  <?php if($product['original_price'] != $product['price'] ) { ?>
                  <span class="text-decoration">
                    <?php echo $product['original_price']; ?>
                  </span>
                  <br />
                  <?php } ?>
                  <span ><?php echo $product['price']; ?></span>
                </td>
                <td class="text-right text-danger"><?php echo $product['total']; ?></td>
                <td class="text-right">
                  <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"><i class="fa fa-times-circle"></i></button>
                </td>
              </tr>
              <?php } ?>
              <?php foreach ($vouchers as $voucher) { ?>
              <tr>
                <td class="text-left"><?php echo $voucher['description']; ?></td>
                <td class="text-left"></td>
                <td class="text-left"><div class="input-group btn-block" style="max-width: 200px;">
                    <input type="text" name="" value="1" size="1" disabled="disabled" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger" onclick="voucher.remove('<?php echo $voucher['key']; ?>');"><i class="fa fa-times-circle"></i></button>
                    </span></div></td>
                <td class="text-right"><?php echo $voucher['amount']; ?></td>
                <td class="text-right"><?php echo $voucher['amount']; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </form>
      <!--Coupon-->
      <?php if ($modules) { ?>
      <h2>
        <?php /* echo $text_next;*/ ?>
      </h2>
      <p><?php echo $text_next_choice; ?></p>
      <div class="panel-group" id="accordion">
        <?php foreach ($modules as $module) { ?>
        <?php echo $module; ?>
        <?php } ?>
      </div>
      <?php } ?>
      <!---->
      <br />
      <div class="row">
        <div class="col-sm-5 col-sm-offset-7">
          <table class="table" id="total">
            <?php foreach ($totals as $total) { ?>
            <?php if ($total['code'] != "sub_total") { ?>
            <tr class="<?php echo $total['class'] ?>">
              <td class="text-right"><?php echo $total['title']; ?>:</td>
              <td class="text-right"><?php echo $total['text']; ?></td>
            </tr>
            <?php } else { ?>
            <tr class="<?php echo $total['class'] ?>">
              <td class="text-right"><?php echo $total['title']; ?>:</td>
              <td class="text-right">
                <?php if($product['original_price'] != $product['price'] ) { ?>
                <span class="text-decoration">
                  <?php echo $total['addin'] ?>
                </span>
                <br>
                <?php } ?>
                <span ><?php echo $total['text']; ?></span>
              </td>
            </tr>
            <?php } ?>
            <?php } ?>
          </table>
        </div>
      </div>
      <div class="buttons">
        <div class="pull-left"><a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_shopping; ?></a></div>
        <div class="pull-right"><a href="<?php echo $checkout; ?>" class="btn btn-primary"><?php echo $button_checkout; ?></a></div>
      </div>
      <!--Accepted Payment Methods-->
      <div class="row accepted-pay">
        <div class="col-md-4 col-md-offset-8">
          <div class="panel panel-default">
            <!-- Default panel contents -->
            <div class="panel-heading"><?php echo $text_accepted_payment_methods; ?></div>
            <div class="panel-body">
              <ul class="list-unstyled payment-methods">
                <li>
                  <img src="<?php echo HTTPS_SERVER; ?>/catalog/view/theme/default/image/PayPal.png" />
                </li>
                <li>
                  <img src="<?php echo HTTPS_SERVER; ?>/catalog/view/theme/default/image/visa.png" />
                </li>
                <li>
                  <img src="<?php echo HTTPS_SERVER; ?>/catalog/view/theme/default/image/mastercard.png" />
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
      <!--The Xlight Guarantee-->
      <div class="row gurantee">
        <div class="col-md-4 col-md-offset-8">
          <h4><b><?php echo $text_guarantee; ?></b></h4>
          <div class="panel panel-default">
            <!--Default panel contens-->
            <div class="panel-heading">
              <a href="javascript:void(0);"><?php echo $text_free_return; ?><i class="fa fa-plus pull-right"></i></a>
            </div>
            <div class="panel-body">
              <?php echo $text_free_return_content; ?>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-md-offset-8">
          <div class="panel panel-default">
            <!--Default panel contens-->
            <div class="panel-heading">
              <a href="javascript:void(0);"><?php echo $text_safe_secured; ?><i class="fa fa-plus pull-right"></i></a>
            </div>
            <div class="panel-body">
              <?php echo $text_safe_secured_content; ?>
            </div>
          </div>
        </div>
        <div class="col-md-4 col-md-offset-8">
          <div class="panel panel-default">
            <!--Default panel contens-->
            <div class="panel-heading">
              <a href="javascript:void(0);"><?php echo $text_instant_help; ?><i class="fa fa-plus pull-right"></i></a>
            </div>
            <div class="panel-body">
              <?php echo $text_instant_help_content; ?>
            </div>
          </div>
        </div>
      </div>

      <?php /*
      <img class="pull-right" src="<?php echo HTTPS_SERVER; ?>/catalog/view/theme/default/image/Norton-Secure-Logo.jpg" />
      */?>
      <?php echo $content_bottom; ?></div>
      <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
<script>
  $(function () {
    $(".spinner").spinner('changed',function (e,newVal,oldVal) {
      $('#cart_form').submit();
    });

    $('.gurantee .panel-heading > a').click(function () {
      if($(this).find('i').hasClass('fa-plus')) {
        $(this).parent().next('div.panel-body').fadeOut("normal");
        $(this).find('i').removeClass('fa-plus');
        $(this).find('i').addClass('fa-minus');
      } else {
        $(this).parent().next('div.panel-body').fadeIn("normal");
        $(this).find('i').removeClass('fa-minus');
        $(this).find('i').addClass('fa-plus');
      }
    });
  });
</script>