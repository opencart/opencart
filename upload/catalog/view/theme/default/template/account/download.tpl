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

    <div class="span9" id="content">

        <?php echo $content_top; ?>

        <h2><?php echo $heading_title; ?></h2>

            <?php if($downloads) { ?>

            <table class="table table-bordered table-hover download-list">

                <thead>
                    <tr>
                        <td class="download-id">
                            <strong><?php echo $text_order; ?></strong>
                        </td>

                        <td class="download-status">
                            <strong><?php echo $text_size; ?></strong>
                        </td>

                        <td class="download-name">
                            <strong><?php echo $text_name; ?></strong>
                        </td>

                        <td class="download-added">
                            <strong><?php echo $text_date_added; ?></strong>
                        </td>

                        <td class="download-remaining">
                            <strong><?php echo $text_remaining; ?></strong>
                        </td>

                        <td class="empty">
                        </td>
                    </tr>
                </thead>

                <tbody>
                <?php foreach ($downloads as $download) { ?>
                    <tr>
                        <td class="download-id-answer">
                            <?php echo $download['order_id']; ?>
                        </td>

                        <td class="download-status-answer">
                            <?php echo $download['size']; ?>
                        </td>

                        <td class="download-name-answer">
                            <?php echo $download['name']; ?>
                        </td>

                        <td class="download-added-answer">
                            <?php echo $download['date_added']; ?>
                        </td>

                        <td class="download-remaining-answer">
                            <?php echo $download['remaining']; ?>
                        </td>

                        <td class="download-button">
                            <?php if ($download['remaining'] > 0) { ?>
                                <a data-toggle="tooltip" class="tooltip-item" title="Download your item" href="<?php echo $download['href']; ?>"><i class="icon-cloud-download"></i></a>
                            <?php } ?>
                        </td>
                    </tr>
            <?php } ?>
            </tbody>
            
            </table>

            
            <div class="pagination">
                <?php echo $pagination; ?>
            </div>

            <?php } ?>




            <div class="buttons clearfix">
                <div class="pull-right">
                    <a href="<?php echo $continue; ?>" class="btn btn-primary">
                        <?php echo $button_continue; ?>
                    </a>
                </div>
            </div>

        <?php echo $content_bottom; ?>

    </div> <!-- span9 content -->

    <?php echo $column_right; ?>


</div> <!-- row -->
<?php echo $footer; ?>