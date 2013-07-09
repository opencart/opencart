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

    <div id="content" class="span12">

        <?php echo $content_top; ?>


        <h1><?php echo $heading_title; ?></h1>
        <?php echo $description; ?>


        <?php echo $content_bottom; ?>

    </div> <!-- content span12 -->

    <?php echo $column_right; ?>

</div> <!-- row -->
<?php echo $footer; ?>

