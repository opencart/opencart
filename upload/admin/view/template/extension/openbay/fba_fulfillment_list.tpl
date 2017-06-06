<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_fulfillment_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <label class="control-label" for="input-date"><?php echo $entry_start_date; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date" value="<?php echo $filter_date; ?>" placeholder="YYYY-MM-DD" data-date-format="YYYY-MM-DD" id="input-date" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
            </div>
            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-left"><?php echo $column_seller_fulfillment_order_id; ?></th>
                <th class="text-left"><?php echo $column_displayable_order_id; ?></th>
                <th class="text-left"><?php echo $column_displayable_order_date; ?></th>
                <th class="text-left"><?php echo $column_shipping_speed_category; ?></th>
                <th class="text-left"><?php echo $column_fulfillment_order_status; ?></th>
                <td class="text-left"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($fulfillments)) { ?>
                <tr>
                  <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                </tr>
              <?php } else { ?>
                <?php foreach ($fulfillments as $fulfillment) { ?>
                  <tr>
                    <td class="text-left"><?php echo $fulfillment['seller_fulfillment_order_id']; ?></td>
                    <td class="text-left"><?php echo $fulfillment['displayable_order_id']; ?></td>
                    <td class="text-left"><?php echo $fulfillment['displayable_order_date_time']; ?></td>
                    <td class="text-left"><?php echo $fulfillment['shipping_speed_category']; ?></td>
                    <td class="text-left"><?php echo $fulfillment['fulfillment_order_status']; ?></td>
                    <td class="text-right"><a href="<?php echo $fulfillment['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                  </tr>
                <?php } ?>
              <?php } ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
  var url = 'index.php?route=extension/openbay/fba/fulfillmentlist&token=<?php echo $token; ?>';

  var filter_date = $('input[name=\'filter_date\']').val();

  if (filter_date) {
    url += '&filter_date=' + encodeURIComponent(filter_date);
  }

  var filter_time = $('input[name=\'filter_time\']').val();

  if (filter_time) {
    url += '&filter_time=' + encodeURIComponent(filter_time);
  }

  location = url;
});
//--></script>
<script src="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js" type="text/javascript"></script>
<link href="view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css" type="text/css" rel="stylesheet" media="screen" />
<script type="text/javascript"><!--
$('.date').datetimepicker({
  pickTime: false
});
//--></script>
<?php echo $footer; ?>