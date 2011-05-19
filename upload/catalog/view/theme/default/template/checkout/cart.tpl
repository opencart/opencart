<?php echo $header; ?>
<div class="container"><?php echo $column_left; ?><?php echo $column_right; ?>
  <div id="content"><?php echo $content_top; ?>
    <div class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
      <?php } ?>
    </div>
    <h1><?php echo $heading_title; ?>
      <?php if ($weight) { ?>
      &nbsp;(<?php echo $weight; ?>)
      <?php } ?>
    </h1>
    <?php if ($attention) { ?>
    <div class="attention"><?php echo $attention; ?></div>
    <?php } ?>    
    <?php if ($success) { ?>
    <div class="success"><?php echo $success; ?></div>
    <?php } ?>
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="basket">
      <div class="cart-info">
        <table>
          <thead>
            <tr>
              <td class="remove"><?php echo $column_remove; ?></td>
              <td class="image"><?php echo $column_image; ?></td>
              <td class="name"><?php echo $column_name; ?></td>
              <td class="model"><?php echo $column_model; ?></td>
              <td class="quantity"><?php echo $column_quantity; ?></td>
              <td class="price"><?php echo $column_price; ?></td>
              <td class="total"><?php echo $column_total; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) { ?>
            <tr>
              <td class="remove"><input type="checkbox" name="remove[]" value="<?php echo $product['key']; ?>" /></td>
              <td class="image"><?php if ($product['thumb']) { ?>
                <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" /></a>
                <?php } ?></td>
              <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                <?php if (!$product['stock']) { ?>
                <span class="stock">***</span>
                <?php } ?>
                <div>
                  <?php foreach ($product['option'] as $option) { ?>
                  - <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small><br />
                  <?php } ?>
                </div>
                <?php if ($product['points']) { ?>
                <small><?php echo $product['points']; ?></small>
                <?php } ?></td>
              <td class="model"><?php echo $product['model']; ?></td>
              <td class="quantity"><input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="3" /></td>
              <td class="price"><?php echo $product['price']; ?></td>
              <td class="total"><?php echo $product['total']; ?></td>
            </tr>
            <?php } ?>
            <?php foreach ($vouchers as $voucher) { ?>
            <tr>
              <td class="remove"><input type="checkbox" name="voucher[]" value="<?php echo $voucher['key']; ?>" /></td>
              <td class="image"></td>
              <td class="name"><?php echo $voucher['description']; ?></td>
              <td class="model"></td>
              <td class="quantity">1</td>
              <td class="price"><?php echo $voucher['amount']; ?></td>
              <td class="total"><?php echo $voucher['amount']; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </form>
    <div class="cart-module">
      <?php foreach ($modules as $module) { ?>
      <?php echo $module; ?>
      <?php } ?>
    </div>
    <div class="cart-total">
      <table>
        <?php foreach ($totals as $total) { ?>
        <tr>
          <td colspan="5"></td>
          <td class="right"><b><?php echo $total['title']; ?></b></td>
          <td class="right"><?php echo $total['text']; ?></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <div class="buttons">
      <div class="left"><a onclick="$('#basket').submit();" class="button"><span><?php echo $button_update; ?></span></a></div>
      <div class="right"><a href="<?php echo $checkout; ?>" class="button"><span><?php echo $button_checkout; ?></span></a></div>
      <div class="center"><a href="<?php echo $continue; ?>" class="button"><span><?php echo $button_shopping; ?></span></a></div>
    </div>
    <?php echo $content_bottom; ?></div>
</div>
<script type="text/javascript"><!--
$('.cart-module .cart-heading').bind('click', function() {
	if ($(this).hasClass('active')) {
		$(this).removeClass('active');
	} else {
		$(this).addClass('active');
	}
		
	$(this).parent().find('.cart-content').slideToggle('slow');
});
//--></script> 
<?php echo $footer; ?>