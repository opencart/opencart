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
        <div class="content"><p><?php echo $text_error; ?></p></div>
        <div class="buttons clearfix">
            <div class="pull-left">
                <a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
            </div>
        </div>
        
        <?php echo $content_bottom; ?>

    </div>

    <?php echo $column_right; ?>
    
</div>

</div>


<?php echo $footer; ?>