<?php echo $header; ?>
<div id="content">

    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>

    <div class="box" style="margin-bottom:130px;">
        <div class="heading">
            <h1><?php echo $lang_page_title; ?></h1>
            <div class="buttons">
                <a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $lang_cancel; ?></span></a>
            </div>
        </div>
        <div class="content">
            <h3><?php echo $lang_created; echo $pricing['created']; ?></h3>
            <table class="list">
                <thead>
                <tr>
                    <td class="left"><?php echo $lang_column_name; ?></td>
                    <td class="center" width="75"><?php echo $lang_column_product_id; ?></td>
                    <td class="center" width="75"><?php echo $lang_column_product_type; ?></td>
                    <td class="center" width="75"><?php echo $lang_column_dispatchto; ?></td>
                    <td class="center" width="75"><?php echo $lang_column_price_uk; ?></td>
                    <td class="center" width="75"><?php echo $lang_column_price_cheap_uk; ?></td>
                    <td class="center" width="75"><?php echo $lang_column_price_euro; ?></td>
                    <td class="center" width="75"><?php echo $lang_column_price_cheap_euro; ?></td>
                    <td class="center" width="75"><?php echo $lang_column_stock; ?></td>
                </tr>
                </thead>
                <tbody>
                <?php if ($pricing) { ?>
                <?php foreach ($pricing['products'] as $price) { ?>
                    <tr>
                        <td class="left"><?php echo $price['item-name']; ?></td>
                        <td class="center" width="75"><?php echo $price['product-id']; ?></td>
                        <td class="center" width="75"><?php echo $product_id_types[$price['product-id-type']]; ?></td>
                        <td class="center" width="75"><?php echo $product_dispatch_to[$price['dispatch-to']]; ?></td>
                        <td class="center" width="75">&pound;<?php echo number_format((double)$price['delivered-price-gbp'], 2); ?></td>
                        <td class="center" width="75">&pound;<?php echo number_format((double)$price['cheapest-delivered-price-gbp'], 2); ?></td>
                        <td class="center" width="75"><?php echo number_format((double)$price['delivered-price-euro'], 2); ?>&euro;</td>
                        <td class="center" width="75"><?php echo number_format((double)$price['cheapest-delivered-price-euro'], 2); ?>&euro;</td>
                        <td class="center" width="75"><?php echo $price['quantity']; ?></td>
                    </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                    <td class="center" colspan="9"><?php echo $lang_no_data_found; ?></td>
                </tr>
                <?php } ?>
                </tbody>
            </table>

            <div class="pagination"><?php echo $pagination; ?></div>

        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {

    });
    //--></script>
<?php echo $footer; ?>