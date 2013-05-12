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
          <label class="control-label" for="input-email"><span class="required">*</span> <?php echo $entry_email; ?></label>
          <div class="controls">
            <input type="text" name="pp_standard_email" value="<?php echo $pp_standard_email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" />
            <?php if ($error_email) { ?>
            <span class="error"><?php echo $error_email; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="control-group">
          <div class="control-label"><?php echo $entry_test; ?></div>
          <div class="controls">
            <label class="radio inline">
              <?php if ($pp_standard_test) { ?>
              <input type="radio" name="pp_standard_test" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <?php } else { ?>
              <input type="radio" name="pp_standard_test" value="1" />
              <?php echo $text_yes; ?>
              <?php } ?>
            </label>
            <label class="radio inline">
              <?php if (!$pp_standard_test) { ?>
              <input type="radio" name="pp_standard_test" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="pp_standard_test" value="0" />
              <?php echo $text_no; ?>
              <?php } ?>
            </label>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-transaction"><?php echo $entry_transaction; ?></label>
          <div class="controls">
            <select name="pp_standard_transaction" id="input-transaction">
              <?php if (!$pp_standard_transaction) { ?>
              <option value="0" selected="selected"><?php echo $text_authorization; ?></option>
              <?php } else { ?>
              <option value="0"><?php echo $text_authorization; ?></option>
              <?php } ?>
              <?php if ($pp_standard_transaction) { ?>
              <option value="1" selected="selected"><?php echo $text_sale; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_sale; ?></option>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-debug"><?php echo $entry_debug; ?></label>
          <div class="controls">
            <select name="pp_standard_debug" id="input-debug">
              <?php if ($pp_standard_debug) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select>
            
            
            <a data-toggle="tooltip" title="<?php echo $help_debug; ?>"><i class="icon-info-sign"></i></a>
             </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-total"><?php echo $entry_total; ?></label>
          <div class="controls">
            <input type="text" name="pp_standard_total" value="<?php echo $pp_standard_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" />
            
            <a data-toggle="tooltip" title="<?php echo $help_total; ?>"><i class="icon-info-sign"></i></a>
            
             </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-canceled-reversal-status"><?php echo $entry_canceled_reversal_status; ?></label>
          <div class="controls">
            <select name="pp_standard_canceled_reversal_status_id" id="input-canceled-reversal-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_standard_canceled_reversal_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-completed-status"><?php echo $entry_completed_status; ?></label>
          <div class="controls">
            <select name="pp_standard_completed_status_id" id="input-completed-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_standard_completed_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-denied-status"><?php echo $entry_denied_status; ?></label>
          <div class="controls">
            <select name="pp_standard_denied_status_id" id="input-denied-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_standard_denied_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-expired-status"><?php echo $entry_expired_status; ?></label>
          <div class="controls">
            <select name="pp_standard_expired_status_id" id="input-expired-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_standard_expired_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-failed-status"><?php echo $entry_failed_status; ?></label>
          <div class="controls">
            <select name="pp_standard_failed_status_id" id="input-failed-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_standard_failed_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-pending-status"><?php echo $entry_pending_status; ?></label>
          <div class="controls">
            <select name="pp_standard_pending_status_id" id="input-pending-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_standard_pending_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-processed-status"><?php echo $entry_processed_status; ?></label>
          <div class="controls">
            <select name="pp_standard_processed_status_id" id="input-processed-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_standard_processed_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-refunded-status"><?php echo $entry_refunded_status; ?></label>
          <div class="controls">
            <select name="pp_standard_refunded_status_id" id="input-refunded-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_standard_refunded_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-reversed-status"><?php echo $entry_reversed_status; ?></label>
          <div class="controls">
            <select name="pp_standard_reversed_status_id" id="input-reversed-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_standard_reversed_status_id) { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select>
          </div>
        </div>
        <div class="control-group">
          <label class="control-label" for="input-voided-status"><?php echo $entry_voided_status; ?></label>
          <div class="controls">
            <select name="pp_standard_voided_status_id" id="input-voided-status">
              <?php foreach ($order_statuses as $order_status) { ?>
              <?php if ($order_status['order_status_id'] == $pp_standard_voided_status_id) { ?>
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
            <select name="pp_standard_geo_zone_id" id="input-geo-zone">
              <option value="0"><?php echo $text_all_zones; ?></option>
              <?php foreach ($geo_zones as $geo_zone) { ?>
              <?php if ($geo_zone['geo_zone_id'] == $pp_standard_geo_zone_id) { ?>
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
            <select name="pp_standard_status" id="input-status">
              <?php if ($pp_standard_status) { ?>
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
            <input type="text" name="pp_standard_sort_order" value="<?php echo $pp_standard_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" />
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?> 