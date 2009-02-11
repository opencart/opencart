<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php if ($error) { ?>
  <div class="warning"><?php echo $error; ?></div>
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
        <td align="right" valign="top"><?php if (!$product['discount']) { ?>
          <?php echo $product['price']; ?>
          <?php } else { ?>
          <span class="price_old"><?php echo $product['price']; ?></span><br />
          <span class="price_new"><?php echo $product['discount']; ?></span>
          <?php } ?></td>
        <td align="right" valign="top"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td colspan="7" align="right"><b><?php echo $text_subtotal; ?></b> <?php echo $subtotal; ?></td>
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
</div>
<div class="bottom"></div>
