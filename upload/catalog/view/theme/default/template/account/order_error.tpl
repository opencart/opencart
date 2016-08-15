<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
            <h1><?php echo $heading_error_title; ?></h1>
            <h3><p class="text-danger"><?php echo $text_order_error; ?></p></h3>
            <a href="<?php echo $order_history_href; ?>" class="btn btn-danger"><?php echo $text_repay; ?></a>
            <?php echo $content_bottom; ?>
        </div>
        <?php echo $column_right; ?>
    </div>
</div>