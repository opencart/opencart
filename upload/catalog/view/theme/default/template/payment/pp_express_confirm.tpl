<?php echo $header; ?>

<?php if ($attention) { ?>
    <div class="attention"><?php echo $attention; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
<?php } ?>

<?php if ($success) { ?>
    <div class="success"><?php echo $success; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
<?php } ?>

<?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?><img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>
<?php } ?>

<?php echo $column_left; ?>

<?php echo $column_right; ?>

<div id="content">
    <?php echo $content_top; ?>
    <h1><?php echo $text_title; ?></h1>
        
    <?php if ($coupon_status || $voucher_status || $reward_status) { ?>
        <h2><?php echo $text_next; ?></h2>
        <div class="content">
            <p><?php echo $text_next_choice; ?></p>
            <table class="radio">
                <?php if ($coupon_status) { ?>
                    <tr class="highlight">
                        <td><?php if ($next == 'coupon') { ?>
                                <input type="radio" name="next" value="coupon" id="use_coupon" checked="checked" />
                            <?php } else { ?>
                                <input type="radio" name="next" value="coupon" id="use_coupon" />
                            <?php } ?></td>
                        <td><label for="use_coupon"><?php echo $text_use_coupon; ?></label></td>
                    </tr>
                <?php } ?>
                <?php if ($voucher_status) { ?>
                    <tr class="highlight">
                        <td><?php if ($next == 'voucher') { ?>
                                <input type="radio" name="next" value="voucher" id="use_voucher" checked="checked" />
                            <?php } else { ?>
                                <input type="radio" name="next" value="voucher" id="use_voucher" />
                            <?php } ?></td>
                        <td><label for="use_voucher"><?php echo $text_use_voucher; ?></label></td>
                    </tr>
                <?php } ?>
                <?php if ($reward_status) { ?>
                    <tr class="highlight">
                        <td><?php if ($next == 'reward') { ?>
                                <input type="radio" name="next" value="reward" id="use_reward" checked="checked" />
                            <?php } else { ?>
                                <input type="radio" name="next" value="reward" id="use_reward" />
                            <?php } ?></td>
                        <td><label for="use_reward"><?php echo $text_use_reward; ?></label></td>
                    </tr>
                <?php } ?>
            </table>
        </div>
        <div class="cart-discounts">
            <div id="coupon" class="content" style="display: <?php echo ($next == 'coupon' ? 'block' : 'none'); ?>;">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                    <?php echo $entry_coupon; ?>&nbsp;
                    <input type="text" name="coupon" value="<?php echo $coupon; ?>" />
                    <input type="hidden" name="next" value="coupon" />
                    &nbsp;
                    <input type="submit" value="<?php echo $button_coupon; ?>" class="button" />
                </form>
            </div>
            <div id="voucher" class="content" style="display: <?php echo ($next == 'voucher' ? 'block' : 'none'); ?>;">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                    <?php echo $entry_voucher; ?>&nbsp;
                    <input type="text" name="voucher" value="<?php echo $voucher; ?>" />
                    <input type="hidden" name="next" value="voucher" />
                    &nbsp;
                    <input type="submit" value="<?php echo $button_voucher; ?>" class="button" />
                </form>
            </div>
            <div id="reward" class="content" style="display: <?php echo ($next == 'reward' ? 'block' : 'none'); ?>;">
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                    <?php echo $entry_reward; ?>&nbsp;
                    <input type="text" name="reward" value="<?php echo $reward; ?>" />
                    <input type="hidden" name="next" value="reward" />
                    &nbsp;
                    <input type="submit" value="<?php echo $button_reward; ?>" class="button" />
                </form>
            </div>
        </div>
    <?php } ?>
        
<?php if($has_shipping) { ?>
    <?php if(!isset($shipping_methods)) { ?>
        <div class="warning"><?php echo $error_no_shipping ?></div>
    <?php } else { ?>
        <div class="cart-module">
            <div id="shipping" class="content" style="display: block;">
                <form action="<?php echo $action_shipping; ?>" method="post" id="shipping_form">
                    <table class="radio">

                    <?php foreach ($shipping_methods as $shipping_method) { ?>

                        <tr>
                            <td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
                        </tr>
                        <?php if (!$shipping_method['error']) { ?>
                            <?php foreach ($shipping_method['quote'] as $quote) { ?>
                            <tr class="highlight">
                                <td><?php if ($quote['code'] == $code || !$code) { ?>
                                    <?php $code = $quote['code']; ?>
                                    <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked" />
                                    <?php } else { ?>
                                    <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
                                    <?php } ?></td>
                                <td><label for="<?php echo $quote['code']; ?>"><?php echo $quote['title']; ?></label></td>
                                <td style="text-align: right;"><label for="<?php echo $quote['code']; ?>"><?php echo $quote['text']; ?></label></td>
                            </tr>
                            <?php } ?>
                        <?php } else { ?>
                        <tr>
                            <td colspan="3"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
                        </tr>
                        <?php } ?>
                    <?php } ?>

                    </table>
                </form>
            </div>
        </div>
    <?php } ?>
<?php } ?>


    <div class="checkout-product">
        <table>
            <thead>
            <tr>
                <td class="name"><?php echo $column_name; ?></td>
                <td class="model"><?php echo $column_model; ?></td>
                <td class="quantity"><?php echo $column_quantity; ?></td>
                <td class="price"><?php echo $column_price; ?></td>
                <td class="total"><?php echo $column_total; ?></td>
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
                <td class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                    <?php foreach ($product['option'] as $option) { ?>
                    <br />
                    <small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                    <?php } ?>
                    <?php if ($product['recurring']): ?>
                    <br />
                    <small> - <?php echo $text_payment_profile ?>: <?php echo $product['profile_name'] ?></small>
                    <?php endif; ?>
                </td>
                <td class="model"><?php echo $product['model']; ?></td>
                <td class="quantity"><?php echo $product['quantity']; ?></td>
                <td class="price"><?php echo $product['price']; ?></td>
                <td class="total"><?php echo $product['total']; ?></td>
            </tr>
            <?php } ?>
            <?php foreach ($vouchers as $voucher) { ?>
            <tr>
                <td class="name"><?php echo $voucher['description']; ?></td>
                <td class="model"></td>
                <td class="quantity">1</td>
                <td class="price"><?php echo $voucher['amount']; ?></td>
                <td class="total"><?php echo $voucher['amount']; ?></td>
            </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <?php foreach ($totals as $total) { ?>
            <tr>
                <td colspan="4" class="price"><b><?php echo $total['title']; ?>:</b></td>
                <td class="total"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>
            </tfoot>
        </table>
    </div>
    <div class="buttons">
        <div class="right"><a href="<?php echo $action_confirm; ?>" class="button"><?php echo $button_confirm; ?></a></div>
    </div>

    <?php echo $content_bottom; ?>
</div>
<script type="text/javascript"><!--
$("input[name='shipping_method']").change( function() {
    $('#shipping_form').submit();
});

$('input[name=\'next\']').bind('change', function() {
    $('.cart-discounts > div').hide();

    $('#' + this.value).show();
});
//--></script>