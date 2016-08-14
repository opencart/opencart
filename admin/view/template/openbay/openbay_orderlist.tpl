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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <div class="well">
          <div class="row">
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
                <input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
                <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-order-status"><?php echo $entry_order_status; ?></label>
                <select name="filter_order_status_id" id="input-order-status" class="form-control">
                  <option value="*"></option>
                  <?php if ($filter_order_status_id == '0') { ?>
                  <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_missing; ?></option>
                  <?php } ?>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-channel"><?php echo $entry_order_status; ?></label>
                <select name="filter_channel" id="input-channel" class="form-control">
                  <option value=""></option>
                  <?php foreach ($channels as $channel) { ?>
                  <?php if ($channel['module'] == $filter_channel) { ?>
                  <option value="<?php echo $channel['module'] ?>" selected="selected"><?php echo $channel['title'] ?></option>
                  <?php } else {  ?>
                  <option value="<?php echo $channel['module'] ?>"><?php echo $channel['title'] ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-sm-4">
              <div class="form-group">
                <label class="control-label" for="input-date-added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-date-format="YYYY-MM-DD" id="input-date-added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="col-sm-4">
              <button onclick="filter();" type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter; ?></button>
            </div>
          </div>
        </div>
        <form action="<?php echo $link_update; ?>" method="post" id="order-update-form">
        <div class="well">
          <div class="row">
            <div class="col-sm-12">
              <div class="form-group">
                <div class="input-group">
                  <select name="change_order_status_id" class="form-control">
                    <?php foreach ($order_statuses as $order_status) { ?>
                      <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                    <?php } ?>
                  </select>
                  <a data-toggle="tooltip" title="<?php echo $button_filter; ?>" class="btn btn-primary input-group-addon" onclick="$('#order-update-form').submit();"><i class="fa fa-plus-circle"></i> <?php echo $button_status; ?></a>
                </div>
              </div>
            </div>
          </div>
        </div>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
            <thead>
              <tr>
                <th width="1"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                <th class="text-left"><?php if ($sort == 'o.order_id') { ?>
                  <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                  <?php } ?></th>
                <th class="text-left"><?php if ($sort == 'customer') { ?>
                  <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                  <?php } ?></th>
                <th class="text-left"><?php if ($sort == 'status') { ?>
                  <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                  <?php } ?></th>
                <th class="text-left"><?php if ($sort == 'channel') { ?>
                  <a href="<?php echo $sort_channel; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_channel; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_channel; ?>"><?php echo $column_channel; ?></a>
                  <?php } ?></th>
                <th class="text-left"><?php if ($sort == 'o.date_added') { ?>
                  <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                  <?php } ?></th>
                <th class="text-right"><?php echo $column_action; ?></th>
              </tr>
            </thead>
            <tbody>
              <?php if ($orders) { ?>
                <?php foreach ($orders as $order) { ?>
                <tr>
                  <td style="text-align: center;">
                    <?php if ($order['selected']) { ?>
                      <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                    <?php } else { ?>
                      <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                    <?php } ?>
                  </td>
                  <td class="text-left"><?php echo $order['order_id']; ?></td>
                  <td class="text-left"><?php echo $order['customer']; ?></td>
                  <td class="text-left"><?php echo $order['status']; ?></td>
                  <td class="text-left"><?php echo $order['channel']; ?></td>
                  <td class="text-left"><?php echo $order['date_added']; ?></td>
                  <td class="text-right"><a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
                </tr>
                <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
  function filter() {
    url = 'index.php?route=extension/openbay/orderList&token=<?php echo $token; ?>';
    var filter_order_id = $('input[name=\'filter_order_id\']').val();
    if (filter_order_id) {
      url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
    }

    var filter_customer = $('input[name=\'filter_customer\']').val();
    if (filter_customer) {
      url += '&filter_customer=' + encodeURIComponent(filter_customer);
    }

    var filter_order_status_id = $('select[name=\'filter_order_status_id\']').find(":selected").val();
    if (filter_order_status_id != '*') {
      url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
    }

    var filter_channel = $('select[name=\'filter_channel\']').find(":selected").val();

    if (filter_channel != '') {
      url += '&filter_channel=' + encodeURIComponent(filter_channel);
    }

    var filter_date_added = $('input[name=\'filter_date_added\']').val();
    if (filter_date_added) {
      url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
    }

    location = url;
  }
//--></script>
<script type="text/javascript"><!--
  $(document).ready(function() {
	  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  });
//--></script>
<script type="text/javascript"><!--
  $('#form input').keydown(function(e) {
    if (e.keyCode == 13) {
      filter();
    }
  });
//--></script>
<script type="text/javascript"><!--
    $.widget('custom.catcomplete', $.ui.autocomplete, {
        _renderMenu: function(ul, items) {
            var self = this, currentCategory = '';

            $.each(items, function(index, item) {
                if (item.category != currentCategory) {
                    ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');

                    currentCategory = item.category;
                }

                self._renderItem(ul, item);
            });
        }
    });

    $('input[name=\'filter_customer\']').catcomplete({
        delay: 0,
        source: function(request, response) {
            $.ajax({
                url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
                dataType: 'json',
                success: function(json) {
                    response($.map(json, function(item) {
                        return {
                            category: item.customer_group,
                            label: item.name,
                            value: item.customer_id
                        }
                    }));
                }
            });
        },
        select: function(event, ui) {
            $('input[name=\'filter_customer\']').val(ui.item.label);

            return false;
        }
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