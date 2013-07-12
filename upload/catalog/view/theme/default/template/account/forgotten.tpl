<?php echo $header; ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<?php if ($error_warning) { ?>
<div class="alert alert-error"><?php echo $error_warning; ?></div>
<?php } ?>
<div class="row"><?php echo $column_left; ?>
  <div id="content" class="span9"><?php echo $content_top; ?>
    <h1><?php echo $heading_title; ?></h1>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
      <p><?php echo $text_email; ?></p>
      <fieldset>
        <legend><?php echo $text_your_email; ?></legend>
        <div class="control-group required">
          <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
          <div class="controls">
            <input type="email" name="email" value="" id="input-email" />
          </div>
        </div>
      </fieldset>
      <div class="buttons clearfix">
        <div class="pull-left"><a href="<?php echo $back; ?>" class="btn"><?php echo $button_back; ?></a></div>
        <div class="pull-right">
          <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
        </div>
      </div>
    </form>
    <?php echo $content_bottom; ?></div>
  <?php echo $column_right; ?></div>
<?php echo $footer; ?>