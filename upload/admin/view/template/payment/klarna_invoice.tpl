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
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="icon-ok-sign"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-klarna-invoice" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-klarna-invoice" class="form-horizontal">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-log" data-toggle="tab"><?php echo $tab_log ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general"><a href="https://merchants.klarna.com/signup?locale=en&partner_id=d5c87110cebc383a826364769047042e777da5e8&utm_campaign=Platform&utm_medium=Partners&utm_source=Opencart" target="_blank" style="float: right;"><img src="view/image/payment/klarna_banner.gif" /></a>
            <ul class="nav nav-tabs" id="country">
              <?php foreach ($countries as $country) { ?>
              <li><a href="#tab-<?php echo $country['code']; ?>" data-toggle="tab"><?php echo $country['name']; ?></a></li>
              <?php } ?>
            </ul>
            <div class="tab-content">
              <?php foreach ($countries as $country) { ?>
              <div class="tab-pane" id="tab-<?php echo $country['code']; ?>">
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-merchant<?php echo $country['code']; ?>"><?php echo $entry_merchant; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="klarna_invoice[<?php echo $country['code']; ?>][merchant]" value="<?php echo isset($klarna_invoice[$country['code']]) ? $klarna_invoice[$country['code']]['merchant'] : ''; ?>" placeholder="<?php echo $entry_merchant; ?>" id="input-merchant<?php echo $country['code']; ?>" class="form-control" />
                    <span class="help-block"><?php echo $help_merchant; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-secret<?php echo $country['code']; ?>"><?php echo $entry_secret; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="klarna_invoice[<?php echo $country['code']; ?>][secret]" value="<?php echo isset($klarna_invoice[$country['code']]) ? $klarna_invoice[$country['code']]['secret'] : ''; ?>" placeholder="<?php echo $entry_secret; ?>" id="input-secret<?php echo $country['code']; ?>" class="form-control" />
                    <span class="help-block"><?php echo $help_secret; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-server<?php echo $country['code']; ?>"><?php echo $entry_server; ?></label>
                  <div class="col-sm-10">
                    <select name="klarna_invoice[<?php echo $country['code']; ?>][server]" id="input-server<?php echo $country['code']; ?>" class="form-control">
                      <?php if (isset($klarna_invoice[$country['code']]) && $klarna_invoice[$country['code']]['server'] == 'live') { ?>
                      <option value="live" selected="selected"><?php echo $text_live; ?></option>
                      <?php } else { ?>
                      <option value="live"><?php echo $text_live; ?></option>
                      <?php } ?>
                      <?php if (isset($klarna_invoice[$country['code']]) && $klarna_invoice[$country['code']]['server'] == 'beta') { ?>
                      <option value="beta" selected="selected"><?php echo $text_beta; ?></option>
                      <?php } else { ?>
                      <option value="beta"><?php echo $text_beta; ?></option>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-total<?php echo $country['code']; ?>"><?php echo $entry_total; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="klarna_invoice[<?php echo $country['code']; ?>][total]" value="<?php echo isset($klarna_invoice[$country['code']]) ? $klarna_invoice[$country['code']]['total'] : ''; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total<?php echo $country['code']; ?>" class="form-control" />
                    <span class="help-block"><?php echo $help_total; ?></span> </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-pending-status<?php echo $country['code']; ?>"><?php echo $entry_pending_status; ?></label>
                  <div class="col-sm-10">
                    <select name="klarna_invoice[<?php echo $country['code']; ?>][pending_status_id]" id="input-pending-status<?php echo $country['code']; ?>" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if (isset($klarna_invoice[$country['code']]) && $order_status['order_status_id'] == $klarna_invoice[$country['code']]['pending_status_id']) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-accepted-status<?php echo $country['code']; ?>"><?php echo $entry_accepted_status; ?></label>
                  <div class="col-sm-10">
                    <select name="klarna_invoice[<?php echo $country['code']; ?>][accepted_status_id]" id="input-accepted-status<?php echo $country['code']; ?>" class="form-control">
                      <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if (isset($klarna_invoice[$country['code']]) && $order_status['order_status_id'] == $klarna_invoice[$country['code']]['accepted_status_id']) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-geo-zone<?php echo $country['code']; ?>"><?php echo $entry_geo_zone; ?></label>
                  <div class="col-sm-10">
                    <select name="klarna_invoice[<?php echo $country['code']; ?>][geo_zone_id]" id="input-geo-zone<?php echo $country['code']; ?>" class="form-control">
                      <option value="0"><?php echo $text_all_zones; ?></option>
                      <?php foreach ($geo_zones as $geo_zone) { ?>
                      <?php if (isset($klarna_invoice[$country['code']]) && $geo_zone['geo_zone_id'] == $klarna_invoice[$country['code']]['geo_zone_id']) {  ?>
                      <option value="<?php echo $geo_zone['geo_zone_id'] ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                      <?php } else { ?>
                      <option value="<?php echo $geo_zone['geo_zone_id'] ?>"><?php echo $geo_zone['name']; ?></option>
                      <?php } ?>
                      <?php } ?>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-status<?php echo $country['code']; ?>"><?php echo $entry_status; ?></label>
                  <div class="col-sm-10">
                    <select name="klarna_invoice[<?php echo $country['code']; ?>][status]" id="input-status<?php echo $country['code']; ?>" class="form-control">
                      <?php if (isset($klarna_invoice[$country['code']]) && $klarna_invoice[$country['code']]['status']) { ?>
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
                  <label class="col-sm-2 control-label" for="input-sort-order<?php echo $country['code']; ?>"><?php echo $entry_sort_order ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="klarna_invoice[<?php echo $country['code']; ?>][sort_order]" value="<?php echo isset($klarna_invoice[$country['code']]) ? $klarna_invoice[$country['code']]['sort_order'] : ''; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order<?php echo $country['code']; ?>" class="form-control" />
                  </div>
                </div>
              </div>
              <?php } ?>
            </div>
          </div>
          <div class="tab-pane" id="tab-log">
            <p>
              <textarea wrap="off" rows="15" class="form-control"><?php echo $log ?></textarea>
            </p>
            <div class="text-right"><a href="<?php echo $clear; ?>" class="btn btn-danger"><i class="icon-eraser"></i> <?php echo $button_clear ?></a></div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#country a:first').tab('show');
//--></script> 
<?php echo $footer; ?> 