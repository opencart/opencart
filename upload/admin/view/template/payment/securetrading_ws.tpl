<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-sagepay-direct" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
			</div>
			<div class="panel-body">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-securetrading-ws" class="form-horizontal">
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="securetrading_ws_site_reference"><?php echo $entry_site_reference; ?></label>
						<div class="col-sm-10">
							<input type="text" name="securetrading_ws_site_reference" value="<?php echo $securetrading_ws_site_reference; ?>" placeholder="<?php echo $entry_site_reference; ?>" id="securetrading_ws_site_reference" class="form-control" />
							<?php if ($error_site_reference) { ?>
								<div class="text-danger"><?php echo $error_site_reference; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="securetrading_ws_username"><?php echo $entry_username; ?></label>
						<div class="col-sm-10">
							<input type="text" name="securetrading_ws_username" value="<?php echo $securetrading_ws_username; ?>" placeholder="<?php echo $entry_username; ?>" id="securetrading_ws_username" class="form-control" />
							<?php if ($error_username) { ?>
								<div class="text-danger"><?php echo $error_username; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="securetrading_ws_password"><?php echo $entry_password; ?></label>
						<div class="col-sm-10">
							<input type="text" name="securetrading_ws_password" value="<?php echo $securetrading_ws_password; ?>" placeholder="<?php echo $entry_password; ?>" id="securetrading_ws_password" class="form-control" />
							<?php if ($error_password) { ?>
								<div class="text-danger"><?php echo $error_password; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="securetrading_ws_csv_username"><?php echo $entry_csv_username; ?></label>
						<div class="col-sm-10">
							<input type="text" name="securetrading_ws_csv_username" value="<?php echo $securetrading_ws_csv_username; ?>" placeholder="<?php echo $entry_csv_username; ?>" id="securetrading_ws_csv_username" class="form-control" />
							<span class="help-block"><?php echo $help_csv_username; ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="securetrading_ws_csv_password"><?php echo $entry_csv_password; ?></label>
						<div class="col-sm-10">
							<input type="text" name="securetrading_ws_csv_password" value="<?php echo $securetrading_ws_csv_password; ?>" placeholder="<?php echo $entry_csv_password; ?>" id="securetrading_ws_csv_password" class="form-control" />
							<span class="help-block"><?php echo $help_csv_password; ?></span>
						</div>
					</div>
					<div class="form-group required">
						<label class="col-sm-2 control-label" for="securetrading_ws_cards_accepted"><?php echo $entry_cards_accepted; ?></label>
						<div class="col-sm-10">
							<?php foreach ($cards as $key => $value) { ?>
								<div class="checkbox">
									<label>
										<?php if (in_array($key, $securetrading_ws_cards_accepted)) { ?>
											<input type="checkbox" checked="checked" name="securetrading_ws_cards_accepted[]" value="<?php echo $key ?>" />
										<?php } else { ?>
											<input type="checkbox" name="securetrading_ws_cards_accepted[]" value="<?php echo $key ?>" />
										<?php } ?>
										<?php echo $value ?>
									</label>
								</div>
							<?php } ?>
							<?php if ($error_cards_accepted) { ?>
								<div class="text-danger"><?php echo $error_cards_accepted; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="securetrading_ws_settle_status"><?php echo $entry_settle_status; ?></label>
						<div class="col-sm-10">
							<select name="securetrading_ws_settle_status" id="securetrading_ws_settle_status" class="form-control">
								<?php foreach ($settlement_statuses as $key => $value) { ?>
									<?php if ($key == $securetrading_ws_settle_status) { ?>
										<option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
									<?php } else { ?>
										<option value="<?php echo $key; ?>"><?php echo $value; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="securetrading_ws_settle_due_date"><?php echo $entry_settle_due_date; ?></label>
						<div class="col-sm-10">
							<select name="securetrading_ws_settle_due_date" id="securetrading_ws_settle_due_date" class="form-control">
								<?php if ($securetrading_ws_settle_due_date == 0) { ?>
									<option value="0" selected="selected"><?php echo $text_process_immediately; ?></option>
								<?php } else { ?>
									<option value="0"><?php echo $text_process_immediately; ?></option>
								<?php } ?>
								<?php for ($i = 1; $i < 8; $i++) { ?>
									<?php if ($i == $securetrading_ws_settle_due_date) { ?>
										<option value="<?php echo $i ?>" selected="selected"><?php echo sprintf($text_wait_x_days, $i) ?></option>
									<?php } else { ?>
										<option value="<?php echo $i ?>"><?php echo sprintf($text_wait_x_days, $i) ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="securetrading_ws_total"><?php echo $entry_total; ?></label>
						<div class="col-sm-10">
							<input type="text" name="securetrading_ws_total" value="<?php echo $securetrading_ws_total; ?>" placeholder="<?php echo $entry_total; ?>" id="securetrading_ws_total" class="form-control" />
                            <span class="help-block"><?php echo $help_total; ?></span>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="securetrading_ws_order_status_id"><?php echo $entry_order_status; ?></label>
						<div class="col-sm-10">
							<select name="securetrading_ws_order_status_id" id="securetrading_ws_order_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
									<?php if ($order_status['order_status_id'] == $securetrading_ws_order_status_id) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="securetrading_ws_failed_order_status_id"><?php echo $entry_failed_order_status; ?></label>
						<div class="col-sm-10">
							<select name="securetrading_ws_failed_order_status_id" id="securetrading_ws_failed_order_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
									<?php if ($order_status['order_status_id'] == $securetrading_ws_failed_order_status_id) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="securetrading_ws_declined_order_status_id"><?php echo $entry_declined_order_status; ?></label>
						<div class="col-sm-10">
							<select name="securetrading_ws_declined_order_status_id" id="securetrading_ws_declined_order_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
									<?php if ($order_status['order_status_id'] == $securetrading_ws_declined_order_status_id) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="securetrading_ws_refunded_order_status_id"><?php echo $entry_refunded_order_status; ?></label>
						<div class="col-sm-10">
							<select name="securetrading_ws_refunded_order_status_id" id="securetrading_ws_refunded_order_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
									<?php if ($order_status['order_status_id'] == $securetrading_ws_refunded_order_status_id) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="securetrading_ws_authorisation_reversed_order_status_id"><?php echo $entry_authorisation_reversed_order_status; ?></label>
						<div class="col-sm-10">
							<select name="securetrading_ws_authorisation_reversed_order_status_id" id="securetrading_ws_authorisation_reversed_order_status_id" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
									<?php if ($order_status['order_status_id'] == $securetrading_ws_authorisation_reversed_order_status_id) { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="securetrading_ws_geo_zone_id"><?php echo $entry_geo_zone; ?></label>
						<div class="col-sm-10">
							<select name="securetrading_ws_geo_zone_id" id="securetrading_ws_geo_zone_id" class="form-control">
								<?php if ($securetrading_ws_geo_zone_id == 0) { ?>
									<option value="0" selected="selected"><?php echo $text_all_geo_zones; ?></option>
								<?php } else { ?>
									<option value="0"><?php echo $text_all_geo_zones; ?></option>
								<?php } ?>
								<?php foreach ($geo_zones as $geo_zone) { ?>
									<?php if ($securetrading_ws_geo_zone_id == $geo_zone['geo_zone_id']) { ?>
										<option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
									<?php } else { ?>
										<option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
									<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="securetrading_ws_status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
							<select name="securetrading_ws_status" id="securetrading_ws_status" class="form-control">
								<?php if ($securetrading_ws_status == 1) { ?>
									<option value="1" selected="selected"><?php echo $text_enabled; ?></option>
								<?php } else { ?>
									<option value="1"><?php echo $text_enabled; ?></option>
								<?php } ?>
								<?php if ($securetrading_ws_status == 0) { ?>
									<option value="0" selected="selected"><?php echo $text_disabled; ?></option>
								<?php } else { ?>
									<option value="0"><?php echo $text_disabled; ?></option>
								<?php } ?>
							</select>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="securetrading_ws_sort_order"><?php echo $entry_sort_order; ?></label>
						<div class="col-sm-10">
							<input type="text" name="securetrading_ws_sort_order" value="<?php echo $securetrading_ws_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="securetrading_ws_sort_order" class="form-control" />
						</div>
					</div>
				</form>
			</div>
			<?php if ($myst_status) { ?>
				<div class="tab-pane" id="tab-myst">
					<div class="well">
						<form id="transaction-form">
							<div class="row">
								<div class="col-sm-1">
									<div class="form-group">
										<label class="control-label" for="hour-from"><?php echo $entry_hour; ?></label>
										<div class="input-group">
											<select name="hour_from" id="hour-from" class="form-control">
												<?php foreach ($hours as $hour) { ?>
													<option value="<?php echo $hour ?>"><?php echo $hour ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-1">
									<div class="form-group">
										<label class="control-label" for="minute-from"><?php echo $entry_minute; ?></label>
										<div class="input-group">
											<select name="minute_from" id="minute-from" class="form-control">
												<?php foreach ($minutes as $minute) { ?>
													<option value="<?php echo $minute ?>"><?php echo $minute ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label class="control-label" for="date-from"><?php echo $entry_date_from; ?></label>
										<div class="input-group datetime">
											<input type="text" name="date_from" value="<?php echo date('Y-m-d'); ?>" placeholder="<?php echo $entry_date_from; ?>" data-format="YYYY-MM-DD" id="date-from" class="form-control" />
											<span class="input-group-btn">
												<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
								</div>
								<div class="col-sm-1 col-sm-offset-2">
									<div class="form-group">
										<label class="control-label" for="hour-to"><?php echo $entry_hour; ?></label>
										<div class="input-group">
											<select name="hour_to" id="hour-to" class="form-control">
												<?php foreach ($hours as $hour) { ?>
													<option value="<?php echo $hour ?>"><?php echo $hour ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-1">
									<div class="form-group">
										<label class="control-label" for="minute-to"><?php echo $entry_minute; ?></label>
										<div class="input-group">
											<select name="minute_to" id="minute-to" class="form-control">
												<?php foreach ($minutes as $minute) { ?>
													<option value="<?php echo $minute ?>"><?php echo $minute ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>
								<div class="col-sm-3">
									<div class="form-group">
										<label class="control-label" for="date-to"><?php echo $entry_date_to; ?></label>
										<div class="input-group datetime">
											<input type="text" name="date_to" value="<?php echo date('Y-m-d'); ?>" placeholder="<?php echo $entry_date_to; ?>" data-format="YYYY-MM-DD" id="date-to" class="form-control" />
											<span class="input-group-btn">
												<button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
											</span>
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label" for="request"><?php echo $entry_request; ?></label>
										<select name="request[]" id="request" multiple class="form-control" style="height: 150px">
											<option selected="selected">ACCOUNTCHECK</option>
											<option selected="selected">AUTH</option>
											<option selected="selected">FRAUDSCORE</option>
											<option selected="selected">ORDER</option>
											<option selected="selected">ORDERDETAILS</option>
											<option selected="selected">REFUND</option>
											<option selected="selected">THREEDQUERY</option>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label" for="currency"><?php echo $entry_currency; ?></label>
										<select name="currency[]" id="currency" multiple class="form-control" style="height: 150px">
											<?php foreach ($currencies as $currency) { ?>
												<option selected="selected" value="<?php echo $currency['code'] ?>"><?php echo $currency['title'] ?></option>
											<?php } ?>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label" for="payment-type"><?php echo $entry_payment_type; ?></label>
										<select name="payment_type[]" id="payment-type" multiple class="form-control" style="height: 150px">
											<option selected="selected">AMEX</option>
											<option selected="selected">DELTA</option>
											<option selected="selected">ELECTRON</option>
											<option selected="selected">MAESTRO</option>
											<option selected="selected">MASTERCARD</option>
											<option selected="selected">MASTERCARDDEBIT</option>
											<option selected="selected">PAYPAL</option>
											<option selected="selected">PURCHASING</option>
											<option selected="selected">VISA</option>
											<option selected="selected">VPAY</option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label" for="status"><?php echo $entry_status_code; ?></label>
										<select name="status[]" id="status" multiple class="form-control" style="height: 150px">
											<option selected="selected" value="0">0 - Ok</option>
											<option selected="selected" value="70000">70000 - Decline</option>
										</select>
									</div>
								</div>
								<div class="col-sm-4">
									<div class="form-group">
										<label class="control-label" for="settle-status"><?php echo $entry_settle_status; ?></label>
										<select name="settle_status[]" id="settle-status" multiple class="form-control" style="height: 150px">
											<option selected="selected" value="0">0 - <?php echo $text_pending_settlement ?></option>
											<option selected="selected" value="1">1 - <?php echo $text_manual_settlement ?></option>
											<option selected="selected" value="2">2 - <?php echo $text_suspended ?></option>
											<option selected="selected" value="3">3 - <?php echo $text_cancelled ?></option>
											<option selected="selected" value="10">10 - <?php echo $text_settling ?></option>
											<option selected="selected" value="100">100 - <?php echo $text_settled ?></option>
										</select>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-sm-12">
									<a class="btn btn-primary" onclick="showTransactions()"><?php echo $button_show ?></a>
									<a class="btn btn-primary" onclick="downloadTransactions()"><?php echo $button_download ?></a>
								</div>
							</div>
						</form>
					</div>
				</div>
			<?php } ?>
		</div>
	</div>
</div>
<script type="text/javascript">
	function downloadTransactions() {
		$('#download-iframe').remove();
		$('#transaction-form').after('<iframe name="download-iframe" id="download-iframe" style="display: none;" src="" />');

		$('#transaction-form').attr('method', 'POST');
		$('#transaction-form').attr('target', 'download-iframe');
		$('#transaction-form').attr('action', 'index.php?route=payment/securetrading_ws/downloadTransactions&token=<?php echo $token ?>');

		$('#transaction-form').submit();

		$('#transaction-form').removeAttr('method');
		$('#transaction-form').removeAttr('target');
		$('#transaction-form').removeAttr('action');
	}

	function showTransactions() {
		$.ajax({
			url: 'index.php?route=payment/securetrading_ws/showTransactions&token=<?php echo $token ?>',
			type: 'post',
			data: $('#transaction-form').serialize(),
			dataType: 'html',
			beforeSend: function() {
				$('.transactions').remove();
			},
			success: function(html) {
				$('.well').after(html);
			}
		});
	}

	$('.datetime').datetimepicker({
		pickTime: false
	});
</script>
<?php echo $footer; ?>