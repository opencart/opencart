<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right"> <a onclick="editSearch();" id="btn_edit" class="btn btn-primary" style="display:none;"><?php echo $btn_edit_search; ?></a> <a onclick="doSearch();" id="btn_search" class="btn btn-primary"><?php echo $btn_search; ?></a> </div>
      <h1 class="panel-title"><i class="fa fa-search fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <div id="search_input">
        <form id="form" class="form-horizontal">
          <h3><?php echo $text_date_search; ?></h3>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_date_start; ?></label>
            <div class="col-sm-10">
              <input type="text" name="date_start" value="<?php echo $date_start; ?>" placeholder="<?php echo $text_format; ?>: yy-mm-dd" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_date_end; ?></label>
            <div class="col-sm-10">
              <input type="text" name="date_end" value="<?php echo $date_end; ?>" placeholder="<?php echo $text_format; ?>: yy-mm-dd" class="form-control" />
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
                <?php foreach($currency_codes as $code){ ?>
                <option <?php if($code == $default_currency){ echo 'selected'; } ?>><?php echo $code; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_profile_id; ?></label>
            <div class="col-sm-10">
              <input type="text" name="profile_id" value="" placeholder="<?php echo $entry_profile_id; ?>" class="form-control" />
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
      <div id="search_box" style="display:none;">
        <div id="searching"><i class="fa fa-cog fa-spin fa-lg"></i> <?php echo $text_searching; ?></div>
        <div style="display:none;" id="error" class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $attention; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
        <table id="search_results" style="display:none;" class="table table-striped table-bordered" >
        </table>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function doSearch() {
  var html;

  $.ajax({
    type: 'POST',
    dataType: 'json',
    data: $('#form').serialize(),
    url: 'index.php?route=payment/pp_express/doSearch&token=<?php echo $token; ?>',
    beforeSend: function () {
      $('#search_input').hide();
      $('#search_box').show();
      $('#btn_search').hide();
      $('#btn_edit').show();
    },
    success: function (data) {
      if (data.error == true) {
        $('#searching').hide();
        $('#error').text(data.error_msg).fadeIn();
      } else {
        if (data.result != '') {
          html += '<thead><tr>';
          html += '<td class="left"><?php echo $tbl_column_date; ?></td>';
          html += '<td class="left"><?php echo $tbl_column_type; ?></td>';
          html += '<td class="left"><?php echo $tbl_column_email; ?></td>';
          html += '<td class="left"><?php echo $tbl_column_name; ?></td>';
          html += '<td class="left"><?php echo $tbl_column_transid; ?></td>';
          html += '<td class="left"><?php echo $tbl_column_status; ?></td>';
          html += '<td class="left"><?php echo $tbl_column_currency; ?></td>';
          html += '<td class="right"><?php echo $tbl_column_amount; ?></td>';
          html += '<td class="right"><?php echo $tbl_column_fee; ?></td>';
          html += '<td class="right"><?php echo $tbl_column_netamt; ?></td>';
          html += '<td class="center"><?php echo $tbl_column_action; ?></td>';
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
          $('#error').text('<?php echo $text_no_results; ?>').fadeIn();
        }
      }
    }
  });
}

function editSearch() {
  $('#search_box').hide();
  $('#search_input').show();
  $('#btn_edit').hide();
  $('#btn_search').show();
  $('#searching').show();
  $('#search_results').empty().hide();
  $('#error').empty().hide();
}
//--></script> 
<?php echo $footer; ?>