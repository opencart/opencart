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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_order_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-start-date"><?php echo $entry_start_date; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_start" value="<?php echo $filter_start; ?>" placeholder="YYYY-MM-DD" data-date-format="YYYY-MM-DD" id="input-start-date" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-end-date"><?php echo $entry_end_date; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_end" value="<?php echo $filter_end; ?>" placeholder="YYYY-MM-DD" data-date-format="YYYY-MM-DD" id="input-end-date" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span>
                </div>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-status"><?php echo $entry_status; ?></label>
                <select name="filter_status" id="input-status" class="form-control">
                  <option value=""<?php echo (is_null($filter_status) ? ' selected' : '') ?>><?php echo $text_option_all; ?></option>
                  <?php foreach ($status_options as $option_key => $option) { ?>
                    <option value="<?php echo $option_key; ?>"<?php echo (!is_null($filter_status) && ($filter_status == $option_key) ? ' selected' : '') ?>><?php echo $option; ?></option>
                  <?php } ?>
                </select>
              </div>
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-filter"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th class="text-left"><?php echo $column_order_id; ?></th>
                <th class="text-left"><?php echo $column_created; ?></th>
                <th class="text-left"><?php echo $column_status; ?></th>
                <th class="text-center"><?php echo $column_fba_item_count; ?></th>
                <td class="text-right"><?php echo $column_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if (empty($orders)) { ?>
                <tr>
                  <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
                </tr>
              <?php } else { ?>
                <?php foreach ($orders as $order) { ?>
                  <tr>
                    <td class="text-left"><a href="<?php echo $order['order_link']; ?>"><?php echo $order['order_id']; ?></a></td>
                    <td class="text-left"><?php echo $order['created']; ?></td>
                    <td class="text-left"><?php echo $status_options[$order['status']]; ?></td>
                    <td class="text-center"><?php echo $order['fba_item_count']; ?></td>
                    <td class="text-right"><a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
  var url = 'index.php?route=extension/openbay/fba/orderlist&token=<?php echo $token; ?>';

  var filter_start = $('input[name=\'filter_start\']').val();

  if (filter_start) {
    url += '&filter_start=' + encodeURIComponent(filter_start);
  }

  var filter_end = $('input[name=\'filter_end\']').val();

  if (filter_end) {
    url += '&filter_end=' + encodeURIComponent(filter_end);
  }

  var filter_status = $('select[name=\'filter_status\']').val();

  if (filter_status) {
    url += '&filter_status=' + encodeURIComponent(filter_status);
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