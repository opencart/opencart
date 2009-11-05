<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <h1><?php echo $heading_title; ?></h1>
  </div>
  <div class="middle" style="padding-bottom: 1px;">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="cart">
      <table class="cart">
        <tr>
          <th align="center"><?php echo $column_remove; ?></th>
          <th align="center"><?php echo $column_image; ?></th>
          <th align="left"><?php echo $column_name; ?></th>
          <th align="left"><?php echo $column_model; ?></th>
          <th align="right"><?php echo $column_quantity; ?></th>
          <th align="right"><?php echo $column_price; ?></th>
          <th align="right"><?php echo $column_total; ?></th>
        </tr>
        <?php $class = 'odd'; ?>
        <?php foreach ($products as $product) { ?>
        <?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
        <tr class="<?php echo $class; ?>">
          <td align="center"><input type="checkbox" name="remove[<?php echo $product['key']; ?>]" /></td>
          <td align="center"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" /></a></td>
          <td align="left" valign="top"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
            <?php if (!$product['stock']) { ?>
            <span style="color: #FF0000; font-weight: bold;">***</span>
            <?php } ?>
            <div>
              <?php foreach ($product['option'] as $option) { ?>
              - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
              <?php } ?>
            </div></td>
          <td align="left" valign="top"><?php echo $product['model']; ?></td>
          <td align="right" valign="top"><input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="3" /></td>
          <td align="right" valign="top"><?php echo $product['price']; ?></td>
          <td align="right" valign="top"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <tr>
          <td colspan="7" align="right"><b><?php echo $text_sub_total; ?></b> <?php echo $sub_total; ?></td>
        </tr>
      </table>
      <div class="buttons">
        <table>
          <tr>
            <td align="left"><a onclick="$('#cart').submit();" class="button"><span><?php echo $button_update; ?></span></a></td>
            <td align="center"><a onclick="location='<?php echo $continue; ?>'" class="button"><span><?php echo $button_shopping; ?></span></a></td>
            <td align="right"><a onclick="location='<?php echo $checkout; ?>'" class="button"><span><?php echo $button_checkout; ?></span></a></td>
          </tr>
        </table>
      </div>
    </form>
    <div style="width: 558px; display: inline-block;">
      <div style="float: left; width: 274px;">
        <div style="border: 1px solid #DDDDDD; min-height: 125px;">
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="coupon">
            <div style="background: #F7F7F7 url('catalog/view/theme/default/image/icon_discount.png') 8px 8px no-repeat; border-bottom: 1px solid #DDDDDD; padding: 8px 8px 8px 29px;"><span style="text-transform: uppercase; font-size: 14px; font-weight: bold;">Discount Coupon</span></div>
            <div style="padding: 8px;"><?php echo $text_coupon; ?><br />
              <input type="text" name="coupon" value="<?php echo $coupon; ?>" />
              <a onclick="$('#coupon').submit();" class="button"><span><?php echo $button_coupon; ?></span></a></div>
          </form>
        </div>
      </div>
    </div>
    <script type="text/javascript"><!--
	$('#shipping').bind('click', function (e) {
		$('#test').slideToggle('slow');									  
	});
	//--></script>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
<?php echo $footer; ?> 