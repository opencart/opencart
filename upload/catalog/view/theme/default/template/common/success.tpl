<?php echo $header; ?>

<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    
    <li>
        <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    </li>
    <?php } ?>
</ul><!-- Breadcrumb -->

<div class="row">
    <?php echo $column_left; ?>
    <div class="span">
        <?php echo $content_top; ?>
        <h1><?php echo $heading_title; ?></h1>
        <?php echo $text_message; ?>
        <div class="buttons clearfix">
            <a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
        </div>
        <?php echo $content_bottom; ?>
    </div>

    <?php echo $column_right; ?>
    
</div>

<?php echo $footer; ?>