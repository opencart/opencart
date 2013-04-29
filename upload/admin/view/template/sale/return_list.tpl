<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="icon-ok-sign"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="box">
    <div class="box-heading">
      <h1><i class="icon-list"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="box-content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <div class="buttons"><a href="<?php echo $insert; ?>" class="btn"><i class="icon-plus"></i> <?php echo $button_insert; ?></a> <a onclick="$('#form').submit();" class="btn"><i class="icon-trash"></i> <?php echo $button_delete; ?></a></div>
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
              <td class="right"><?php if ($sort == 'r.return_id') { ?>
                <a href="<?php echo $sort_return_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_return_id; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_return_id; ?>"><?php echo $column_return_id; ?></a>
                <?php } ?></td>
              <td class="right"><?php if ($sort == 'r.order_id') { ?>
                <a href="<?php echo $sort_order_id; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_order_id; ?>"><?php echo $column_order_id; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'customer') { ?>
                <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'r.product') { ?>
                <a href="<?php echo $sort_product; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_product; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_product; ?>"><?php echo $column_product; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'r.model') { ?>
                <a href="<?php echo $sort_model; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_model; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_model; ?>"><?php echo $column_model; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'r.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'r.date_modified') { ?>
                <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td align="right"><input type="text" name="filter_return_id" value="<?php echo $filter_return_id; ?>" class="input-mini" style="text-align: right;" /></td>
              <td align="right"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" class="input-mini" style="text-align: right;" /></td>
              <td><input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" data-toggle="dropdown" data-target="#autocomplete-customer" autocomplete="off" class="input-medium" />
                <div id="autocomplete-customer" class="dropdown">
                  <ul class="dropdown-menu">
                    <li class="disabled"><a href="#"><i class="icon-spinner icon-spin"></i> <?php echo $text_loading; ?></a></li>
                  </ul>
                </div></td>
              <td><input type="text" name="filter_product" value="<?php echo $filter_product; ?>" data-toggle="dropdown" data-target="#autocomplete-product" autocomplete="off" class="input-medium" />
                <div id="autocomplete-product" class="dropdown">
                  <ul class="dropdown-menu">
                    <li class="disabled"><a href="#"><i class="icon-spinner icon-spin"></i> <?php echo $text_loading; ?></a></li>
                  </ul>
                </div></td>
              <td><input type="text" name="filter_model" value="<?php echo $filter_model; ?>" data-toggle="dropdown" data-target="#autocomplete-model" autocomplete="off" class="input-medium" />
                <div id="autocomplete-model" class="dropdown">
                  <ul class="dropdown-menu">
                    <li class="disabled"><a href="#"><i class="icon-spinner icon-spin"></i> <?php echo $text_loading; ?></a></li>
                  </ul>
                </div></td>
              <td><select name="filter_return_status_id" class="input-medium">
                  <option value="*"></option>
                  <?php foreach ($return_statuses as $return_status) { ?>
                  <?php if ($return_status['return_status_id'] == $filter_return_status_id) { ?>
                  <option value="<?php echo $return_status['return_status_id']; ?>" selected="selected"><?php echo $return_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $return_status['return_status_id']; ?>"><?php echo $return_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td><input type="date" name="filter_date_added" value="<?php echo $filter_date_added; ?>" class="input-medium" /></td>
              <td><input type="date" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" class="input-medium" /></td>
              <td align="right"><button type="button" id="button-filter" class="btn"><i class="icon-search"></i> <?php echo $button_filter; ?></button></td>
            </tr>
            <?php if ($returns) { ?>
            <?php foreach ($returns as $return) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($return['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $return['return_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $return['return_id']; ?>" />
                <?php } ?></td>
              <td class="right"><?php echo $return['return_id']; ?></td>
              <td class="right"><?php echo $return['order_id']; ?></td>
              <td class="left"><?php echo $return['customer']; ?></td>
              <td class="left"><?php echo $return['product']; ?></td>
              <td class="left"><?php echo $return['model']; ?></td>
              <td class="left"><?php echo $return['status']; ?></td>
              <td class="left"><?php echo $return['date_added']; ?></td>
              <td class="left"><?php echo $return['date_modified']; ?></td>
              <td class="right"><?php foreach ($return['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="10"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
      <div class="results"><?php echo $results; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-filter').on('click', function() {
	url = 'index.php?route=sale/return&token=<?php echo $token; ?>';
	
	var filter_return_id = $('input[name=\'filter_return_id\']').val();
	
	if (filter_return_id) {
		url += '&filter_return_id=' + encodeURIComponent(filter_return_id);
	}
	
	var filter_order_id = $('input[name=\'filter_order_id\']').val();
	
	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}	
		
	var filter_customer = $('input[name=\'filter_customer\']').val();
	
	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}
	
	var filter_product = $('input[name=\'filter_product\']').val();
	
	if (filter_product) {
		url += '&filter_product=' + encodeURIComponent(filter_product);
	}

	var filter_model = $('input[name=\'filter_model\']').val();
	
	if (filter_model) {
		url += '&filter_model=' + encodeURIComponent(filter_model);
	}
		
	var filter_return_status_id = $('select[name=\'filter_return_status_id\']').val();
	
	if (filter_return_status_id != '*') {
		url += '&filter_return_status_id=' + encodeURIComponent(filter_return_status_id);
	}	
	
	var filter_date_added = $('input[name=\'filter_date_added\']').val();
	
	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	var filter_date_modified = $('input[name=\'filter_date_modified\']').val();
	
	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}
			
	location = url;
});
//--></script> 
<script type="text/javascript"><!--
var timer = null;

$('input[name=\'filter_customer\']').on('click keyup', function() {
	var input = this;
	
	if (timer != null) {
		clearTimeout(timer);
	}

	timer = setTimeout(function() {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent($(input).val()),
			dataType: 'json',			
			success: function(json) {
				if (json.length) {
					html = '';
					
					for (i in json) {
						html += '<li class="disabled"><a href="#"><b>' + json[i]['name'] + '</b></a></li>';
						
						for (j = 0; j < json[i]['customer'].length; j++) {
							customer = json[i]['customer'][j];
							
							html += '<li data-value="' + customer['customer_id'] + '"><a href="#">' + customer['name'] + '</a></li>';						
						}
					}
				} else {
					html = '<li class="disabled"><a href="#"><?php echo $text_none; ?></a></li>';
				}
				
				$($(input).attr('data-target')).find('ul').html(html);
			}
		});
	}, 250);
});

$('#autocomplete-customer').delegate('a', 'click', function(e) {
	e.preventDefault();
	
	var value = $(this).parent().attr('data-value');
	
	if (typeof value !== 'undefined') {
		$('input[name=\'filter_customer\']').val($(this).text());
	}
});

var timer = null;

$('input[name=\'filter_product\']').on('click keyup', function() {
	var input = this;
	
	if (timer != null) {
		clearTimeout(timer);
	}

	timer = setTimeout(function() {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent($(input).val()),
			dataType: 'json',			
			success: function(json) {
				if (json.length) {
					html = '';
					
					for (i = 0; i < json.length; i++) {
						html += '<li data-value="' + json[i]['product_id'] + '"><a href="#">' + json[i]['name'] + '</a></li>';
					}
				} else {
					html = '<li class="disabled"><a href="#"><?php echo $text_none; ?></a></li>';
				}
				
				$($(input).attr('data-target')).find('ul').html(html);
			}
		});
	}, 250);
});

$('#autocomplete-product').delegate('a', 'click', function(e) {
	e.preventDefault();
	
	var value = $(this).parent().attr('data-value');
	
	if (typeof value !== 'undefined') {
		$('input[name=\'filter_product\']').val($(this).text());
	}
});

var timer = null;

$('input[name=\'filter_model\']').on('click keyup', function() {
	var input = this;
	
	if (timer != null) {
		clearTimeout(timer);
	}

	timer = setTimeout(function() {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent($(input).val()),
			dataType: 'json',			
			success: function(json) {
				if (json.length) {
					html = '';
					
					for (i = 0; i < json.length; i++) {
						html += '<li data-value="' + json[i]['product_id'] + '"><a href="#">' + json[i]['model'] + '</a></li>';
					}
				} else {
					html = '<li class="disabled"><a href="#"><?php echo $text_none; ?></a></li>';
				}
				
				$($(input).attr('data-target')).find('ul').html(html);
			}
		});
	}, 250);
});

$('#autocomplete-model').delegate('a', 'click', function(e) {
	e.preventDefault();
	
	var value = $(this).parent().attr('data-value');
	
	if (typeof value !== 'undefined') {
		$('input[name=\'filter_model\']').val($(this).text());
	}
});
//--></script> 
<?php echo $footer; ?> 