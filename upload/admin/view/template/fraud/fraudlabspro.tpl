<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
	<div class="page-header">
		<div class="container-fluid">
			<div class="pull-right">
				<button type="submit" form="form-google-base" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
				<h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
			</div>
			<div class="panel-body">
				<div class="alert alert-info"><i class="fa fa-exclamation-circle"></i> <?php echo $text_signup; ?></div>
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-google-base" class="form-horizontal">
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
						<div class="col-sm-10">
							<select name="fraudlabspro_status" id="input-status" class="form-control">
								<?php if ($fraudlabspro_status) { ?>
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
						<label class="col-sm-2 control-label" for="input-key"><?php echo $entry_key; ?></label>
						<div class="col-sm-10">
							<input type="text" name="fraudlabspro_key" value="<?php echo $fraudlabspro_key; ?>" placeholder="<?php echo $entry_key; ?>" id="input-key" class="form-control" />
							<?php if ($error_key) { ?>
							<div class="text-danger"><?php echo $error_key; ?></div>
							<?php } ?>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-score"><span data-toggle="tooltip" title="<?php echo $help_score; ?>"><?php echo $entry_score; ?></span></label>
						<div class="col-sm-10">
							<input type="text" name="fraudlabspro_score" value="<?php echo $fraudlabspro_score; ?>" placeholder="<?php echo $entry_score; ?>" id="input-score" class="form-control" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label" for="input-order-status"><span data-toggle="tooltip" title="<?php echo $help_order_status; ?>"><?php echo $entry_order_status; ?></span></label>
						<div class="col-sm-10">
							<select name="fraudlabspro_order_status_id" id="input-order-status" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $fraudlabspro_order_status_id) { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
								<?php } ?>
							</select>
						</div>
					</div>
					<fieldset>
						<legend>Rules Validation</legend>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-fraud-review-status"><span data-toggle="tooltip" title="<?php echo $help_review_status; ?>"><?php echo $entry_review_status; ?></span></label>
							<div class="col-sm-10">
								<select name="fraudlabspro_review_status_id" id="input-fraud-review-status" class="form-control">
									<?php foreach ($order_statuses as $order_status) { ?>
									<?php if ($order_status['order_status_id'] == $fraudlabspro_review_status_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-fraud-approve-status"><span data-toggle="tooltip" title="<?php echo $help_approve_status; ?>"><?php echo $entry_approve_status; ?></span></label>
							<div class="col-sm-10">
								<select name="fraudlabspro_approve_status_id" id="input-fraud-approve-status" class="form-control">
									<?php foreach ($order_statuses as $order_status) { ?>
									<?php if ($order_status['order_status_id'] == $fraudlabspro_approve_status_id) { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
									<?php } else { ?>
									<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
									<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-fraud-reject-status"><span data-toggle="tooltip" title="<?php echo $help_reject_status; ?>"><?php echo $entry_reject_status; ?></span></label>
							<div class="col-sm-10">
								<select name="fraudlabspro_reject_status_id" id="input-fraud-reject-status" class="form-control">
								<?php foreach ($order_statuses as $order_status) { ?>
								<?php if ($order_status['order_status_id'] == $fraudlabspro_reject_status_id) { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
								<?php } else { ?>
								<option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
								<?php } ?>
								<?php } ?>
								</select>
							</div>
						</div>
					</fieldset>
					<fieldset>
						<legend>Testing Purpose</legend>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="input-score"><span data-toggle="tooltip" title="<?php echo $help_simulate_ip; ?>"><?php echo $entry_simulate_ip; ?></span></label>
							<div class="col-sm-10">
								<input type="text" name="fraudlabspro_simulate_ip" value="<?php echo $fraudlabspro_simulate_ip; ?>" placeholder="<?php echo $entry_simulate_ip; ?>" id="input-score" class="form-control" />
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
<?php echo $footer; ?>