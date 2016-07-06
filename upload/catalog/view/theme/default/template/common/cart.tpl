<div id="cart" class="btn-group btn-block">
  <button type="button" data-toggle="dropdown" data-loading-text="{{ text_loading }}" class="btn btn-inverse btn-block btn-lg dropdown-toggle"><i class="fa fa-shopping-cart"></i> <span id="cart-total">{{ text_items }}</span></button>
  <ul class="dropdown-menu pull-right">
    {% if products || $vouchers) { ?>
    <li>
      <table class="table table-striped">
        <?php foreach ($products as $product) { ?>
        <tr>
          <td class="text-center">{% if product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
            <?php } ?></td>
          <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
            {% if product['option']) { ?>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small>
            <?php } ?>
            <?php } ?>
            {% if product['recurring']) { ?>
            <br />
            - <small>{{ text_recurring }} <?php echo $product['recurring']; ?></small>
            <?php } ?></td>
          <td class="text-right">x <?php echo $product['quantity']; ?></td>
          <td class="text-right"><?php echo $product['total']; ?></td>
          <td class="text-center"><button type="button" onclick="cart.remove('<?php echo $product['cart_id']; ?>');" title="{{ button_remove }}" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td class="text-center"></td>
          <td class="text-left"><?php echo $voucher['description']; ?></td>
          <td class="text-right">x&nbsp;1</td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
          <td class="text-center text-danger"><button type="button" onclick="voucher.remove('<?php echo $voucher['key']; ?>');" title="{{ button_remove }}" class="btn btn-danger btn-xs"><i class="fa fa-times"></i></button></td>
        </tr>
        <?php } ?>
      </table>
    </li>
    <li>
      <div>
        <table class="table table-bordered">
          <?php foreach ($totals as $total) { ?>
          <tr>
            <td class="text-right"><strong><?php echo $total['title']; ?></strong></td>
            <td class="text-right"><?php echo $total['text']; ?></td>
          </tr>
          <?php } ?>
        </table>
        <p class="text-right"><a href="{{ history }}"><strong><i class="fa fa-shopping-cart"></i> {{ text_cart }}</strong></a>&nbsp;&nbsp;&nbsp;<a href="{{ checkout }}"><strong><i class="fa fa-share"></i> {{ text_checkout }}</strong></a></p>
      </div>
    </li>
    <?php } else { ?>
    <li>
      <p class="text-center">{{ text_empty }}</p>
    </li>
    <?php } ?>
  </ul>
</div>
