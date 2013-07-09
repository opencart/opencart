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

        <div class="span9">

            <div id="content">

                <?php echo $content_top; ?>

                <h1><?php echo $heading_title; ?></h1>

                <?php if ($returns) { ?>

                <table class="table table-bordered table-hover return-list">

                    <thead>
                        <tr>
                            <td class="return-id">
                                <strong><?php echo $text_return_id; ?></strong>
                            </td>

                            <td class="return-status">
                                <strong><?php echo $text_status; ?></strong>
                            </td>

                            <td class="return-date-added">
                                <strong><?php echo $text_date_added; ?></strong>
                            </td>

                            <td class="return-order-id">
                                <strong><?php echo $text_order_id; ?></strong>
                            </td>

                            <td class="return-customer">
                                <strong><?php echo $text_customer; ?></strong>
                            </td>

                            <td class="empty">
                            </td>
                        </tr>
                    </thead>

                    <tbody>

                        <?php foreach ($returns as $return) { ?>
                        <tr>

                            <td class="return-id-answer">
                                #<?php echo $return['return_id']; ?>
                            </td>

                            <td class="return-status-answer">
                                <?php echo $return['status']; ?>
                            </td>

                            <td class="return-date-added-answer">
                                <?php echo $return['date_added']; ?>
                            </td>

                            <td class="return-order-id-answer">
                                <?php echo $return['order_id']; ?>
                            </td>

                            <td class="return-customer-answer">
                                <?php echo $return['name']; ?>
                            </td>

                            <td class="return-button">
                                <a data-toggle="tooltip" class="tooltip-item" href="<?php echo $return['href']; ?>" alt="<?php echo $button_view; ?>" title="<?php echo $button_view; ?>">
                                    <i class="icon-eye-open"></i>
                                </a>
                            </td>

                        </tr>
                        <?php } ?>


                    </tbody>

                </table>


                <div class="pagination"><?php echo $pagination; ?></div>
                <?php } else { ?>
                <div class="content"><?php echo $text_empty; ?></div>
                <?php } ?>
                <div class="buttons clearfix">
                    <div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
                </div>
                <?php echo $content_bottom; ?>
            </div>

        </div>

        <?php echo $column_right; ?>

    </div>

    <?php echo $footer; ?>