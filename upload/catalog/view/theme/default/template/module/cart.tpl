<div id="cart" class="btn-group btn-block">
  <button type="button" data-toggle="dropdown" class="btn btn-inverse btn-block btn-lg dropdown-toggle"><i class="fa fa-shopping-cart"></i> <span id="cart-total"><?php echo $text_items; ?></span></button>
  <div id="dropdown-menu" class="dropdown-menu pull-right">
    <div>
      <?php if ($products || $vouchers) { ?>
      <table class="table table-striped">
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
          <td class="text-center"><button type="button" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $product['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $product['key']; ?>' + ' #cart > *');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
        </tr>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td class="text-center"></td>
          <td class="text-left"><?php echo $voucher['description']; ?></td>
          <td class="text-right">x&nbsp;1</td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
          <td class="text-center text-danger"><button type="button" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $voucher['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $voucher['key']; ?>' + ' #cart > *');" title="<?php echo $button_remove; ?>" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
        </tr>
        <?php } ?>
        <?php } ?>
      </table>
      <div class="container">
        <div class="row">
          <div class="col-sm-7 col-sm-offset-5">
            <table class="table table-bordered">
              <?php foreach ($totals as $total) { ?>
              <tr>
                <td class="text-right"><strong><?php echo $total['title']; ?></strong></td>
                <td class="text-right"><?php echo $total['text']; ?></td>
              </tr>
              <?php } ?>
            </table>
            <p class="text-right"><a href="<?php echo $cart; ?>"><strong><i class="fa fa-shopping-cart"></i> <?php echo $text_cart; ?></strong></a>&nbsp;&nbsp;&nbsp;<a href="<?php echo $checkout; ?>"><strong><i class="fa fa-share"></i> <?php echo $text_checkout; ?></strong></a></p>
          </div>
        </div>
      </div>
      <?php } else { ?>
      <div class="container">
        <p><?php echo $text_empty; ?></p>
      </div>
      <?php } ?>
    </div>
  </div>
</div>
