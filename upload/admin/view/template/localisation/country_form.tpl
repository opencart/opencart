<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?> <button type="button" class="close" data-dismiss="alert">&times;</button></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_name; ?></label>
          <div class="controls">
            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" />
            <?php if ($error_name) { ?>
            <span class="error"><?php echo $error_name; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-iso-code-2"><?php echo $entry_iso_code_2; ?></label>
          <div class="controls">
            <input type="text" name="iso_code_2" value="<?php echo $iso_code_2; ?>" placeholder="<?php echo $entry_iso_code_2; ?>" id="input-iso-code-2" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-iso-code-3"><?php echo $entry_iso_code_3; ?></label>
          <div class="controls">
            <input type="text" name="iso_code_3" value="<?php echo $iso_code_3; ?>" placeholder="<?php echo $entry_iso_code_3; ?>" id="input-iso-code-3" />
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-address-format"><?php echo $entry_address_format; ?></label>
          <div class="controls">
            <textarea name="address_format" cols="40" rows="5" placeholder="<?php echo $entry_address_format; ?>" id="input-address-format"><?php echo $address_format; ?></textarea>
            
            <a data-toggle="tooltip" title="<?php echo $help_address_format; ?>"><i class="icon-info-sign"></i></a>
            
            </div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $entry_postcode_required; ?></div>
          <div class="controls">
            <label class="radio inline">
              <?php if ($postcode_required) { ?>
              <input type="radio" name="postcode_required" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="postcode_required" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio inline">
              <?php if (!$postcode_required) { ?>
              <input type="radio" name="postcode_required" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="postcode_required" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
          <div class="controls">
            <select name="status" id="input-status">
              <?php if ($status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="buttons"><button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>