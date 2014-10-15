<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
                <input type="text" name="filter_order_recurring_id" value="<?php echo $filter_order_recurring_id; ?>" placeholder="<?php echo $entry_order_id; ?>" id="input-order-id" class="form-control" />
              </div>
              <div class="form-group">
                <label class="control-label" for="input-reference"><?php echo $entry_reference; ?></label>
                <input type="text" name="filter_reference" value="<?php echo $filter_reference; ?>" placeholder="<?php echo $entry_reference; ?>" id="input-reference" class="form-control" />
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
                <label class="control-label" for="input-date-date_added"><?php echo $entry_date_added; ?></label>
                <div class="input-group date">
                  <input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" placeholder="<?php echo $entry_date_added; ?>" data-format="YYYY-MM-DD" id="input-date-date_added" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-12">
              <button type="button" id="button-filter" class="btn btn-primary pull-right"><i class="fa fa-search"></i> <?php echo $button_filter ?></button>
            </div>
          </div>
        </div>
        <form action="" method="post" enctype="multipart/form-data" id="form">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
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
                  <td class="text-left"><?php if ($sort == 'or.reference') { ?>
                    <a href="<?php echo $sort_reference; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_reference; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_reference; ?>"><?php echo $entry_reference; ?></a>
                    <?php }  ?></td>
                  <td class="text-left"><?php if ($sort == 'customer') { ?>
                    <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_customer ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_customer; ?>"><?php echo $entry_customer ?></a>
                    <?php }  ?></td>
                  <td class="text-left"><?php if ($sort == 'or.status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $entry_status; ?></a>
                    <?php }  ?></td>
                  <td class="text-left"><?php if ($sort == 'or.date_added') { ?>
                    <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $entry_date_added ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_date_added; ?>"><?php echo $entry_date_added ?></a>
                    <?php }  ?></td>
                  <td class="text-right"><?php echo $entry_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($recurrings) { ?>
                <?php foreach ($recurrings as $recurring) { ?>
                <tr>
                  <td class="text-left"><?php echo $recurring['order_recurring_id'] ?></td>
                  <td class="text-left"><a href="<?php echo $recurring['order_link']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?> <?php echo $recurring['order_id'] ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
                  <td class="text-left"><?php echo $recurring['reference'] ?></td>
                  <td class="text-left"><?php echo $recurring['customer'] ?></td>
                  <td class="text-left"><?php echo $recurring['status'] ?></td>
                  <td class="text-left"><?php echo $recurring['date_added'] ?></td>
                  <td class="text-right"><a href="<?php echo $recurring['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
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

	var filter_reference = $('input[name=\'filter_reference\']').val();
	
	if (filter_reference) {
		url += '&filter_reference=' + encodeURIComponent(filter_reference);
	}

	var filter_customer = $('input[name=\'filter_customer\']').val();
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}

	var filter_status = $('select[name=\'filter_status\']').val();
	
	if (filter_status != 0) {
		url += '&filter_status=' + encodeURIComponent(filter_status);
	}
	
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added != '') {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}
		
	location = url;
});
	
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});

$('.date').datetimepicker({ 
	pickTime: false 
});
//--></script> </div>
<?php echo $footer; ?>