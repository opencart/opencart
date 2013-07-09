<div id="cart" class="btn-group btn-block span pull-right">

    <a href="" class="btn btn-block btn-inverse dropdown-toggle" data-toggle="dropdown">
        <i class="icon-shopping-cart"></i><span class="text-items" id="cart-total"> <?php echo $text_items; ?></span>
    </a>

    <ul class="dropdown-menu pull-right" role="menu" aria-labelledby="dropdownMenu" id="dropdown-menu">
        <?php if ($products || $vouchers) { ?>

        <li>
            <table class="table">
                <?php foreach ($products as $product) { ?>
                <tr>
                    <td class="image">
                        <?php if ($product['thumb']) { ?>
                        <a href="<?php echo $product['href']; ?>">
                            <img class="thumbnail square" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
                        </a>
                        <?php } ?>
                    </td>
                    <td class="name">
                        <a href="<?php echo $product['href']; ?>">
                            <?php echo $product['name']; ?>
                        </a>
                        <div>
                            <?php foreach ($product['option'] as $option) { ?>
                            - <small><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
                            <?php } ?>
                        </div>
                    </td>
                    <td class="quantity">
                        x&nbsp;<?php echo $product['quantity']; ?>
                    </td>
                    <td class="total">
                        <?php echo $product['total']; ?>
                    </td>
                    <td class="remove">
                        <a onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $product['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $product['key']; ?>' + ' #cart > *');"><i class="icon-remove-sign text-error"></i></a>
                    </td>

                    <?php foreach ($vouchers as $voucher) { ?>
                    <tr>
                        <td class="image"></td>
                        <td class="name"><?php echo $voucher['description']; ?></td>
                        <td class="quantity">x&nbsp;1</td>
                        <td class="total"><?php echo $voucher['amount']; ?></td>
                        <td class="remove">
                            <img class="table-thumb" src="catalog/view/theme/default/image/remove-small.png" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" onclick="(getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') ? location = 'index.php?route=checkout/cart&remove=<?php echo $voucher['key']; ?>' : $('#cart').load('index.php?route=module/cart&remove=<?php echo $voucher['key']; ?>' + ' #cart > *');" />
                        </td>
                    </tr>
                    <?php } ?>

                </tr>
                <?php } ?>
            </table>
        </li>

        <li>
            <ul class="nav cart-totals">
                <?php foreach ($totals as $total) { ?>
                <li class="disabled">
                        <strong><?php echo $total['title']; ?>:</strong> 
                        <span><?php echo $total['text']; ?></span>
                </li>
                <?php } ?>
            </ul>
        </li>
            
        <li>
            <ul class="nav cart-btns pull-right clearfix">

                <li class="pull-left">
                    <a class="" href="<?php echo $cart; ?>"><i class="icon-shopping-cart"></i> <?php echo $text_cart; ?></a>
                </li>

                <li class="pull-left">
                    <a class="" href="<?php echo $checkout; ?>"><i class="icon-share-alt"></i> <?php echo $text_checkout; ?></a>
                </li>

            </ul>
        </li>

    </ul>

    <?php } else { ?>
        <li><span class="cart-empty"><?php echo $text_empty; ?></span></li>
    <?php } ?>

</div>