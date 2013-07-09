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
    <div class="span12">
        <div id="content">
            <?php echo $content_top; ?>
            <h1><?php echo $heading_title; ?></h1>
            <div class="row">
                <div class="span4">
                    <h3><?php echo $text_location; ?></h3>
                    <div class="contact-info">
                        <div class="content">
                            <address>
                                <?php echo $text_address; ?><br>
                                <?php echo $store; ?><br>
                                <?php echo $address; ?><br>
                                <?php if ($telephone) { ?>
                                <?php echo $text_telephone; ?><br>
                                <?php echo $telephone; ?><br>
                                <?php } ?>
                                <?php if ($fax) { ?>
                                <?php echo $text_fax; ?><br>
                                <?php echo $fax; ?><br>
                                <?php } ?>
                            </address>
                        </div>
                    </div>
                </div>
                <div class="span8">
                    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                        <fieldset>

                            <h3><?php echo $text_contact; ?></h3>

                            <label><?php echo $entry_name; ?></label>
                            <input type="text" name="name" value="<?php echo $name; ?>" />

                            <?php if ($error_name) { ?>
                            <div class="alert alert-error"><?php echo $error_name; ?></div>
                            <?php } ?>

                            <label><?php echo $entry_email; ?></label>

                            <input type="text" name="email" value="<?php echo $email; ?>" />

                            <?php if ($error_email) { ?>
                            <div class="alert alert-error"><?php echo $error_email; ?></div>
                            <?php } ?>

                            <label><?php echo $entry_enquiry; ?></label>

                            <textarea name="enquiry" cols="40" rows="10" style="width: 99%;">
                                <?php echo $enquiry; ?>
                            </textarea>

                            <?php if ($error_enquiry) { ?>
                            <div class="alert alert-error"><?php echo $error_enquiry; ?></div>
                            <?php } ?>

                            <img class="captcha" src="index.php?route=information/contact/captcha" alt="" />

                            <label><?php echo $entry_captcha; ?></label>
                            
                            <input type="text" name="captcha" value="<?php echo $captcha; ?>" />

                            <?php if ($error_captcha) { ?>
                            <div class="alert alert-error"><?php echo $error_captcha; ?></div>
                            <?php } ?>

                            <hr>
                            <input class="btn btn-primary" type="submit" value="<?php echo $button_continue; ?>" />
                        </fieldset>
                    </form>
                </div>
            </div>
            <?php echo $content_bottom; ?>
        </div>
    </div>
    <?php echo $column_right; ?>
</div>
<?php echo $footer; ?>