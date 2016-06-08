<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <div class="form-horizontal">
          <fieldset>
            <legend><?php echo $heading_order_info; ?></legend>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-seller-fulfillment-order-id"><?php echo $entry_seller_fulfillment_order_id; ?></label>
              <div class="col-sm-10">
                <input disabled type="text" name="seller_fulfillment_order_id" value="<?php echo $response['seller_fulfillment_order_id']; ?>" placeholder="<?php echo $entry_seller_fulfillment_order_id; ?>" id="input-seller-fulfillment-order-id" class="form-control disabled" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-displayable-order-id"><?php echo $entry_displayable_order_id; ?></label>
              <div class="col-sm-10">
                <input disabled type="text" name="displayable_order_id" value="<?php echo $response['displayable_order_id']; ?>" placeholder="<?php echo $entry_displayable_order_id; ?>" id="input-displayable-order-id" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-displayable-date"><?php echo $entry_displayable_date; ?></label>
              <div class="col-sm-10">
                <input disabled type="text" name="displayable_date" value="<?php echo $response['displayable_order_date_time']; ?>" placeholder="<?php echo $entry_displayable_date; ?>" id="input-displayable-date" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-displayable-comment"><?php echo $entry_displayable_comment; ?></label>
              <div class="col-sm-10">
                <input disabled type="text" name="displayable_comment" value="<?php echo $response['displayable_order_comment']; ?>" placeholder="<?php echo $entry_displayable_comment; ?>" id="input-displayable-comment" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-shipping-speed-category"><?php echo $entry_shipping_speed_category; ?></label>
              <div class="col-sm-10">
                <input disabled type="text" name="shipping_speed_category" value="<?php echo $response['shipping_speed_category']; ?>" placeholder="<?php echo $entry_shipping_speed_category; ?>" id="input-shipping-speed-category" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-fulfillment-policy"><?php echo $entry_fulfillment_policy; ?></label>
              <div class="col-sm-10">
                <input disabled type="text" name="fulfillment_policy" value="<?php echo $response['fulfillment_policy']; ?>" placeholder="<?php echo $entry_fulfillment_policy; ?>" id="input-fulfillment-policy" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-fulfillment-order-status"><?php echo $entry_fulfillment_order_status; ?></label>
              <div class="col-sm-10">
                <input disabled type="text" name="fulfillment_order_status" value="<?php echo $response['fulfillment_order_status']; ?>" placeholder="<?php echo $entry_fulfillment_order_status; ?>" id="input-fulfillment-order-status" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-notification-email-list"><?php echo $entry_notification_email_list; ?></label>
              <div class="col-sm-10">
                <input disabled type="text" name="notification_email_list" value="<?php echo $response['notification_email_list']; ?>" placeholder="<?php echo $entry_notification_email_list; ?>" id="input-notification-email-list" class="form-control" />
              </div>
            </div>
          </fieldset>
          <fieldset>
            <legend><?php echo $heading_products; ?></legend>
            <div class="table-responsive">
              <table class="table table-bordered table-hover">
                <thead>
                  <tr>
                    <td class="text-left"><?php echo $column_sku; ?></td>
                    <td class="text-left"><?php echo $column_order_item_id; ?></td>
                    <td class="text-left"><?php echo $column_quantity; ?></td>
                    <td class="text-left"><?php echo $column_cancelled_quantity; ?></td>
                    <td class="text-left"><?php echo $column_unfulfillable_quantity; ?></td>
                    <td class="text-left"><?php echo $column_estimated_ship; ?></td>
                    <td class="text-left"><?php echo $column_estimated_arrive; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($response['items'] as $item) { ?>
                    <tr>
                      <td class="text-left"><?php echo $item['seller_sku']; ?></td>
                      <td class="text-left"><?php echo $item['seller_fulfillment_order_item_id']; ?></td>
                      <td class="text-left"><?php echo $item['quantity']; ?></td>
                      <td class="text-left"><?php echo $item['cancelled_quantity']; ?></td>
                      <td class="text-left"><?php echo $item['unfulfillable_quantity']; ?></td>
                      <td class="text-left"><?php echo $item['estimated_ship_date_time']; ?></td>
                      <td class="text-left"><?php echo $item['estimated_arrival_date_time']; ?></td>
                    </tr>
                  <?php } ?>
                </tbody>
              </table>
            </div>
          </fieldset>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
