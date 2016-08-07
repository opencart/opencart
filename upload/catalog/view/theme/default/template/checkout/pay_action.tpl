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
            <h1><?php echo $heading_title; ?></h1>
            <ul class="list-unstyled">
                <li class="pull-left">
                    <a href="<?php echo $paypal_express_url; ?>" title="PayPal Express">
                        <img src="<?php echo HTTPS_SERVER; ?>/catalog/view/theme/default/image/paypal_express.png" />
                    </a>
                </li>
                <li class="pull-left col-md-offset-2">
                    <a href="<?php echo $paypal_pro_url; ?>" title="PayPal Pro">
                        <img src="<?php echo HTTPS_SERVER; ?>/catalog/view/theme/default/image/paypal_pro.png" />
                    </a>
                </li>
            </ul>
            <?php echo $content_bottom; ?></div>
        <?php echo $column_right; ?></div>
</div>