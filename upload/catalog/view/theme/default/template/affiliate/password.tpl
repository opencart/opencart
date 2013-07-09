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

<div id="content" class="row">
    <?php echo $column_left; ?>
    <div class="span9">
        <?php echo $content_top; ?>

        <h1><?php echo $heading_title; ?></h1>

        <form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">

            <h3><?php echo $text_password; ?></h3>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_password; ?></label>
                <div class="controls">
                    <input type="password" name="password" value="<?php echo $password; ?>" />

                    <?php if ($error_password) { ?>
                    <span class="error"><?php echo $error_password; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label"><span class="text-error">*</span> <?php echo $entry_confirm; ?></label>
                <div class="controls">
                    <input type="password" name="confirm" value="<?php echo $confirm; ?>" />

                    <?php if ($error_confirm) { ?>
                    <span class="error"><?php echo $error_confirm; ?></span>
                    <?php } ?>
                </div>
            </div>

            <div class="buttons clearfix">
                <a href="<?php echo $back; ?>" class="btn pull-left"><?php echo $button_back; ?></a>
                <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary pull-right" />
            </div>

        </form>

        <?php echo $content_bottom; ?>

    </div>

    <?php echo $column_right; ?>

</div>

<?php echo $footer; ?>