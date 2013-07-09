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


        <h2><?php echo $heading_title; ?></h2>



        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">

            <h3><?php echo $text_password; ?></h3>

            <fieldset>

                <div class="control-group">

                    <label class="control-label" for="firstname">
                        <span class="text-error">*</span> <?php echo $entry_password; ?>
                    </label>
                    <div class="controls">
                        <input type="password" name="password" value="<?php echo $password; ?>" />
                        <?php if ($error_password) { ?>
                        <div class="alert alert-error alert-form"><?php echo $error_password; ?></div>
                        <?php } ?>
                    </div> <!-- controls -->

                </div> <!-- control-group -->

                <div class="control-group">

                    <label class="control-label" for="firstname">
                        <span class="text-error">*</span> <?php echo $entry_confirm; ?>
                    </label>
                    <div class="controls">
                        <input type="password" name="confirm" value="<?php echo $confirm; ?>" />
                        <?php if ($error_confirm) { ?>
                        <div class="alert alert-error alert-form"><?php echo $error_confirm; ?></div>
                        <?php } ?>
                    </div> <!-- controls -->

                </div> <!-- control-group -->

            </fieldset>

            <div class="buttons clearfix">

                <div class="pull-left">
                    <a href="<?php echo $back; ?>" class="btn">
                        <?php echo $button_back; ?>
                    </a>
                </div> <!-- left -->

                <div class="pull-right">
                    <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
                </div> <!-- right -->

            </div> <!-- buttons -->

        </form>



        <?php echo $content_bottom; ?>

    </div> <!-- content span9 -->

    <?php echo $column_right; ?>

</div> <!-- row -->
<?php echo $footer; ?>
