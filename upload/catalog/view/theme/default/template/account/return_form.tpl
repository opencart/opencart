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
<div class="error"><?php echo $error_warning; ?></div>
<?php } ?>

<div class="row">

    <?php echo $column_left; ?>

    <div id="content" class="span9">

        <?php echo $content_top; ?>



        <h1><?php echo $heading_title; ?></h1>
        <?php echo $text_description; ?>
        <form class="form-horizontal" action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <h3><?php echo $text_order; ?></h3>

        <div class="content">

            <div class="control-group">
                <div class="control-label">
                    <span class="text-error">*</span> <?php echo $entry_firstname; ?>
                </div>
                <div class="controls">
                    <input type="text" name="firstname" value="<?php echo $firstname; ?>" class="large-field" />
                    <?php if ($error_firstname) { ?>
                    <div class="error"><?php echo $error_firstname; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <div class="control-label">
                    <span class="text-error">*</span> <?php echo $entry_lastname; ?>
                </div>
                <div class="controls">
                    <input type="text" name="lastname" value="<?php echo $lastname; ?>" class="large-field" />
                    <?php if ($error_lastname) { ?>
                    <div class="error"><?php echo $error_lastname; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <div class="control-label">
                    <span class="text-error">*</span> <?php echo $entry_email; ?>
                </div>
                <div class="controls">
                    <input type="text" name="email" value="<?php echo $email; ?>" class="large-field" />
                    <?php if ($error_email) { ?>
                    <div class="error"><?php echo $error_email; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <div class="control-label">
                    <span class="text-error">*</span> <?php echo $entry_telephone; ?>
                </div>
                <div class="controls">
                    <input type="text" name="telephone" value="<?php echo $telephone; ?>" class="large-field" />
                    <?php if ($error_telephone) { ?>
                    <div class="error"><?php echo $error_telephone; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <div class="control-label">
                    <span class="text-error">*</span> <?php echo $entry_order_id; ?>
                </div>
                <div class="controls">
                    <input type="text" name="order_id" value="<?php echo $order_id; ?>" class="large-field" />
                    <?php if ($error_order_id) { ?>
                    <div class="error"><?php echo $error_order_id; ?></div>
                    <?php } ?>
                </div>
            </div>

            <div class="control-group">
                <div class="control-label">
                    <?php echo $entry_date_ordered; ?>
                </div>
                <div class="controls">
                    <input type="text" name="date_ordered" value="<?php echo $date_ordered; ?>" class="large-field date" />
                </div>
            </div>

        </div>

        <h3><?php echo $text_product; ?></h3>
        <div id="return-product">

            <div class="content">

                <div class="return-product">


                    <div class="control-group return-name">
                        <div class="control-label">
                            <span class="text-error">*</span> <?php echo $entry_product; ?>
                        </div>
                        <div class="controls">
                            <input type="text" name="product" value="<?php echo $product; ?>" />
                            <?php if ($error_product) { ?>
                            <div class="error"><?php echo $error_product; ?></div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="control-group return-model">
                        <div class="control-label">
                            <span class="text-error">*</span> <?php echo $entry_model; ?>
                        </div>
                        <div class="controls">
                            <input type="text" name="model" value="<?php echo $model; ?>" />
                            <?php if ($error_model) { ?>
                            <div class="error"><?php echo $error_model; ?></div>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="control-group return-quantity">
                        <div class="control-label">
                            <?php echo $entry_quantity; ?>
                        </div>
                        <div class="controls">
                            <input type="text" name="quantity" value="<?php echo $quantity; ?>" />
                        </div>
                    </div>

                </div>

                <div class="return-detail">


                        <div class="control-group return-reason">

                            <div class="control-label">
                                <span class="text-error">*</span> <b><?php echo $entry_reason; ?></b><br />
                            </div>

                            <div class="controls">
                                <?php foreach ($return_reasons as $return_reason) { ?>
                                <?php if ($return_reason['return_reason_id'] == $return_reason_id) { ?>

                                <label class="radio" for="return-reason-id<?php echo $return_reason['return_reason_id']; ?>">
                                    <input type="radio" name="return_reason_id" value="<?php echo $return_reason['return_reason_id']; ?>" id="return-reason-id<?php echo $return_reason['return_reason_id']; ?>" checked="checked" />
                                    <?php echo $return_reason['name']; ?>
                                </label>

                                <?php } else { ?>
                                <label class="radio" for="return-reason-id<?php echo $return_reason['return_reason_id']; ?>">

                                    <input type="radio" name="return_reason_id" value="<?php echo $return_reason['return_reason_id']; ?>" id="return-reason-id<?php echo $return_reason['return_reason_id']; ?>" />
                                    <?php echo $return_reason['name']; ?>
                                </label>

                                <?php  } ?>
                                <?php  } ?>

                                <?php if ($error_reason) { ?>
                                <div class="error"><?php echo $error_reason; ?></div>
                                <?php } ?>
                            </div>
                        </div>

                    <div class="control-group return-opened">

                        <div class="control-label">
                            <?php echo $entry_opened; ?>
                        </div>

                        <div class="controls">
                            <label class="radio" for="opened">   
                                <?php if ($opened) { ?>
                                <input type="radio" name="opened" value="1" id="opened" checked="checked" />
                                <?php } else { ?>
                                <input type="radio" name="opened" value="1" id="opened" />
                                <?php } ?>
                                <?php echo $text_yes; ?>
                            </label>

                            <label class="radio" for="unopened">
                                <?php if (!$opened) { ?>
                                <input type="radio" name="opened" value="0" id="unopened" checked="checked" />
                                <?php } else { ?>
                                <input type="radio" name="opened" value="0" id="unopened" />
                                <?php } ?>
                                <?php echo $text_no; ?>
                            </label>
                        </div>

                    </div>

                    <div class="control-group returns-textarea">
                        <div class="control-label">
                            <?php echo $entry_fault_detail; ?>
                        </div>

                        <div class="controls">
                            <textarea name="comment" style="width: 98%; min-height: 150px;"><?php echo $comment; ?></textarea>
                        </div>
                    </div>

                    <div class="control-group return-captcha">
                        <div class="control-label">
                            <?php echo $entry_captcha; ?>
                        </div>

                        <div class="controls">
                            <input type="text" name="captcha" value="<?php echo $captcha; ?>" />
                            <br />
                            <br />
                            <img src="index.php?route=account/return/captcha" alt="" />
                            <?php if ($error_captcha) { ?>
                            <div class="error"><?php echo $error_captcha; ?></div>
                            <?php } ?>
                        </div>
                    </div>

                </div>

            </div>

        </div>

        <?php if ($text_agree) { ?>
        <div class="buttons clearfix">
            <div class="pull-left">
                <a href="<?php echo $back; ?>" class="btn"><?php echo $button_back; ?></a>
            </div>

            <div class="pull-right">
                <?php echo $text_agree; ?>
                <?php if ($agree) { ?>
                <input type="checkbox" name="agree" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="agree" value="1" />
                <?php } ?>
                <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
            </div>
        </div>
        <?php } else { ?>
        <div class="buttons clearfix">
            <div class="pull-left">
                <a href="<?php echo $back; ?>" class="btn"><?php echo $button_back; ?></a></div>
            <div class="pull-right">
                <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
            </div>
        </div>
        <?php } ?>
        </form>


        <?php echo $content_bottom; ?>

    </div>

    <?php echo $column_right; ?>

</div> <!-- row -->
<?php echo $footer; ?>


<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script> 
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		width: 640,
		height: 480
	});
});
//--></script> 