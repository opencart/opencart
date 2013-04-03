<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <div class="buttons"><button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_username; ?></label>
          <div class="controls">
            <input type="text" name="pp_pro_username" value="<?php echo $pp_pro_username; ?>" placeholder="<?php echo $entry_username; ?>" />
            <?php if ($error_username) { ?>
            <span class="error"><?php echo $error_username; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_password; ?></label>
          <div class="controls">
            <input type="text" name="pp_pro_password" value="<?php echo $pp_pro_password; ?>" placeholder="<?php echo $entry_password; ?>" />
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><span class="required">*</span> <?php echo $entry_signature; ?></label>
          <div class="controls">
            <input type="text" name="pp_pro_signature" value="<?php echo $pp_pro_signature; ?>" placeholder="<?php echo $entry_signature; ?>" />
            <?php if ($error_signature) { ?>
            <span class="error"><?php echo $error_signature; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_test; ?></label>
          <div class="controls">
            <label class="radio inline">
              <?php if ($pp_pro_test) { ?>
              <input type="radio" name="pp_pro_test" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="pp_pro_test" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio inline">
              <?php if (!$pp_pro_test) { ?>
              <input type="radio" name="pp_pro_test" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="pp_pro_test" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>
            <span class="help-block"><?php echo $help_test; ?></span></div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_transaction; ?></label>
          <div class="controls">
            <select name="pp_pro_transaction">
              <?php if (!$pp_pro_transaction) { ?>
              <option value="0" selected="selected"><?php echo $text_authorization; ?></option>
              <?php } else { ?>
              <option value="0"><?php echo $text_authorization; ?></option>
              <?php } ?>
              <?php if ($pp_pro_transaction) { ?>
              <option value="1" selected="selected"><?php echo $text_sale; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_sale; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_total; ?></label>
          <div class="controls">
            <input type="text" name="pp_pro_total" value="<?php echo $pp_pro_total; ?>" placeholder="<?php echo $entry_total; ?>" />
            <span class="help-block"><?php echo $help_total; ?></span></div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_order_status; ?></label>
          <div class="controls">
            <select name="pp_pro_order_status_id">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_pro_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_geo_zone; ?></label>
          <div class="controls">
            <select name="pp_pro_geo_zone_id">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $pp_pro_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_status; ?></label>
          <div class="controls">
            <select name="pp_pro_status">
              <?php if ($pp_pro_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-name"><?php echo $entry_sort_order; ?></label>
          <div class="controls">
            <input type="text" name="pp_pro_sort_order" value="<?php echo $pp_pro_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" class="input-mini" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>