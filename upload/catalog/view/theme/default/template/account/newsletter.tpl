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
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
                <div class="content">
                    <table class="form">
                        <tr>
                            <td>
                                <?php if ($newsletter) { ?>

                                <label class="radio">
                                    <input type="radio" name="newsletter" value="1" checked="checked" />
                                    <?php echo $text_yes; ?>
                                </label>

                                <label class="radio">
                                    <input type="radio" name="newsletter" value="0" />
                                    <?php echo $text_no; ?>
                                </label>

                                <?php } else { ?>

                                <label class="radio">
                                    <input type="radio" name="newsletter" value="1" />
                                    <?php echo $text_yes; ?>
                                </label>

                                <label class="radio">
                                    <input type="radio" name="newsletter" value="0" checked="checked" />
                                    <?php echo $text_no; ?>
                                </label>

                                <?php } ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="buttons clearfix">
                    <div class="pull-left">
                        <a href="<?php echo $back; ?>" class="btn"><?php echo $button_back; ?></a>
                    </div>

                    <div class="pull-right">
                        <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
                    </div>
                </div>
            </form>
        <?php echo $content_bottom; ?>
        </div>
    </div>
    <?php echo $column_right; ?>
</div>
<?php echo $footer; ?>