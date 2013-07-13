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
      <fieldset>
        <legend><?php echo $text_your_details; ?></legend>
        <div class="control-group required">
          <label class="control-label" for="input-firstname"><?php echo $entry_firstname; ?> </label>
          <div class="controls">
            <input type="text" name="firstname" value="<?php echo $firstname; ?>" id="input-firstname" />
            <?php if ($error_firstname) { ?>
            <div class="error"><?php echo $error_firstname; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="control-group required">
          <label class="control-label" for="input-lastname"><?php echo $entry_lastname; ?> </label>
          <div class="controls">
            <input type="text" name="lastname" value="<?php echo $lastname; ?>" id="input-lastname" />
            <?php if ($error_lastname) { ?>
            <div class="error"><?php echo $error_lastname; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="control-group required">
          <label class="control-label" for="input-email"><?php echo $entry_email; ?> </label>
          <div class="controls">
            <input type="email" name="email" value="<?php echo $email; ?>" id="input-email" />
            <?php if ($error_email) { ?>
            <div class="error"><?php echo $error_email; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="control-group required">
          <label class="control-label" for="input-telephone"><?php echo $entry_telephone; ?> </label>
          <div class="controls">
            <input type="tel" name="telephone" value="<?php echo $telephone; ?>" id="input-telephone" />
            <?php if ($error_telephone) { ?>
            <div class="error"><?php echo $error_telephone; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-fax"><?php echo $entry_fax; ?></label>
          <div class="controls">
            <input type="text" name="fax" value="<?php echo $fax; ?>" id="input-fax" />
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