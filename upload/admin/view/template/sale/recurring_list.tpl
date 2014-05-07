<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
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
      <h1 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <div class="well">
        <div class="row">
          <div class="col-sm-4">
            <div class="form-group">
              <label class="control-label" for="input-order-id"><?php echo $entry_order_id; ?></label>
              <input type="text" name="filter_order_recurring_id" value="<?php echo $filter_order_recurring_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
            </div>
            <div class="form-group">
              <label class="control-label" for="input-order-id"><?php echo $entry_payment_reference; ?></label>
              <input type="text" name="filter_payment_reference" value="<?php echo $filter_payment_reference; ?>" placeholder="<?php echo $entry_payment_reference; ?>" id="input-payment-reference" class="form-control" />
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="control-label" for="input-filter-status"><?php echo $entry_status; ?></label>
              <select name="filter_status" id="input-filter-status" class="form-control">
                <?php foreach($statuses as $status => $text) { ?>
                <?php if ($filter_status == $status) { ?>
                <option value="<?php echo $status ?>" selected="selected"><?php echo $text ?></option>
                <?php } else { ?>
                <option value="<?php echo $status ?>"><?php echo $text ?></option>
                <?php } ?>
                <?php } ?>
              </select>
            </div>
            <div class="form-group">
              <label class="control-label" for="input-customer"><?php echo $entry_customer; ?></label>
              <input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" placeholder="<?php echo $entry_customer; ?>" id="input-customer" class="form-control" />
            </div>
          </div>
          <div class="col-sm-4">
            <div class="form-group">
              <label class="control-label" for="input-date-created"><?php echo $entry_date_created; ?></label>
              <div class="input-group date"><input type="text" name="filter_created" value="<?php echo $filter_created; ?>" placeholder="<?php echo $entry_date_created; ?>" data-format="YYYY-MM-DD" id="input-date-created" class="form-control" /><span class="input-group-btn">
                <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-sm-12">
            <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $text_filter ?></button>
          </div>
        </div>
      </div>
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td width="10" class="text-left"><?php if ($sort == 'or.order_recurring_id') { ?>
                  <a href="<?php echo $sort_order_recurring; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_order_recurring; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_order_recurring; ?>"><?php echo $entry_order_recurring; ?></a>
                  <?php }  ?></td>
                <td width="80" class="text-center"><?php if ($sort == 'or.order_id') { ?>
                  <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_order_id; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_order; ?>"><?php echo $entry_order_id; ?></a>
                  <?php }  ?></td>
                <td class="text-left"><?php if ($sort == 'or.profile_reference') { ?>
                  <a href="<?php echo $sort_payment_reference; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_payment_reference; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_payment_reference; ?>"><?php echo $entry_payment_reference; ?></a>
                  <?php }  ?></td>
                <td class="text-left"><?php if ($sort == 'customer') { ?>
                  <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_customer ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_customer; ?>"><?php echo $entry_customer ?></a>
                  <?php }  ?>
                <td class="text-left"><?php if ($sort == 'or.created') { ?>
                  <a href="<?php echo $sort_created; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_date_created ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_created; ?>"><?php echo $entry_date_created ?></a>
                  <?php }  ?></td>
                <td class="text-left"><?php if ($sort == 'or.status') { ?>
                  <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_status; ?></a>
                  <?php } else { ?>
                  <a href="<?php echo $sort_status; ?>"><?php echo $entry_status; ?></a>
                  <?php }  ?></td>
                <td class="text-right"><?php echo $entry_action; ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($profiles) { ?>
              <?php foreach ($profiles as $profile) { ?>
              <tr>
                <td class="text-left"><?php echo $profile['order_recurring_id'] ?></td>
                <td class="text-center"><a href="<?php echo $profile['order_link']; ?>" data-toggle="tooltip" title="<?php echo $text_view; ?> <?php echo $profile['order_id'] ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
                <td class="text-left"><?php echo $profile['profile_reference'] ?></td>
                <td class="text-left"><?php echo $profile['customer'] ?></td>
                <td class="text-left"><?php echo $profile['date_created'] ?></td>
                <td class="text-left"><?php echo $profile['status'] ?></td>
                <td class="text-right"><a href="<?php echo $profile['view']; ?>" data-toggle="tooltip" title="<?php echo $text_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
              </tr>
              <?php } ?>
              <?php } else { ?>
              <tr>
                <td class="text-center" colspan="8"><?php echo $text_no_results; ?></td>
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
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
  url = 'index.php?route=sale/recurring&token=<?php echo $token; ?>';

  var filter_order_recurring_id = $('input[name=\'filter_order_recurring_id\']').val();

  if (filter_order_recurring_id) {
    url += '&filter_order_recurring_id=' + encodeURIComponent(filter_order_recurring_id);
  }

  var filter_order_id = $('input[name=\'filter_order_id\']').val();

  if (filter_order_id) {
    url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
  }

  var filter_payment_reference = $('input[name=\'filter_payment_reference\']').val();

  if (filter_payment_reference) {
    url += '&filter_payment_reference=' + encodeURIComponent(filter_payment_reference);
  }

  var filter_customer = $('input[name=\'filter_customer\']').val();

  if (filter_customer) {
    url += '&filter_customer=' + encodeURIComponent(filter_customer);
  }

  var filter_created = $('input[name=\'filter_created\']').val();

  if (filter_created != '') {
    url += '&filter_created=' + encodeURIComponent(filter_created);
  }

  var filter_status = $('select[name=\'filter_status\']').val();

  if (filter_status != 0) {
    url += '&filter_status=' + encodeURIComponent(filter_status);
  }

  location = url;
});

$('#form input').keydown(function(e) {
  if (e.keyCode == 13) {
    filter();
  }
});

$(document).ready(function() {
  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
});

//--></script> 
<?php echo $footer; ?>