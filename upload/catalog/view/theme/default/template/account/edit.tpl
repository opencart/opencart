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

<?php if ($error_warning) { ?>
<div class="warning">
    <?php echo $error_warning; ?>
</div>
<?php } ?>

<div class="row">

    <?php echo $column_left; ?>

    <div id="content" class="span9">

        <?php echo $content_top; ?>

        <h2><?php echo $heading_title; ?></h2>

        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">

            <h3><?php echo $text_your_details; ?></h3>

            <fieldset>

                <div class="control-group">

                        <label class="control-label" for="firstname">
                            <span class="text-error">*</span> <?php echo $entry_firstname; ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="firstname" value="<?php echo $firstname; ?>" />
                            <?php if ($error_firstname) { ?>
                            <div class="alert alert-error alert-form"><?php echo $error_firstname; ?></div>
                            <?php } ?>
                        </div> <!-- controls -->

                </div> <!-- control-group -->

                <div class="control-group">

                        <label class="control-label" for="lastname">
                            <span class="text-error">*</span> <?php echo $entry_lastname; ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="lastname" value="<?php echo $lastname; ?>" />
                            <?php if ($error_lastname) { ?>
                            <div class="alert alert-error alert-form"><?php echo $error_lastname; ?></div>
                            <?php } ?>
                        </div> <!-- controls -->

                </div> <!-- control-group -->

                <div class="control-group">

                        <label class="control-label" for="email">
                            <span class="text-error">*</span> <?php echo $entry_email; ?>
                        </label>
                        <div class="controls">
                            <input type="email" name="email" value="<?php echo $email; ?>" />
                            <?php if ($error_email) { ?>
                            <div class="alert alert-error alert-form"><?php echo $error_email; ?></div>
                            <?php } ?>
                        </div> <!-- controls -->

                </div> <!-- control-group -->

                <div class="control-group">

                        <label class="control-label" for="telephone">
                            <span class="text-error">*</span> <?php echo $entry_telephone; ?>
                        </label>
                        <div class="controls">
                            <input type="tel" name="telephone" value="<?php echo $telephone; ?>" />
                            <?php if ($error_telephone) { ?>
                            <div class="alert alert-error alert-form"><?php echo $error_telephone; ?></div>
                            <?php } ?>
                        </div> <!-- controls -->

                </div> <!-- control-group -->

                <div class="control-group">

                        <label class="control-label" for="fax">
                            <?php echo $entry_fax; ?>
                        </label>
                        <div class="controls">
                            <input type="text" name="fax" value="<?php echo $fax; ?>" />
                        </div> <!-- controls -->

                </div> <!-- control-group -->

            </fieldset>

            <div class="buttons clearfix">

                <div class="pull-left">
                    <a href="<?php echo $back; ?>" class="btn">
                        <?php echo $button_back; ?>
                    </a>
                </div>

                <div class="pull-right">
                    <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
                </div>

            </div> <!-- buttons -->

        </form>

        <?php echo $content_bottom; ?>

    </div>

    <?php echo $column_right; ?>

</div> <!-- row -->

<?php echo $footer; ?>