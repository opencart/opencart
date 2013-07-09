<?php echo $header; ?>

<!-- Breadcrumb -->
<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    
    <li>
        <a href="<?php echo $breadcrumb['href']; ?>">
            <?php echo $breadcrumb['text']; ?>
        </a>
    </li>
    <?php } ?>
</ul>

<div class="row">

    <?php echo $column_left; ?>

    <div id="content" class="span9">

        <?php echo $content_top; ?>

        <h1><?php echo $heading_title; ?></h1>

        <?php if ($orders) { ?>

        <table class="table table-bordered table-hover download-list">

            <thead>
                <tr>
                    <td class="order-id">
                        <strong><?php echo $text_order_id; ?></strong>
                    </td>

                    <td class="order-status">
                        <strong><?php echo $text_status; ?></strong>
                    </td>

                    <td class="order-date-added">
                        <strong><?php echo $text_date_added; ?></strong>
                    </td>

                    <td class="order-products">
                        <strong><?php echo $text_products; ?></strong>
                    </td>

                    <td class="order-customer">
                        <strong><?php echo $text_customer; ?></strong>
                    </td>

                    <td class="order-total">
                        <strong><?php echo $text_total; ?></strong>
                    </td>

                    <td class="empty">
                    </td>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($orders as $order) { ?>
                <tr>

                    <td class="order-id-answer">
                        #<?php echo $order['order_id']; ?>
                    </td>

                    <td class="order-status-answer">
                        <?php echo $order['status']; ?>
                    </td>

                    <td class="order-date-added-answer">
                        <?php echo $order['date_added']; ?>
                    </td>

                    <td class="order-products-answer">
                        <?php echo $order['products']; ?>
                    </td>

                    <td class="order-customer-answer">
                        <?php echo $order['name']; ?>
                    </td>

                    <td class="order-total-answer">
                        <?php echo $order['total']; ?>
                    </td>

                    <td class="order-buttons">
                        <a data-toggle="tooltip" class="tooltip-item" alt="<?php echo $button_view; ?>" title="<?php echo $button_view; ?>" href="<?php echo $order['href']; ?>">
                            <i class="icon-eye-open"></i>
                        </a>

                        <a data-toggle="tooltip" class="tooltip-item" alt="<?php echo $button_reorder; ?>" title="<?php echo $button_reorder; ?>" href="<?php echo $order['reorder']; ?>">
                            <i class="icon-refresh"></i>
                        </a>
                    </td>



                </tr>
                <?php } ?>
            </tbody>


        </table>

        <div class="pagination"><?php echo $pagination; ?></div>
        <?php } else { ?>
        <div class="content"><p><?php echo $text_empty; ?></p></div>
        <?php } ?>
        <div class="buttons clearfix">
            <div class="pull-right">
                <a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
            </div>
        </div>

        <?php echo $content_bottom; ?>

    </div> <!-- content span9 -->

    <?php echo $column_right; ?>

</div> <!-- row -->
<?php echo $footer; ?>