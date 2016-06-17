<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-laybuy" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $fetch; ?>" data-toggle="tooltip" title="<?php echo $button_fetch; ?>" class="btn btn-info"><i class="fa fa-refresh"></i></a>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-laybuy" class="form-horizontal">
		  <ul class="nav nav-tabs">
		    <li class="active" id="li-tab-settings"><a href="#tab-settings" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
		    <li class="" id="li-tab-reports"><a href="#tab-reports" data-toggle="tab"><?php echo $tab_reports; ?></a></li>
		  </ul>
		  <div class="tab-content">
		    <div class="tab-pane active" id="tab-settings">
			  <div class="form-group required">
			    <label class="col-sm-2 control-label" for="input-laybuys-membership-id"><span data-toggle="tooltip" title="<?php echo $help_membership_id; ?>"><?php echo $entry_membership_id; ?></span></label>
			    <div class="col-sm-10">
			      <input type="text" name="laybuys_membership_id" value="<?php echo $laybuys_membership_id; ?>" placeholder="<?php echo $entry_membership_id; ?>" id="input-laybuys-membership-id" class="form-control" />
				  <?php if ($error_laybuys_membership_id) { ?>
              	    <div class="text-danger"><?php echo $error_laybuys_membership_id; ?></div>
                  <?php } ?>
			    </div>
			  </div>
			  <div class="form-group required">
			    <label class="col-sm-2 control-label" for="input-laybuy-token"><span data-toggle="tooltip" title="<?php echo $help_token; ?>"><?php echo $entry_token; ?></span></label>
			    <div class="col-sm-10">
			      <input type="text" name="laybuy_token" value="<?php echo $laybuy_token; ?>" placeholder="<?php echo $entry_token; ?>" id="input-laybuy-token" class="form-control" />
				  <?php if ($error_laybuy_token) { ?>
              	    <div class="text-danger"><?php echo $error_laybuy_token; ?></div>
                  <?php } ?>
			    </div>
			  </div>
		      <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-laybuy-min-deposit"><span data-toggle="tooltip" title="<?php echo $help_minimum; ?>"><?php echo $entry_minimum; ?></span></label>
			    <div class="col-sm-10">
		          <select name="laybuy_min_deposit" id="input-laybuy-min-deposit" class="form-control">
                      <option value="10" <?php if ($laybuy_min_deposit == '10') { echo 'selected="selected"'; } ?> >10%</option>
                      <option value="20" <?php if ($laybuy_min_deposit == '20') { echo 'selected="selected"'; } ?> >20%</option>
                      <option value="30" <?php if ($laybuy_min_deposit == '30') { echo 'selected="selected"'; } ?> >30%</option>
                      <option value="40" <?php if ($laybuy_min_deposit == '40') { echo 'selected="selected"'; } ?> >40%</option>
                      <option value="50" <?php if ($laybuy_min_deposit == '50') { echo 'selected="selected"'; } ?> >50%</option>
			      </select>
				  <?php if ($error_laybuy_min_deposit) { ?>
              	    <div class="text-danger"><?php echo $error_laybuy_min_deposit; ?></div>
                  <?php } ?>
			    </div>
			  </div>
		      <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-laybuy-max-deposit"><span data-toggle="tooltip" title="<?php echo $help_maximum; ?>"><?php echo $entry_maximum; ?></span></label>
			    <div class="col-sm-10">
		          <select name="laybuy_max_deposit" id="input-laybuy-max-deposit" class="form-control">
                      <option value="10" <?php if ($laybuy_max_deposit == '10') { echo 'selected="selected"'; } ?> >10%</option>
                      <option value="20" <?php if ($laybuy_max_deposit == '20') { echo 'selected="selected"'; } ?> >20%</option>
                      <option value="30" <?php if ($laybuy_max_deposit == '30') { echo 'selected="selected"'; } ?> >30%</option>
                      <option value="40" <?php if ($laybuy_max_deposit == '40') { echo 'selected="selected"'; } ?> >40%</option>
                      <option value="50" <?php if ($laybuy_max_deposit == '50') { echo 'selected="selected"'; } ?> >50%</option>
			      </select>
			    </div>
			  </div>
			  <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-laybuy-max-months"><span data-toggle="tooltip" title="<?php echo $help_months; ?>"><?php echo $entry_max_months; ?></span></label>
			    <div class="col-sm-10">
		          <select name="laybuy_max_months" id="input-laybuy-max-months" class="form-control">
                      <option value="1" <?php if ($laybuy_max_months == '1') { echo 'selected="selected"'; } ?> >1</option>
                      <option value="2" <?php if ($laybuy_max_months == '2') { echo 'selected="selected"'; } ?> >2</option>
                      <option value="3" <?php if ($laybuy_max_months == '3') { echo 'selected="selected"'; } ?> >3</option>
                      <option value="4" <?php if ($laybuy_max_months == '4') { echo 'selected="selected"'; } ?> >4</option>
                      <option value="5" <?php if ($laybuy_max_months == '5') { echo 'selected="selected"'; } ?> >5</option>
                      <option value="6" <?php if ($laybuy_max_months == '6') { echo 'selected="selected"'; } ?> >6</option>
			      </select>
			    </div>
			  </div>
			  <div class="form-group">
			    <label class="col-sm-2 control-label" for="category"><span data-toggle="tooltip" title="<?php echo $help_category; ?>"><?php echo $entry_category; ?></span></label>
			    <div class="col-sm-10">
			      <input type="text" name="category" value="" placeholder="<?php echo $entry_category; ?>" id="category" class="form-control" />
				  <div id="laybuy-category" class="well well-sm" style="height: 150px; overflow: auto;">
				    <?php foreach ($categories as $category) { ?>
				      <div id="category<?php echo $category['category_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $category['name']; ?>
					    <input type="hidden" name="laybuy_category[]" value="<?php echo $category['category_id']; ?>" />
					  </div>
				    <?php } ?>
				  </div>
			    </div>
			  </div>
			  <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-laybuy-xproducts"><span data-toggle="tooltip" title="<?php echo $help_product_ids; ?>"><?php echo $entry_product_ids; ?></span></label>
			    <div class="col-sm-10">
			      <input type="text" name="laybuy_xproducts" value="<?php echo $laybuy_xproducts; ?>" placeholder="<?php echo $entry_product_ids; ?>" id="input-laybuy-xproducts" class="form-control" />
			    </div>
			  </div>
			  <div class="form-group">
			    <label class="col-sm-2 control-label" for="customer-group"><span data-toggle="tooltip" title="<?php echo $help_customer_group; ?>"><?php echo $entry_customer_group; ?></span></label>
			    <div class="col-sm-10">
			      <input type="text" name="customer_group" value="" placeholder="<?php echo $entry_customer_group; ?>" id="customer-group" class="form-control" />
				  <div id="laybuy-customer-group" class="well well-sm" style="height: 150px; overflow: auto;">
				    <?php foreach ($customer_groups as $customer_group) { ?>
				      <div id="customer-group<?php echo $customer_group['customer_group_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo $customer_group['name']; ?>
					    <input type="hidden" name="laybuy_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
					  </div>
				    <?php } ?>
				  </div>
			    </div>
			  </div>
		      <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-laybuy-logging"><span data-toggle="tooltip" title="<?php echo $help_logging; ?>"><?php echo $entry_logging; ?></span></label>
			    <div class="col-sm-10">
		          <select name="laybuy_logging" id="input-laybuy-logging" class="form-control">
                    <?php if ($laybuy_logging) { ?>
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
			    <label class="col-sm-2 control-label" for="input-laybuy-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
			    <div class="col-sm-10">
			      <input type="text" name="laybuy_total" value="<?php echo $laybuy_total; ?>" placeholder="<?php echo $entry_total; ?>" id="input-laybuy-total" class="form-control" />
			    </div>
			  </div>
		      <div class="form-group">
                <label class="col-sm-2 control-label" for="input-laybuy-order-status-pending"><span data-toggle="tooltip" title="<?php echo $help_order_status_pending; ?>"><?php echo $entry_order_status_pending; ?></span></label>
                <div class="col-sm-10">
                  <select name="laybuy_order_status_id_pending" id="input-laybuy-order-status-pending" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $laybuy_order_status_id_pending) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
		      <div class="form-group">
                <label class="col-sm-2 control-label" for="input-laybuy-order-status-canceled"><span data-toggle="tooltip" title="<?php echo $help_order_status_canceled; ?>"><?php echo $entry_order_status_canceled; ?></span></label>
                <div class="col-sm-10">
                  <select name="laybuy_order_status_id_canceled" id="input-laybuy-order-status-canceled" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $laybuy_order_status_id_canceled) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
		      <div class="form-group">
                <label class="col-sm-2 control-label" for="input-laybuy-order-status-processing"><span data-toggle="tooltip" title="<?php echo $help_order_status_processing; ?>"><?php echo $entry_order_status_processing; ?></span></label>
                <div class="col-sm-10">
                  <select name="laybuy_order_status_id_processing" id="input-laybuy-order-status-processing" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $laybuy_order_status_id_processing) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
			  <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-laybuy-gateway-url"><?php echo $entry_gateway_url; ?></label>
			    <div class="col-sm-10">
			      <input type="text" name="laybuy_gateway_url" value="<?php echo $laybuy_gateway_url; ?>" placeholder="<?php echo $entry_gateway_url; ?>" id="input-laybuy-gateway-url" class="form-control" />
			    </div>
			  </div>
			  <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-laybuy-api-url"><?php echo $entry_api_url; ?></label>
			    <div class="col-sm-10">
			      <input type="text" name="laybuy_api_url" value="<?php echo $laybuy_api_url; ?>" placeholder="<?php echo $entry_api_url; ?>" id="input-laybuy-api-url" class="form-control" />
			    </div>
			  </div>
			  <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-laybuy-geo-zone"><?php echo $entry_geo_zone; ?></label>
			    <div class="col-sm-10">
			      <select name="laybuy_geo_zone" id="input-laybuy-geo-zone" class="form-control">
				    <option value="0"><?php echo $text_all_zones; ?></option>
				    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $laybuy_geo_zone) { ?>
				        <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
					  <?php } else { ?>
				        <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
    			      <?php } ?>
				    <?php } ?>
				  </select>
			    </div>
			  </div>
		      <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-laybuy-status"><?php echo $entry_status; ?></label>
			    <div class="col-sm-10">
		          <select name="laybuy_status" id="input-laybuy-status" class="form-control">
                    <?php if ($laybuy_status) { ?>
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
		        <label class="col-sm-2 control-label" for="input-laybuy-sort-order"><?php echo $entry_sort_order; ?></label>
			    <div class="col-sm-10">
			      <input type="text" name="laybuy_sort_order" value="<?php echo $laybuy_sort_order; ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-laybuy-sort-order" class="form-control" />
			    </div>
			  </div>
		      <div class="form-group">
              <label class="col-sm-2 control-label" for="input-laybuy-cron-url"><span data-toggle="tooltip" title="<?php echo $help_cron_url; ?>"><?php echo $entry_cron_url; ?></span></label>
			    <div class="col-sm-10">
				  <input type="text" name="laybuy_cron_url" value="<?php echo $laybuy_cron_url; ?>" readonly placeholder="<?php echo $entry_cron_url; ?>" id="input-laybuy-cron-url" class="form-control" />
			    </div>
			  </div>
		      <div class="form-group">
              <label class="col-sm-2 control-label" for="input-laybuy-cron-time"><span data-toggle="tooltip" title="<?php echo $help_cron_time; ?>"><?php echo $entry_cron_time; ?></span></label>
			    <div class="col-sm-10">
				  <input type="text" name="laybuy_cron_time" value="<?php echo $laybuy_cron_time; ?>" readonly disabled placeholder="<?php echo $entry_cron_time; ?>" id="input-laybuy-cron-time" class="form-control" />
			    </div>
			  </div>
		    </div>
  		    <div class="tab-pane" id="tab-reports">
		      <div class="well">
	            <div class="row">
	              <div class="col-sm-4">
	                <div class="form-group">
	                  <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
	                  <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
	                </div>
				    <div class="form-group">
	                  <label class="control-label" for="input-months"><?php echo $entry_months; ?></label>
	                  <input type="text" name="filter_months" value="<?php echo $filter_months; ?>" placeholder="<?php echo $entry_months; ?>" id="input-months" class="form-control" />
	                </div>
	              </div>
	              <div class="col-sm-4">
                    <div class="form-group">
                      <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                      <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
                    </div>
				    <div class="form-group">
	                  <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
	                  <select name="filter_status" id="input-status" class="form-control" />
	                    <option value="*"></option>
	                    <?php foreach ($transaction_statuses as $transaction_status) { ?>
					      <?php if ($transaction_status['status_id'] == $filter_status) { ?>
					        <option value="<?php echo $transaction_status['status_id']; ?>" selected="selected"><?php echo $transaction_status['status_name']; ?></option>
					      <?php } else { ?>
					        <option value="<?php echo $transaction_status['status_id']; ?>"><?php echo $transaction_status['status_name']; ?></option>
				   	      <?php } ?>
					    <?php } ?>
	                  </select>
	                </div>
	              </div>
	              <div class="col-sm-4">
	                <div class="form-group">
	                  <label class="control-label" for="input-dp-percent"><?php echo $entry_dp_percent; ?></label>
	                  <input type="text" name="filter_dp_percent" value="<?php echo $filter_dp_percent; ?>" placeholder="<?php echo $entry_dp_percent; ?>" id="input-dp-percent" class="form-control" />
	                </div>
				    <div class="form-group">
	                  <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
	                  <div class="input-group date">
	                    <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
	                    <span class="input-group-btn">
	                    <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
	                    </span></div>
	                </div>
	                <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
	              </div>
	            </div>
	          </div>
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                      <td class="text-left"><?php if ($sort == 'lt.order_id') { ?>
                        <a href="<?php echo $sort_order_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_order_id; ?>"><?php echo $column_order_id; ?></a>
                        <?php } ?></td>
                      <td class="text-left"><?php if ($sort == 'customer') { ?>
                        <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                        <?php } ?></td>
                      <td class="text-left"><?php if ($sort == 'lt.amount') { ?>
                        <a href="<?php echo $sort_amount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_amount; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_amount; ?>"><?php echo $column_amount; ?></a>
                        <?php } ?></td>
                      <td class="text-left"><?php if ($sort == 'lt.downpayment') { ?>
                        <a href="<?php echo $sort_dp_percent; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_dp_percent; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_dp_percent; ?>"><?php echo $column_dp_percent; ?></a>
                        <?php } ?></td>
                      <td class="text-left"><?php if ($sort == 'lt.months') { ?>
                        <a href="<?php echo $sort_months; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_months; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_months; ?>"><?php echo $column_months; ?></a>
                        <?php } ?></td>
                      <td class="text-left"><?php if ($sort == 'lt.downpayment_amount') { ?>
                        <a href="<?php echo $sort_dp_amount; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_dp_amount; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_dp_amount; ?>"><?php echo $column_dp_amount; ?></a>
                        <?php } ?></td>
                      <td class="text-left"><?php if ($sort == 'lt.first_payment_due') { ?>
                        <a href="<?php echo $sort_first_payment; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_first_payment; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_first_payment; ?>"><?php echo $column_first_payment; ?></a>
                        <?php } ?></td>
                      <td class="text-left"><?php if ($sort == 'lt.last_payment_due') { ?>
                        <a href="<?php echo $sort_last_payment; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_last_payment; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_last_payment; ?>"><?php echo $column_last_payment; ?></a>
                        <?php } ?></td>
                      <td class="text-left"><?php if ($sort == 'lt.status') { ?>
                        <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                        <?php } ?></td>
                      <td class="text-left"><?php if ($sort == 'lt.date_added') { ?>
                        <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                        <?php } ?></td>
                      <td class="text-right"><?php echo $column_action; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($reports) { ?>
                    <?php foreach ($reports as $report) { ?>
                    <tr>
                      <td class="text-center"><?php if (in_array($report['id'], $selected)) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $report['id']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $report['id']; ?>" />
                        <?php } ?></td>
                      <td class="text-left"><a href="<?php echo $report['order_url']; ?>"><?php echo $report['order_id']; ?></a></td>
					  <?php if ($report['customer_url']) { ?>
                        <td class="text-left"><a href="<?php echo $report['customer_url']; ?>"><?php echo $report['customer_name']; ?></a></td>
					  <?php } else { ?>
                        <td class="text-left"><?php echo $report['customer_name']; ?></td>
					  <?php } ?>
                      <td class="text-left"><?php echo $report['amount']; ?></td>
                      <td class="text-left"><?php echo $report['dp_percent'] . '%'; ?></td>
                      <td class="text-left"><?php echo $report['months']; ?></td>
                      <td class="text-left"><?php echo $report['dp_amount']; ?></td>
                      <td class="text-left"><?php echo $report['first_payment']; ?></td>
                      <td class="text-left"><?php echo $report['last_payment']; ?></td>
                      <td class="text-left"><?php echo $report['status']; ?></td>
                      <td class="text-left"><?php echo $report['date_added']; ?></td>
                      <td class="text-right"><a href="<?php echo $report['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="12"><?php echo $text_no_results; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
	          <div class="row">
	            <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
	            <div class="col-sm-6 text-right"><?php echo $results; ?></div>
	          </div>
		    </div>
		  </div>
        </form>
      </div>
    </div>
  </div>
</div>

<style>
#tab-reports .form-group {
	margin-left: 0;
	margin-right: 0;
}
</style>

<script type="text/javascript"><!--
	$('input[name="category"]').autocomplete({
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['category_id']
						}
					}));
				}
			});
		},
		select: function(item) {
			$('input[name=\'category\']').val('');

			$('#laybuy-category' + item['value']).remove();

			$('#laybuy-category').append('<div id="laybuy-category' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="laybuy_category[]" value="' + item['value'] + '" /></div>');
		}
	});

	$('#laybuy-category').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});
//--></script>

<script type="text/javascript"><!--
	$('input[name="customer_group"]').autocomplete({
		source: function(request, response) {
			$.ajax({
				url: 'index.php?route=extension/payment/laybuy/autocomplete&token=<?php echo $token; ?>&filter_customer_group=' +  encodeURIComponent(request),
				dataType: 'json',
				success: function(json) {
					response($.map(json, function(item) {
						return {
							label: item['name'],
							value: item['customer_group_id']
						}
					}));
				}
			});
		},
		select: function(item) {
			$('input[name=\'customer_group\']').val('');

			$('#laybuy-customer-group' + item['value']).remove();

			$('#laybuy-customer-group').append('<div id="laybuy-customer-group' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="laybuy_customer_group[]" value="' + item['value'] + '" /></div>');
		}
	});

	$('#laybuy-customer-group').delegate('.fa-minus-circle', 'click', function() {
		$(this).parent().remove();
	});
//--></script>

<script type="text/javascript"><!--
$('input[name=\'filter_customer\']').autocomplete({
	'source': function(request, response) {
		$.ajax({
			url: 'index.php?route=customer/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						label: item['name'],
						value: item['customer_id']
					}
				}));
			}
		});
	},
	'select': function(item) {
		$('input[name=\'filter_customer\']').val(item['label']);
	}
});
//--></script>

<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=extension/payment/laybuy&token=<?php echo $token; ?>';

	var filter_order_id = $('input[name=\'filter_order_id\']').val();

	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_customer = $('input[name=\'filter_customer\']').val();

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}

	var filter_dp_percent = $('input[name=\'filter_dp_percent\']').val();

	if (filter_dp_percent) {
		url += '&filter_dp_percent=' + encodeURIComponent(filter_dp_percent);
	}

	var filter_months = $('input[name=\'filter_months\']').val();

	if (filter_months) {
		url += '&filter_months=' + encodeURIComponent(filter_months);
	}

	var filter_status = $('select[name=\'filter_status\']').val();

	if (filter_status != '*') {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}

	var filter_date_added = $('input[name=\'filter_date_added\']').val();

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	location = url + '#reportstab';
});
//--></script>

<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false,
	format: 'YYYY-MM-DD'
});
//--></script>

<script type="text/javascript"><!--
if (document.location.hash != '') {
	var hash = window.location.hash.substring(1);

	if (hash == 'settingstab') {
		hash = 'tab-settings';
	} else {
		hash = 'tab-reports';
	}

	$(".nav-tabs li").removeClass('active');
	$(".tab-pane").removeClass('active');

	$("#" + hash).addClass('active');
	$("#li-" + hash).addClass('active');
}
//--></script>

<?php echo $footer; ?>