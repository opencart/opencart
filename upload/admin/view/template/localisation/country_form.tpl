<?php echo $header; ?>
<div id="content" class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-country" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-country" class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
          <div class="col-sm-10">
            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
            <?php if ($error_name) { ?>
            <div class="text-danger"><?php echo $error_name; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-iso-code-2"><?php echo $entry_iso_code_2; ?></label>
          <div class="col-sm-10">
            <input type="text" name="iso_code_2" value="<?php echo $iso_code_2; ?>" placeholder="<?php echo $entry_iso_code_2; ?>" id="input-iso-code-2" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-iso-code-3"><?php echo $entry_iso_code_3; ?></label>
          <div class="col-sm-10">
            <input type="text" name="iso_code_3" value="<?php echo $iso_code_3; ?>" placeholder="<?php echo $entry_iso_code_3; ?>" id="input-iso-code-3" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-address-format"><?php echo $entry_address_format; ?> </label>
          <div class="col-sm-10">
            <textarea name="address_format" rows="5" placeholder="<?php echo $entry_address_format; ?>" id="input-address-format" class="form-control"><?php echo $address_format; ?></textarea>
            <span class="help-block"><?php echo $help_address_format; ?></span></div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label"><?php echo $entry_postcode_required; ?></label>
          <div class="col-sm-10">
            <label class="radio-inline">
              <?php if ($postcode_required) { ?>
              <input type="radio" name="postcode_required" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="postcode_required" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio-inline">
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
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
          <div class="col-sm-10">
            <select name="status" id="input-status" class="form-control">
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
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>