<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a id="button-edit" data-toggle="tooltip" style="display:none;" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a> <a id="button-search" data-toggle="tooltip" title="<?php echo $button_search; ?>" class="btn btn-info"><i class="fa fa-search"></i></a></div>
      <h1><i class="fa fa-search"></i> <?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="panel-body">
        <div id="search-input">
          <form id="form" class="form-horizontal">
            <h3><?php echo $text_date_search; ?></h3>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-date-start"><?php echo $entry_date_start; ?></label>
              <div class="col-sm-10">
                <div class="input-group date">
                  <input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="<?php echo $text_format; ?>: yy-mm-dd" data-date-format="YYYY-MM-DD" id="input-date-start" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-date-end"><?php echo $entry_date_end; ?></label>
              <div class="col-sm-10">
                <div class="input-group date">
                  <input type="text" name="date_end" value="<?php echo $date_end; ?>" placeholder="<?php echo $text_format; ?>: yy-mm-dd" data-date-format="YYYY-MM-DD" class="form-control" />
                  <span class="input-group-btn">
                  <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
                  </span></div>
              </div>
            </div>
            <h3><?php echo $entry_transaction; ?></h3>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_transaction_type; ?></label>
              <div class="col-sm-10">
                <select name="transaction_class" class="form-control">
                  <option value="All"><?php echo $entry_trans_all;?></option>
                  <option value="Sent"><?php echo $entry_trans_sent;?></option>
                  <option value="Received"><?php echo $entry_trans_received;?></option>
                  <option value="MassPay"><?php echo $entry_trans_masspay;?></option>
                  <option value="MoneyRequest"><?php echo $entry_trans_money_req;?></option>
                  <option value="FundsAdded"><?php echo $entry_trans_funds_add;?></option>
                  <option value="FundsWithdrawn"><?php echo $entry_trans_funds_with;?></option>
                  <option value="Referral"><?php echo $entry_trans_referral;?></option>
                  <option value="Fee"><?php echo $entry_trans_fee;?></option>
                  <option value="Subscription"><?php echo $entry_trans_subscription;?></option>
                  <option value="Dividend"><?php echo $entry_trans_dividend;?></option>
                  <option value="Billpay"><?php echo $entry_trans_billpay;?></option>
                  <option value="Refund"><?php echo $entry_trans_refund;?></option>
                  <option value="CurrencyConversions"><?php echo $entry_trans_conv;?></option>
                  <option value="BalanceTransfer"><?php echo $entry_trans_bal_trans;?></option>
                  <option value="Reversal"><?php echo $entry_trans_reversal;?></option>
                  <option value="Shipping"><?php echo $entry_trans_shipping;?></option>
                  <option value="BalanceAffecting"><?php echo $entry_trans_bal_affect;?></option>
                  <option value="ECheck"><?php echo $entry_trans_echeque;?></option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_email; ?> (<?php echo $entry_email_buyer; ?>)</label>
              <div class="col-sm-10">
                <input type="text" name="buyer_email" value="" placeholder="<?php echo $entry_email_buyer; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_email; ?> (<?php echo $entry_email_merchant; ?>)</label>
              <div class="col-sm-10">
                <input type="text" name="merchant_email" value="" placeholder="<?php echo $entry_email_merchant; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_receipt; ?></label>
              <div class="col-sm-10">
                <input type="text" name="receipt_id" value="" placeholder="<?php echo $entry_receipt; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_transaction_id; ?></label>
              <div class="col-sm-10">
                <input type="text" name="transaction_id" value="" placeholder="<?php echo $entry_transaction_id; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_invoice_no; ?></label>
              <div class="col-sm-10">
                <input type="text" name="invoice_number" value="" placeholder="<?php echo $entry_invoice_no; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_auction; ?></label>
              <div class="col-sm-10">
                <input type="text" name="auction_item_number" value="" placeholder="<?php echo $entry_auction; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_amount; ?></label>
              <div class="col-sm-10">
                <input type="text" name="amount" value="" placeholder="<?php echo $entry_amount; ?>" class="form-control" />
                <br />
                <select name="currency_code" class="form-control">
                  <?php foreach($currency_codes as $code) { ?>
                  <option <?php if ($code == $default_currency) { echo 'selected'; } ?>><?php echo $code; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_recurring_id; ?></label>
              <div class="col-sm-10">
                <input type="text" name="recurring_id" value="" placeholder="<?php echo $entry_recurring_id; ?>" class="form-control" />
              </div>
            </div>
            <h3><?php echo $text_buyer_info; ?></h3>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_salutation; ?></label>
              <div class="col-sm-10">
                <input type="text" name="name_salutation" value="" placeholder="<?php echo $entry_salutation; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_firstname; ?></label>
              <div class="col-sm-10">
                <input type="text" name="name_first" value="" placeholder="<?php echo $entry_firstname; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_middlename; ?></label>
              <div class="col-sm-10">
                <input type="text" name="name_middle" value="" placeholder="<?php echo $entry_middlename; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_lastname; ?></label>
              <div class="col-sm-10">
                <input type="text" name="name_last" value="" placeholder="<?php echo $entry_lastname; ?>" class="form-control" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_suffix; ?></label>
              <div class="col-sm-10">
                <input type="text" name="name_suffix" value="" placeholder="<?php echo $entry_suffix; ?>" class="form-control" />
              </div>
            </div>
          </form>
        </div>
        <div id="search-box" style="display:none;">
          <div id="searching"><i class="fa fa-circle-o-notch fa-spin fa-lg"></i> <?php echo $text_searching; ?></div>
          <div style="display:none;" id="error" class="alert alert-danger"></div>
          <table id="search_results" style="display:none;" class="table table-striped table-bordered" >
          </table>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-search').on('click', function() {
  var html = '';

	$.ajax({
		url: 'index.php?route=extension/payment/pp_express/doSearch&token=<?php echo $token; ?>',
		type: 'POST',
		dataType: 'json',
		data: $('#form').serialize(),
		beforeSend: function () {
			$('#search-input').hide();
			$('#search-box').show();
			$('#button-search').hide();
			$('#button-edit').show();
		},
		success: function (data) {
			if (data.error == true) {
				$('#searching').hide();
				$('#error').html('<i class="fa fa-exclamation-circle"></i> ' + data.error_msg).fadeIn();
			} else {
				if (data.result != '') {
					html += '<thead><tr>';
					html += '<td class="left"><?php echo $column_date; ?></td>';
					html += '<td class="left"><?php echo $column_type; ?></td>';
					html += '<td class="left"><?php echo $column_email; ?></td>';
					html += '<td class="left"><?php echo $column_name; ?></td>';
					html += '<td class="left"><?php echo $column_transid; ?></td>';
					html += '<td class="left"><?php echo $column_status; ?></td>';
					html += '<td class="left"><?php echo $column_currency; ?></td>';
					html += '<td class="right"><?php echo $column_amount; ?></td>';
					html += '<td class="right"><?php echo $column_fee; ?></td>';
					html += '<td class="right"><?php echo $column_netamt; ?></td>';
					html += '<td class="center"><?php echo $column_action; ?></td>';
					html += '</tr></thead>';
				
          $.each(data.result, function (k, v) {
            if ("L_LONGMESSAGE" in v) {
              $('#error').text(v.L_LONGMESSAGE).fadeIn();
            } else {
              if (!("L_EMAIL" in v)) {
                v.L_EMAIL = '';
              }

              html += '<tr>';
              html += '<td class="left">' + v.L_TIMESTAMP + '</td>';
              html += '<td class="left">' + v.L_TYPE + '</td>';
              html += '<td class="left">' + v.L_EMAIL + '</td>';
              html += '<td class="left">' + v.L_NAME + '</td>';
              html += '<td class="left">' + v.L_TRANSACTIONID + '</td>';
              html += '<td class="left">' + v.L_STATUS + '</td>';
              html += '<td class="left">' + v.L_CURRENCYCODE + '</td>';
              html += '<td class="right">' + v.L_AMT + '</td>';
              html += '<td class="right">' + v.L_FEEAMT + '</td>';
              html += '<td class="right">' + v.L_NETAMT + '</td>';
              html += '<td class="center">';
              html += '<a href="<?php echo $view_link; ?>&transaction_id=' + v.L_TRANSACTIONID + '"><?php echo $text_view; ?></a>';
              html += '</td>';
              html += '</tr>';
            }
          });
	
          $('#searching').hide();
          $('#search_results').append(html).fadeIn();
	      } else {
	        $('#searching').hide();
	        $('#error').html('<i class="fa fa-exclamation-circle"></i> <?php echo $text_no_results; ?>').fadeIn();
	      }
	    }
	  }
	});
});

$('#button-edit').on('click', function() {
  $('#search-box').hide();
  $('#search-input').show();
  $('#button-edit').hide();
  $('#button-search').show();
  $('#searching').show();
  $('#search_results').empty().hide();
  $('#error').empty().hide();
});

$('.date').datetimepicker({
	pickTime: false
});

$('.time').datetimepicker({
	pickDate: false
});

$('.datetime').datetimepicker({
	pickDate: true,
	pickTime: true
});
//--></script></div>
<?php echo $footer; ?>