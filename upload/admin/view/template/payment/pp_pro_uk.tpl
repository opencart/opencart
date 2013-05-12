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
        <div class="buttons"><button type="submit" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></button> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
        <div class="control-group">
          <label class="control-label" for="input-vendor"><span class="required">*</span> <?php echo $entry_vendor; ?></label>
          <div class="controls">
            <input type="text" name="pp_pro_uk_vendor" value="<?php echo $pp_pro_uk_vendor; ?>" placeholder="<?php echo $entry_vendor; ?>" id="input-vendor" />
            
            <a data-toggle="tooltip" title="<?php echo $help_vendor; ?>"><i class="icon-info-sign"></i></a>
            
            <?php if ($error_vendor) { ?>
            <span class="error"><?php echo $error_vendor; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-user"><span class="required">*</span> <?php echo $entry_user; ?></label>
          <div class="controls">
            <input type="text" name="pp_pro_uk_user" value="<?php echo $pp_pro_uk_user; ?>" placeholder="<?php echo $entry_user; ?>" id="input-user" />
            
            <a data-toggle="tooltip" title="<?php echo $help_user; ?>"><i class="icon-info-sign"></i></a>
            
            <?php if ($error_user) { ?>
            <span class="error"><?php echo $error_user; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-password"><span class="required">*</span> <?php echo $entry_password; ?></label>
          <div class="controls">
            <input type="text" name="pp_pro_uk_password" value="<?php echo $pp_pro_uk_password; ?>" placeholder="<?php echo $entry_password; ?>" id="input-password" />
            
            <a data-toggle="tooltip" title="<?php echo $help_password; ?>"><i class="icon-info-sign"></i></a>
            
            <?php if ($error_password) { ?>
            <span class="error"><?php echo $error_password; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-partner"><span class="required">*</span> <?php echo $entry_partner; ?></label>
          <div class="controls">
            <input type="text" name="pp_pro_uk_partner" value="<?php echo $pp_pro_uk_partner; ?>" placeholder="<?php echo $entry_partner; ?>" id="input-partner" />

            <a data-toggle="tooltip" title="<?php echo $help_partner; ?>"><i class="icon-info-sign"></i></a>
            
            <?php if ($error_partner) { ?>
            <span class="error"><?php echo $error_partner; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $entry_test; ?></div>
          <div class="controls">
            <label class="radio inline">
              <?php if ($pp_pro_uk_test) { ?>
              <input type="radio" name="pp_pro_uk_test" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="pp_pro_uk_test" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio inline">
              <?php if (!$pp_pro_uk_test) { ?>
              <input type="radio" name="pp_pro_uk_test" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="pp_pro_uk_test" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>
            
            <a data-toggle="tooltip" title="<?php echo $help_test; ?>"><i class="icon-info-sign"></i></a>
            </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-transaction"><?php echo $entry_transaction; ?></label>
          <div class="controls">
            <select name="pp_pro_uk_transaction" id="input-transaction">
              <?php if (!$pp_pro_uk_transaction) { ?>
              <option value="0" selected="selected"><?php echo $text_authorization; ?></option>
              <?php } else { ?>
              <option value="0"><?php echo $text_authorization; ?></option>
              <?php } ?>
              <?php if ($pp_pro_uk_transaction) { ?>
              <option value="1" selected="selected"><?php echo $text_sale; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_sale; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
          <div class="controls">
            <input type="text" name="pp_pro_uk_total" value="<?php echo $pp_pro_uk_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" />
            
            <a data-toggle="tooltip" title="<?php echo $help_total; ?>"><i class="icon-info-sign"></i></a>
            
            </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
          <div class="controls">
            <select name="pp_pro_uk_order_status_id" id="input-order-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_pro_uk_order_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
          <div class="controls">
            <select name="pp_pro_uk_geo_zone_id" id="input-geo-zone">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $pp_pro_uk_geo_zone_id) { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
          <div class="controls">
            <select name="pp_pro_uk_status" id="input-status">
              <?php if ($pp_pro_uk_status) { ?>
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
          <label class="control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
          <div class="controls">
            <input type="text" name="pp_pro_uk_sort_order" value="<?php echo $pp_pro_uk_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>