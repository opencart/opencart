<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <ul class="breadcrumb">
	<?php foreach ($breadcrumbs as $breadcrumb) { ?>
		<li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
	<?php } ?>
  </ul>
  <div class="page-header">
	<div class="container-fluid">
	  <div class="pull-right">
		<button type="submit" form="form-worldpay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		<a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
	  <h1><i class="fa fa-credit-card"></i> <?php echo $heading_title; ?></h1>
	</div>
  </div>
  <div class="container-fluid">
	<div class="panel-body">
	  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-worldpay" class="form-horizontal">
		<ul class="nav nav-tabs">
		  <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
		  <li><a href="#tab-order-status" data-toggle="tab"><?php echo $tab_order_status; ?></a></li>
		</ul>
		<div class="tab-content">
		  <div class="tab-pane active" id="tab-general">
			<div class="form-group required">
			  <label class="col-sm-2 control-label" for="input-service-key"><?php echo $entry_service_key; ?></label>
			  <div class="col-sm-10">
				<input type="text" name="worldpay_service_key" value="<?php echo $worldpay_service_key; ?>" placeholder="<?php echo $entry_service_key; ?>" id="input-service-key" class="form-control" />
				<?php if ($error_service_key) { ?>
					<div class="text-danger"><?php echo $error_service_key; ?></div>
				<?php } ?>
			  </div>
			</div>
			<div class="form-group required">
			  <label class="col-sm-2 control-label" for="input-client-key"><?php echo $entry_client_key; ?></label>
			  <div class="col-sm-10">
				<input type="text" name="worldpay_client_key" value="<?php echo $worldpay_client_key; ?>" placeholder="<?php echo $entry_client_key; ?>" id="input-client-key" class="form-control" />
				<?php if ($error_client_key) { ?>
					<div class="text-danger"><?php echo $error_client_key; ?></div>
				<?php } ?>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label" for="input-total"><?php echo $entry_total; ?> </label>
			  <div class="col-sm-10">
				<input type="text" name="worldpay_total" value="<?php echo $worldpay_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-total" class="form-control" />
				<span class="help-block"><?php echo $help_total; ?></span> </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label" for="input-card"><?php echo $entry_card; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_card" id="input-card" class="form-control">
				  <?php if ($worldpay_card) { ?>
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
			  <label class="col-sm-2 control-label" for="input-secret-token"><span data-toggle="tooltip" title="<?php echo $help_secret_token; ?>"><?php echo $entry_secret_token; ?></span></label>
			  <div class="col-sm-10">
				<input type="text" name="worldpay_secret_token" value="<?php echo $worldpay_secret_token; ?>" id="input-secret-token" class="form-control" />
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label" for="input-webhook-url"><span data-toggle="tooltip" title="<?php echo $help_webhook_url; ?>"><?php echo $entry_webhook_url; ?></span></label>
			  <div class="col-sm-10">
				<div class="input-group"><span class="input-group-addon"><i class="fa fa-link"></i></span>
				  <input type="text" readonly value="<?php echo $worldpay_webhook_url; ?>" id="input-webhook-url" class="form-control" />
				</div>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label" for="input-cron-job-url"><span data-toggle="tooltip" title="<?php echo $help_cron_job_url; ?>"><?php echo $entry_cron_job_url; ?></span></label>
			  <div class="col-sm-10">
				<div class="input-group"><span class="input-group-addon"><i class="fa fa-link"></i></span>
				  <input type="text" readonly value="<?php echo $worldpay_cron_job_url; ?>" id="input-cron-job-url" class="form-control" />
				</div>
			  </div>
			</div>
			<?php if ($worldpay_last_cron_job_run) { ?>
				<div class="form-group">
				  <label class="col-sm-2 control-label" for="input-cron-job-last-run"><?php echo $entry_last_cron_job_run; ?></label>
				  <div class="col-sm-10">
					<input type="text" readonly value="<?php echo $worldpay_last_cron_job_run; ?>" id="input-cron-job-last-run" class="form-control" />
				  </div>
				</div>
			<?php } ?>
			<div class="form-group">
			  <label class="col-sm-2 control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_order_status_id" id="input-order-status" class="form-control">
				  <?php foreach ($order_statuses as $order_status) { ?>
					  <?php if ($order_status['order_status_id'] == $worldpay_order_status_id) { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_geo_zone_id" id="input-geo-zone" class="form-control">
				  <option value="0"><?php echo $text_all_zones; ?></option>
				  <?php foreach ($geo_zones as $geo_zone) { ?>
					  <?php if ($geo_zone['geo_zone_id'] == $worldpay_geo_zone_id) { ?>
						  <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
					  <?php } else { ?>
						  <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
					  <?php } ?>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label" for="input-debug"><?php echo $entry_debug; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_debug" id="input-debug" class="form-control">
				  <?php if ($worldpay_debug) { ?>
					  <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
					  <option value="0"><?php echo $text_disabled; ?></option>
				  <?php } else { ?>
					  <option value="1"><?php echo $text_enabled; ?></option>
					  <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
				  <?php } ?>
				</select>
				<span class="help-block"><?php echo $help_debug; ?></span>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_status" id="input-status" class="form-control">
				  <?php if ($worldpay_status) { ?>
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
			  <label class="col-sm-2 control-label" for="input-sort-order"><?php echo $entry_sort_order; ?></label>
			  <div class="col-sm-10">
				<input type="text" name="worldpay_sort_order" value="<?php echo $worldpay_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-sort-order" class="form-control" />
			  </div>
			</div>
		  </div>
		  <div class="tab-pane" id="tab-order-status">
			<div class="form-group">
			  <label class="col-sm-2 control-label"><?php echo $entry_success_status; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_entry_success_status_id" class="form-control">
				  <?php foreach ($order_statuses as $order_status) { ?>
					  <?php if ($order_status['order_status_id'] == $worldpay_entry_success_status_id) { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label"><?php echo $entry_failed_status; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_entry_failed_status_id" class="form-control">
				  <?php foreach ($order_statuses as $order_status) { ?>
					  <?php if ($order_status['order_status_id'] == $worldpay_entry_failed_status_id) { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label"><?php echo $entry_settled_status; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_entry_settled_status_id" class="form-control">
				  <?php foreach ($order_statuses as $order_status) { ?>
					  <?php if ($order_status['order_status_id'] == $worldpay_entry_settled_status_id) { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label"><?php echo $entry_refunded_status; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_refunded_status_id" class="form-control">
				  <?php foreach ($order_statuses as $order_status) { ?>
					  <?php if ($order_status['order_status_id'] == $worldpay_refunded_status_id) { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label"><?php echo $entry_partially_refunded_status; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_entry_partially_refunded_status_id" class="form-control">
				  <?php foreach ($order_statuses as $order_status) { ?>
					  <?php if ($order_status['order_status_id'] == $worldpay_entry_partially_refunded_status_id) { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label"><?php echo $entry_charged_back_status; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_entry_charged_back_status_id" class="form-control">
				  <?php foreach ($order_statuses as $order_status) { ?>
					  <?php if ($order_status['order_status_id'] == $worldpay_entry_charged_back_status_id) { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label"><?php echo $entry_information_requested_status; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_entry_information_requested_status_id" class="form-control">
				  <?php foreach ($order_statuses as $order_status) { ?>
					  <?php if ($order_status['order_status_id'] == $worldpay_entry_information_requested_status_id) { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label"><?php echo $entry_information_supplied_status; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_entry_information_supplied_status_id" class="form-control">
				  <?php foreach ($order_statuses as $order_status) { ?>
					  <?php if ($order_status['order_status_id'] == $worldpay_entry_information_supplied_status_id) { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?>
				  <?php } ?>
				</select>
			  </div>
			</div>
			<div class="form-group">
			  <label class="col-sm-2 control-label"><?php echo $entry_chargeback_reversed_status; ?></label>
			  <div class="col-sm-10">
				<select name="worldpay_entry_chargeback_reversed_status_id" class="form-control">
				  <?php foreach ($order_statuses as $order_status) { ?>
					  <?php if ($order_status['order_status_id'] == $worldpay_entry_chargeback_reversed_status_id) { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
					  <?php } else { ?>
						  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
					  <?php } ?>
				  <?php } ?>
				</select>
			  </div>
			</div>
		  </div>
		</div>
	  </form>
	</div>
  </div>
</div>
<?php echo $footer; ?>