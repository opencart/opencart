<?php echo $header; ?>
<div class="container">
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
  <div class="panel">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="icon-edit icon-large"></i> <?php echo $heading_title; ?></h1>
      <div class="buttons">
        <button type="submit" form="form-bank-transfer" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
    </div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-bank-transfer" class="form-horizontal">
      <?php foreach ($languages as $language) { ?>
      <div class="form-group required">
        <label class="col-lg-3 control-label" for="input-bank<?php echo $language['language_id']; ?>"><?php echo $entry_bank; ?></label>
        <div class="col-lg-9">
          <textarea name="bank_transfer_bank<?php echo $language['language_id']; ?>" cols="80" rows="10" placeholder="<?php echo $entry_bank; ?>" id="input-bank<?php echo $language['language_id']; ?>"><?php echo isset(${'bank_transfer_bank_' . $language['language_id']}) ? ${'bank_transfer_bank_' . $language['language_id']} : ''; ?></textarea>
          <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" style="vertical-align: top;" /><br />
          <?php if (isset(${'error_bank_' . $language['language_id']})) { ?>
          <span class="error"><?php echo ${'error_bank_' . $language['language_id']}; ?></span>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="input-total"><?php echo $entry_total; ?> <span class="help-block"><?php echo $help_total; ?></span></label>
        <div class="col-lg-9">
          <input type="text" name="bank_transfer_total" value="<?php echo $bank_transfer_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
        <div class="col-lg-9">
          <select name="bank_transfer_order_status_id" id="input-order-status">
            <?php foreach ($order_statuses as $order_status) { ?>
            <?php if ($order_status['order_status_id'] == $bank_transfer_order_status_id) { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
        <div class="col-lg-9">
          <select name="bank_transfer_geo_zone_id" id="input-geo-zone">
            <option value="0"><?php echo $text_all_zones; ?></option>
            <?php foreach ($geo_zones as $geo_zone) { ?>
            <?php if ($geo_zone['geo_zone_id'] == $bank_transfer_geo_zone_id) { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
            <?php } else { ?>
            <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
            <?php } ?>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="input-status"><?php echo $entry_status; ?></label>
        <div class="col-lg-9">
          <select name="bank_transfer_status" id="input-status">
            <?php if ($bank_transfer_status) { ?>
            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
            <option value="0"><?php echo $text_disabled; ?></option>
            <?php } else { ?>
            <option value="1"><?php echo $text_enabled; ?></option>
            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
            <?php } ?>
          </select>
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
        <div class="col-lg-9">
          <input type="text" name="bank_transfer_sort_order" value="<?php echo $bank_transfer_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="input-mini" />
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>