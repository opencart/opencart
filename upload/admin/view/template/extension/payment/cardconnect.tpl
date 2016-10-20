<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-cardconnect" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-cardconnect" class="form-horizontal">
		  <ul class="nav nav-tabs">
		    <li class="active" id="li-tab-settings"><a href="#tab-settings" data-toggle="tab"><?php echo $tab_settings; ?></a></li>
		    <li class="" id="li-tab-order-status"><a href="#tab-order-status" data-toggle="tab"><?php echo $tab_order_status; ?></a></li>
		  </ul>
		  <div class="tab-content">
		    <div class="tab-pane active" id="tab-settings">
			  <div class="form-group required">
			    <label class="col-sm-2 control-label" for="input-cardconnect-merchant-id"><span data-toggle="tooltip" title="<?php echo $help_merchant_id; ?>"><?php echo $entry_merchant_id; ?></span></label>
			    <div class="col-sm-10">
			      <input type="text" name="cardconnect_merchant_id" value="<?php echo $cardconnect_merchant_id; ?>" placeholder="<?php echo $entry_merchant_id; ?>" id="input-cardconnect-merchant-id" class="form-control" />
				  <?php if ($error_cardconnect_merchant_id) { ?>
              	    <div class="text-danger"><?php echo $error_cardconnect_merchant_id; ?></div>
                  <?php } ?>
			    </div>
			  </div>
			  <div class="form-group required">
			    <label class="col-sm-2 control-label" for="input-cardconnect-api-username"><span data-toggle="tooltip" title="<?php echo $help_api_username; ?>"><?php echo $entry_api_username; ?></span></label>
			    <div class="col-sm-10">
			      <input type="text" name="cardconnect_api_username" value="<?php echo $cardconnect_api_username; ?>" placeholder="<?php echo $entry_api_username; ?>" id="input-cardconnect-api-username" class="form-control" />
				  <?php if ($error_cardconnect_api_username) { ?>
              	    <div class="text-danger"><?php echo $error_cardconnect_api_username; ?></div>
                  <?php } ?>
			    </div>
			  </div>
			  <div class="form-group required">
			    <label class="col-sm-2 control-label" for="input-cardconnect-api-password"><span data-toggle="tooltip" title="<?php echo $help_api_password; ?>"><?php echo $entry_api_password; ?></span></label>
			    <div class="col-sm-10">
			      <input type="text" name="cardconnect_api_password" value="<?php echo $cardconnect_api_password; ?>" placeholder="<?php echo $entry_api_password; ?>" id="input-cardconnect-api-password" class="form-control" />
				  <?php if ($error_cardconnect_api_password) { ?>
              	    <div class="text-danger"><?php echo $error_cardconnect_api_password; ?></div>
                  <?php } ?>
			    </div>
			  </div>
			  <div class="form-group required">
			    <label class="col-sm-2 control-label" for="input-cardconnect-token"><span data-toggle="tooltip" title="<?php echo $help_token; ?>"><?php echo $entry_token; ?></span></label>
			    <div class="col-sm-10">
			      <input type="text" name="cardconnect_token" value="<?php echo $cardconnect_token; ?>" placeholder="<?php echo $entry_token; ?>" id="input-cardconnect-token" class="form-control" />
				  <?php if ($error_cardconnect_token) { ?>
              	    <div class="text-danger"><?php echo $error_cardconnect_token; ?></div>
                  <?php } ?>
			    </div>
			  </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-cardconnect-transaction"><span data-toggle="tooltip" title="<?php echo $help_transaction; ?>"><?php echo $entry_transaction; ?></span></label>
                <div class="col-sm-10">
                  <select name="cardconnect_transaction" id="input-cardconnect-transaction" class="form-control">
                    <?php if ($cardconnect_transaction == 'payment') { ?>
                    <option value="payment" selected="selected"><?php echo $text_payment; ?></option>
                    <?php } else { ?>
                    <option value="payment"><?php echo $text_payment; ?></option>
                    <?php } ?>
                    <?php if ($cardconnect_transaction == 'authorize') { ?>
                    <option value="authorize" selected="selected"><?php echo $text_authorize; ?></option>
                    <?php } else { ?>
                    <option value="authorize"><?php echo $text_authorize; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
			  <div class="form-group required">
			    <label class="col-sm-2 control-label" for="input-cardconnect-site"><span data-toggle="tooltip" title="<?php echo $help_site; ?>"><?php echo $entry_site; ?></span></label>
			    <div class="col-sm-10">
			      <input type="text" name="cardconnect_site" value="<?php echo $cardconnect_site; ?>" placeholder="<?php echo $entry_site; ?>" id="input-cardconnect-site" class="form-control" />
				  <?php if ($error_cardconnect_site) { ?>
              	    <div class="text-danger"><?php echo $error_cardconnect_site; ?></div>
                  <?php } ?>
			    </div>
			  </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-cardconnect-environment"><?php echo $entry_environment; ?></label>
                <div class="col-sm-10">
                  <select name="cardconnect_environment" id="input-cardconnect-environment" class="form-control">
                    <?php if ($cardconnect_environment == 'live') { ?>
                    <option value="live" selected="selected"><?php echo $text_live; ?></option>
                    <?php } else { ?>
                    <option value="live"><?php echo $text_live; ?></option>
                    <?php } ?>
                    <?php if ($cardconnect_environment == 'test') { ?>
                    <option value="test" selected="selected"><?php echo $text_test; ?></option>
                    <?php } else { ?>
                    <option value="test"><?php echo $text_test; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
		      <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-cardconnect-store-cards"><span data-toggle="tooltip" title="<?php echo $help_store_cards; ?>"><?php echo $entry_store_cards; ?></span></label>
			    <div class="col-sm-10">
		          <select name="cardconnect_store_cards" id="input-cardconnect-store-cards" class="form-control">
                    <?php if ($cardconnect_store_cards) { ?>
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
			    <label class="col-sm-2 control-label" for="input-cardconnect-echeck"><span data-toggle="tooltip" title="<?php echo $help_echeck; ?>"><?php echo $entry_echeck; ?></span></label>
			    <div class="col-sm-10">
		          <select name="cardconnect_echeck" id="input-cardconnect-echeck" class="form-control">
                    <?php if ($cardconnect_echeck) { ?>
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
			    <label class="col-sm-2 control-label" for="input-cardconnect-total"><span data-toggle="tooltip" title="<?php echo $help_total; ?>"><?php echo $entry_total; ?></span></label>
			    <div class="col-sm-10">
			      <input type="text" name="cardconnect_total" value="<?php echo $cardconnect_total ?>" placeholder="<?php echo $entry_total; ?>" id="input-cardconnect-total" class="form-control" />
			    </div>
			  </div>
			  <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-cardconnect-geo-zone"><?php echo $entry_geo_zone; ?></label>
			    <div class="col-sm-10">
			      <select name="cardconnect_geo_zone" id="input-cardconnect-geo-zone" class="form-control">
				    <option value="0"><?php echo $text_all_zones; ?></option>
				    <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $cardconnect_geo_zone) { ?>
				        <option value="<?php echo $geo_zone['geo_zone_id'] ?>" selected="selected"><?php echo $geo_zone['name'] ?></option>
					  <?php } else { ?>
				        <option value="<?php echo $geo_zone['geo_zone_id'] ?>"><?php echo $geo_zone['name'] ?></option>
    			      <?php } ?>
				    <?php } ?>
				  </select>
			    </div>
			  </div>
		      <div class="form-group">
			    <label class="col-sm-2 control-label" for="input-cardconnect-status"><?php echo $entry_status; ?></label>
			    <div class="col-sm-10">
		          <select name="cardconnect_status" id="input-cardconnect-status" class="form-control">
                    <?php if ($cardconnect_status) { ?>
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
			    <label class="col-sm-2 control-label" for="input-cardconnect-logging"><span data-toggle="tooltip" title="<?php echo $help_logging; ?>"><?php echo $entry_logging; ?></span></label>
			    <div class="col-sm-10">
		          <select name="cardconnect_logging" id="input-cardconnect-logging" class="form-control">
                    <?php if ($cardconnect_logging) { ?>
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
		        <label class="col-sm-2 control-label" for="input-cardconnect-sort-order"><?php echo $entry_sort_order; ?></label>
			    <div class="col-sm-10">
			      <input type="text" name="cardconnect_sort_order" value="<?php echo $cardconnect_sort_order ?>" placeholder="<?php echo $entry_sort_order; ?>" id="input-cardconnect-sort-order" class="form-control" />
			    </div>
			  </div>
		      <div class="form-group">
                <label class="col-sm-2 control-label" for="input-cardconnect-cron-url"><span data-toggle="tooltip" title="<?php echo $help_cron_url; ?>"><?php echo $entry_cron_url; ?></span></label>
			    <div class="col-sm-10">
				  <input type="text" name="cardconnect_cron_url" value="<?php echo $cardconnect_cron_url ?>" readonly placeholder="<?php echo $entry_cron_url; ?>" id="input-cardconnect-cron-url" class="form-control" />
			    </div>
			  </div>
		      <div class="form-group">
                <label class="col-sm-2 control-label" for="input-cardconnect-cron-time"><span data-toggle="tooltip" title="<?php echo $help_cron_time; ?>"><?php echo $entry_cron_time; ?></span></label>
			    <div class="col-sm-10">
				  <input type="text" name="cardconnect_cron_time" value="<?php echo $cardconnect_cron_time ?>" readonly disabled placeholder="<?php echo $entry_cron_time; ?>" id="input-cardconnect-cron-time" class="form-control" />
			    </div>
			  </div>
		    </div>
  		    <div class="tab-pane" id="tab-order-status">
		      <div class="form-group">
                <label class="col-sm-2 control-label" for="input-cardconnect-order-status-pending"><span data-toggle="tooltip" title="<?php echo $help_order_status_pending; ?>"><?php echo $entry_order_status_pending; ?></span></label>
                <div class="col-sm-10">
                  <select name="cardconnect_order_status_id_pending" id="input-cardconnect-order-status-pending" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $cardconnect_order_status_id_pending) { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                      <?php } else { ?>
                        <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                      <?php } ?>
                    <?php } ?>
                  </select>
                </div>
              </div>
		      <div class="form-group">
                <label class="col-sm-2 control-label" for="input-cardconnect-order-status-processing"><span data-toggle="tooltip" title="<?php echo $help_order_status_processing; ?>"><?php echo $entry_order_status_processing; ?></span></label>
                <div class="col-sm-10">
                  <select name="cardconnect_order_status_id_processing" id="input-cardconnect-order-status-processing" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                      <?php if ($order_status['order_status_id'] == $cardconnect_order_status_id_processing) { ?>
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
</div>

<?php echo $footer; ?>