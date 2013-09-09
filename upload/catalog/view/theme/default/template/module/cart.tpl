<div id="cart" class="btn-group btn-block"><a class="btn btn-default btn-block dropdown-toggle" data-toggle="dropdown"><i class="icon-shopping-cart"></i><span class="text-items" id="cart-total"> <?php echo $text_items; ?></span></a>
  <div id="dropdown-menu" class="dropdown-menu">
    <?php if ($products || $vouchers) { ?>
    <table class="table table-bordered">
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="text-center"><?php if ($product['thumb']) { ?>
          <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
          <?php } ?></td>
        <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php if ($product['option']) { ?>
          <br />
          <?php foreach ($product['option'] as $option) { ?>
          - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
          <?php } ?>
          <?php } ?></td>
        <td class="text-right">x <?php echo $product['quantity']; ?></td>
        <td class="text-right"><?php echo $product['total']; ?></td>
        <td class="text-center"><button type="button" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $product['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $product['key']; ?>' + ' #cart > *');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-sm"><i class="icon-remove-sign"></i></button></td>
      </tr>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td class="text-center"></td>
        <td class="text-left"><?php echo $voucher['description']; ?></td>
        <td class="text-right">x&nbsp;1</td>
        <td class="text-right"><?php echo $voucher['amount']; ?></td>
        <td class="text-center text-danger"><button type="button" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $voucher['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $voucher['key']; ?>' + ' #cart > *');" title="<?php echo $button_remove; ?>" class="btn btn-sm"><i class="icon-remove-sign"></i></button></td>
      </tr>
      <?php } ?>
      <?php } ?>
    </table>
    <table class="table table-bordered">
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td><?php echo $total['title']; ?></td>
        <td><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </table>
    <div>
      <div class="pull-left"><a href="<?php echo $cart; ?>"><i class="icon-shopping-cart"></i> <?php echo $text_cart; ?></a> </div>
      <div class="pull-right"><a href="<?php echo $checkout; ?>"><i class="icon-share-alt"></i> <?php echo $text_checkout; ?></a> </div>
    </div>
    <?php } else { ?>
    <p><?php echo $text_empty; ?></p>
    <?php } ?>
  </div>
</div>
