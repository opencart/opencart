<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-klarna-account" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-klarna-account" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-log" data-toggle="tab"><?php echo $tab_log; ?></a></li>
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
                    <label class="col-sm-2 control-label" for="input-merchant<?php echo $country['code']; ?>"><span data-toggle="tooltip" title="<?php echo $help_merchant; ?>"><?php echo $entry_merchant; ?></span></label>
                    <div class="col-sm-10">
                      <input type="text" name="klarna_account[<?php echo $country['code']; ?>][merchant]" value="<?php echo isset($klarna_account[$country['code']]) ? $klarna_account[$country['code']]['merchant'] : ''; ?>" placeholder="<?php echo $entry_merchant; ?>" id="input-merchant<?php echo $country['code']; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-secret<?php echo $country['code']; ?>"><span data-toggle="tooltip" title="<?php echo $help_secret; ?>"><?php echo $entry_secret; ?></span></label>
                    <div class="col-sm-10">
                      <input type="text" name="klarna_account[<?php echo $country['code']; ?>][secret]" value="<?php echo isset($klarna_account[$country['code']]) ? $klarna_account[$country['code']]['secret'] : ''; ?>" placeholder="<?php echo $entry_secret; ?>" id="input-secret<?php echo $country['code']; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-server<?php echo $country['code']; ?>"><?php echo $entry_server; ?></label>
                    <div class="col-sm-10">
                      <select name="klarna_account[<?php echo $country['code']; ?>][server]" id="input-server<?php echo $country['code']; ?>" class="form-control">
                        <?php if (isset($klarna_account[$country['code']]) && $klarna_account[$country['code']]['server'] == 'live') { ?>
                        <option value="live" selected="selected"><?php echo $text_live; ?></option>
                        <?php } else { ?>
                        <option value="live"><?php echo $text_live; ?></option>
                        <?php } ?>
                        <?php if (isset($klarna_account[$country['code']]) && $klarna_account[$country['code']]['server'] == 'beta') { ?>
                        <option value="beta" selected="selected"><?php echo $text_beta; ?></option>
                        <?php } else { ?>
                        <option value="beta"><?php echo $text_beta; ?></option>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-total<?php echo $country['code']; ?>"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
                    <div class="col-sm-10">
                      <input type="text" name="klarna_account[<?php echo $country['code']; ?>][total]" value="<?php echo isset($klarna_account[$country['code']]) ? $klarna_account[$country['code']]['total'] : ''; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total<?php echo $country['code']; ?>" class="form-control" />
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-pending-status<?php echo $country['code']; ?>"><?php echo $entry_pending_status; ?></label>
                    <div class="col-sm-10">
                      <select name="klarna_account[<?php echo $country['code']; ?>][pending_status_id]" id="input-pending-status<?php echo $country['code']; ?>" class="form-control">
                        <?php foreach ($order_statuses as $order_status) { ?>
                        <?php if (isset($klarna_account[$country['code']]) && $order_status['order_status_id'] == $klarna_account[$country['code']]['pending_status_id']) { ?>
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
                      <select name="klarna_account[<?php echo $country['code']; ?>][accepted_status_id]" id="input-accepted-status<?php echo $country['code']; ?>" class="form-control">
                        <?php foreach ($order_statuses as $order_status) { ?>
                        <?php if (isset($klarna_account[$country['code']]) && $order_status['order_status_id'] == $klarna_account[$country['code']]['accepted_status_id']) { ?>
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
                      <select name="klarna_account[<?php echo $country['code']; ?>][geo_zone_id]" id="input-geo-zone<?php echo $country['code']; ?>" class="form-control">
                        <option value="0"><?php echo $text_all_zones; ?></option>
                        <?php foreach ($geo_zones as $geo_zone) { ?>
                        <?php if (isset($klarna_account[$country['code']]) && $geo_zone['geo_zone_id'] == $klarna_account[$country['code']]['geo_zone_id']) {  ?>
                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                        <?php } ?>
                        <?php } ?>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label" for="input-status<?php echo $country['code']; ?>"><?php echo $entry_status; ?></label>
                    <div class="col-sm-10">
                      <select name="klarna_account[<?php echo $country['code']; ?>][status]" id="input-status<?php echo $country['code']; ?>" class="form-control">
                        <?php if (isset($klarna_account[$country['code']]) && $klarna_account[$country['code']]['status']) { ?>
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
                    <label class="col-sm-2 control-label" for="input-sort-order<?php echo $country['code']; ?>"><?php echo $entry_sort_order; ?></label>
                    <div class="col-sm-10">
                      <input type="text" name="klarna_account[<?php echo $country['code']; ?>][sort_order]" value="<?php echo isset($klarna_account[$country['code']]) ? $klarna_account[$country['code']]['sort_order'] : ''; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order<?php echo $country['code']; ?>" class="form-control" />
                    </div>
                  </div>
                </div>
                <?php } ?>
              </div>
            </div>
            <div class="tab-pane" id="tab-log">
              <p>
                <textarea wrap="off" rows="15" class="form-control"><?php echo $log; ?></textarea>
              </p>
              <div class="text-right"><a href="<?php echo $clear; ?>" class="btn btn-danger"><i class="fa fa-eraser"></i> <?php echo $button_clear; ?></a></div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#country a:first').tab('show');
//--></script></div>
<?php echo $footer; ?> 