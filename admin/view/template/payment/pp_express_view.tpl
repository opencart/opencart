<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $cancel; ?>" class="btn btn-primary"><i class="fa fa-reply"></i> <?php echo $button_cancel; ?></a></div>
      <h1><?php echo $heading_title; ?></h1>
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
        <table class="table table-striped table-bordered">
          <?php if (!empty($lines)) { ?>
          <tr>
            <td><?php echo $text_product_lines; ?></td>
            <td><table class="table table-striped table-bordered">
                <?php $i = 0;  foreach($lines as  $k => $v) { ?>
                <?php if ($i == 0) { ?>
                <thead>
                  <tr>
                    <?php
                    foreach($v as $k => $row) {
                      if ($k == 'L_EBAYITEMTXNID') { echo '<td class="left">'.$text_ebay_txn_id.'</td>'; }
                      if ($k == 'L_NAME') { echo '<td class="left">'.$text_name.'</td>'; }
                      if ($k == 'L_NUMBER') { echo '<td class="left">'.$text_number.'</td>'; }
                      if ($k == 'L_QTY') { echo '<td class="left">'.$text_qty.'</td>'; }
                      if ($k == 'L_COUPONID') { echo '<td class="left">'.$text_coupon_id.'</td>'; }
                      if ($k == 'L_COUPONAMOUNT') { echo '<td class="left">'.$text_coupon_amount.'</td>'; }
                      if ($k == 'L_COUPONAMOUNTCURRENCY') { echo '<td class="left">'.$text_coupon_currency.'</td>'; }
                      if ($k == 'L_LOYALTYCARDDISCOUNTAMOUNT') { echo '<td class="left">'.$text_loyalty_disc_amt.'</td>'; }
                      if ($k == 'L_LOYALTYCARDISCOUNTCURRENCY') { echo '<td class="left">'.$text_loyalty_currency.'</td>'; }
                      if ($k == 'L_AMT') { echo '<td class="left">'.$text_amount.'</td>'; }
                      if ($k == 'L_OPTIONSNAME') { echo '<td class="left">'.$text_options_name.'</td>'; }
                      if ($k == 'L_TAXAMT') { echo '<td class="left">'.$text_tax_amt.'</td>'; }
                      if ($k == 'L_CURRENCYCODE') { echo '<td class="left">'.$text_currency_code.'</td>'; }
                    } $i++;
                    ?>
                  </tr>
                </thead>
                <?php } ?>
                <tr>
                  <?php
                foreach($v as $k => $row) {
                  if ($k == 'L_EBAYITEMTXNID') { echo '<td class="left">'.$row.'</td>'; }
                  if ($k == 'L_NAME') { echo '<td class="left">'.$row.'</td>'; }
                  if ($k == 'L_NUMBER') { echo '<td class="left">'.$row.'</td>'; }
                  if ($k == 'L_QTY') { echo '<td class="left">'.$row.'</td>'; }
                  if ($k == 'L_COUPONID') { echo '<td class="left">'.$row.'</td>'; }
                  if ($k == 'L_COUPONAMOUNT') { echo '<td class="left">'.$row.'</td>'; }
                  if ($k == 'L_COUPONAMOUNTCURRENCY') { echo '<td class="left">'.$row.'</td>'; }
                  if ($k == 'L_LOYALTYCARDDISCOUNTAMOUNT') { echo '<td class="left">'.$row.'</td>'; }
                  if ($k == 'L_LOYALTYCARDISCOUNTCURRENCY') { echo '<td class="left">'.$row.'</td>'; }
                  if ($k == 'L_AMT') { echo '<td class="left">'.$row.'</td>'; }
                  if ($k == 'L_OPTIONSNAME') { echo '<td class="left">'.$row.'</td>'; }
                  if ($k == 'L_OPTIONSVALUE') { echo '<td class="left">'.$row.'</td>'; }
                  if ($k == 'L_TAXAMT') { echo '<td class="left">'.$row.'</td>'; }
                  if ($k == 'L_CURRENCYCODE') { echo '<td class="left">'.$row.'</td>'; }
                } $i++;
                ?>
                </tr>
                <?php } ?>
              </table></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['GIFTMESSAGE'])) { ?>
          <tr>
            <td><?php echo $text_gift_msg; ?></td>
            <td><?php echo $transaction['GIFTMESSAGE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['GIFTRECEIPTENABLE'])) { ?>
          <tr>
            <td><?php echo $text_gift_receipt; ?></td>
            <td><?php echo $transaction['GIFTRECEIPTENABLE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['GIFTWRAPNAME'])) { ?>
          <tr>
            <td><?php echo $text_gift_wrap_name; ?></td>
            <td><?php echo $transaction['GIFTWRAPNAME']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['GIFTWRAPAMOUNT'])) { ?>
          <tr>
            <td><?php echo $text_gift_wrap_amt; ?></td>
            <td><?php echo $transaction['GIFTWRAPAMOUNT']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['BUYERMARKETINGEMAIL'])) { ?>
          <tr>
            <td><?php echo $text_buyer_email_market; ?></td>
            <td><?php echo $transaction['BUYERMARKETINGEMAIL']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SURVEYQUESTION'])) { ?>
          <tr>
            <td><?php echo $text_survey_question; ?></td>
            <td><?php echo $transaction['SURVEYQUESTION']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SURVEYCHOICESELECTED'])) { ?>
          <tr>
            <td><?php echo $text_survey_chosen; ?></td>
            <td><?php echo $transaction['SURVEYCHOICESELECTED']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['RECEIVERBUSINESS'])) { ?>
          <tr>
            <td><?php echo $text_receiver_business; ?></td>
            <td><?php echo $transaction['RECEIVERBUSINESS']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['RECEIVEREMAIL'])) { ?>
          <tr>
            <td><?php echo $text_receiver_email; ?></td>
            <td><?php echo $transaction['RECEIVEREMAIL']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['RECEIVERID'])) { ?>
          <tr>
            <td><?php echo $text_receiver_id; ?></td>
            <td><?php echo $transaction['RECEIVERID']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['EMAIL'])) { ?>
          <tr>
            <td><?php echo $text_buyer_email; ?></td>
            <td><?php echo $transaction['EMAIL']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['PAYERID'])) { ?>
          <tr>
            <td><?php echo $text_payer_id; ?></td>
            <td><?php echo $transaction['PAYERID']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['PAYERSTATUS'])) { ?>
          <tr>
            <td><?php echo $text_payer_status; ?></td>
            <td><?php echo $transaction['PAYERSTATUS']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['COUNTRYCODE'])) { ?>
          <tr>
            <td><?php echo $text_country_code; ?></td>
            <td><?php echo $transaction['COUNTRYCODE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['PAYERBUSINESS'])) { ?>
          <tr>
            <td><?php echo $text_payer_business; ?></td>
            <td><?php echo $transaction['PAYERBUSINESS']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SALUTATION'])) { ?>
          <tr>
            <td><?php echo $text_payer_salute; ?></td>
            <td><?php echo $transaction['SALUTATION']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['FIRSTNAME'])) { ?>
          <tr>
            <td><?php echo $text_payer_firstname; ?></td>
            <td><?php echo $transaction['FIRSTNAME']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['MIDDLENAME'])) { ?>
          <tr>
            <td><?php echo $text_payer_middlename; ?></td>
            <td><?php echo $transaction['MIDDLENAME']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['LASTNAME'])) { ?>
          <tr>
            <td><?php echo $text_payer_lastname; ?></td>
            <td><?php echo $transaction['LASTNAME']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SUFFIX'])) { ?>
          <tr>
            <td><?php echo $text_payer_suffix; ?></td>
            <td><?php echo $transaction['SUFFIX']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['ADDRESSOWNER'])) { ?>
          <tr>
            <td><?php echo $text_address_owner; ?></td>
            <td><?php echo $transaction['ADDRESSOWNER']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['ADDRESSSTATUS'])) { ?>
          <tr>
            <td><?php echo $text_address_status; ?></td>
            <td><?php echo $transaction['ADDRESSSTATUS']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOSECONDARYNAME'])) { ?>
          <tr>
            <td><?php echo $text_ship_sec_name; ?></td>
            <td><?php echo $transaction['SHIPTOSECONDARYNAME']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTONAME'])) { ?>
          <tr>
            <td><?php echo $text_ship_name; ?></td>
            <td><?php echo $transaction['SHIPTONAME']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOSTREET'])) { ?>
          <tr>
            <td><?php echo $text_ship_street1; ?></td>
            <td><?php echo $transaction['SHIPTOSTREET']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOSECONDARYADDRESSLINE1'])) { ?>
          <tr>
            <td><?php echo $text_ship_sec_add1; ?></td>
            <td><?php echo $transaction['SHIPTOSECONDARYADDRESSLINE1']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOSTREET2'])) { ?>
          <tr>
            <td><?php echo $text_ship_street2; ?></td>
            <td><?php echo $transaction['SHIPTOSTREET2']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOSECONDARYADDRESSLINE2'])) { ?>
          <tr>
            <td><?php echo $text_ship_sec_add2; ?></td>
            <td><?php echo $transaction['SHIPTOSECONDARYADDRESSLINE2']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOCITY'])) { ?>
          <tr>
            <td><?php echo $text_ship_city; ?></td>
            <td><?php echo $transaction['SHIPTOCITY']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOSECONDARYCITY'])) { ?>
          <tr>
            <td><?php echo $text_ship_sec_city; ?></td>
            <td><?php echo $transaction['SHIPTOSECONDARYCITY']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOSTATE'])) { ?>
          <tr>
            <td><?php echo $text_ship_state; ?></td>
            <td><?php echo $transaction['SHIPTOSTATE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOSECONDARYSTATE'])) { ?>
          <tr>
            <td><?php echo $text_ship_sec_state; ?></td>
            <td><?php echo $transaction['SHIPTOSECONDARYSTATE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOZIP'])) { ?>
          <tr>
            <td><?php echo $text_ship_zip; ?></td>
            <td><?php echo $transaction['SHIPTOZIP']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOSECONDARYZIP'])) { ?>
          <tr>
            <td><?php echo $text_ship_sec_zip; ?></td>
            <td><?php echo $transaction['SHIPTOSECONDARYZIP']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOCOUNTRYCODE'])) { ?>
          <tr>
            <td><?php echo $text_ship_country; ?></td>
            <td><?php echo $transaction['SHIPTOCOUNTRYCODE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOSECONDARYCOUNTRYCODE'])) { ?>
          <tr>
            <td><?php echo $text_ship_sec_country; ?></td>
            <td><?php echo $transaction['SHIPTOSECONDARYCOUNTRYCODE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOPHONENUM'])) { ?>
          <tr>
            <td><?php echo $text_ship_phone; ?></td>
            <td><?php echo $transaction['SHIPTOPHONENUM']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SHIPTOSECONDARYPHONENUM'])) { ?>
          <tr>
            <td><?php echo $text_ship_sec_phone; ?></td>
            <td><?php echo $transaction['SHIPTOSECONDARYPHONENUM']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['TRANSACTIONID'])) { ?>
          <tr>
            <td><?php echo $text_trans_id; ?></td>
            <td><?php echo $transaction['TRANSACTIONID']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['PARENTTRANSACTIONID'])) { ?>
          <tr>
            <td><?php echo $text_parent_trans_id; ?></td>
            <td><a href="<?php echo $view_link.'&transaction_id='.$transaction['PARENTTRANSACTIONID']; ?>"><?php echo $transaction['PARENTTRANSACTIONID']; ?></a></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['RECEIPTID'])) { ?>
          <tr>
            <td><?php echo $text_receipt_id; ?></td>
            <td><?php echo $transaction['RECEIPTID']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['TRANSACTIONTYPE'])) { ?>
          <tr>
            <td><?php echo $text_trans_type; ?></td>
            <td><?php echo $transaction['TRANSACTIONTYPE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['PAYMENTTYPE'])) { ?>
          <tr>
            <td><?php echo $text_payment_type; ?></td>
            <td><?php echo $transaction['PAYMENTTYPE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['ORDERTIME'])) { ?>
          <tr>
            <td><?php echo $text_order_time; ?></td>
            <td><?php echo $transaction['ORDERTIME']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['AMT'])) { ?>
          <tr>
            <td><?php echo $text_amount; ?></td>
            <td><?php echo $transaction['AMT']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['CURRENCYCODE'])) { ?>
          <tr>
            <td><?php echo $text_currency_code; ?></td>
            <td><?php echo $transaction['CURRENCYCODE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['FEEAMT'])) { ?>
          <tr>
            <td><?php echo $text_fee_amount; ?></td>
            <td><?php echo $transaction['FEEAMT']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SETTLEAMT'])) { ?>
          <tr>
            <td><?php echo $text_settle_amount; ?></td>
            <td><?php echo $transaction['SETTLEAMT']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['TAXAMT'])) { ?>
          <tr>
            <td><?php echo $text_tax_amount; ?></td>
            <td><?php echo $transaction['TAXAMT']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['EXCHANGERATE'])) { ?>
          <tr>
            <td><?php echo $text_exchange; ?></td>
            <td><?php echo $transaction['EXCHANGERATE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['PAYMENTSTATUS'])) { ?>
          <tr>
            <td><?php echo $text_payment_status; ?></td>
            <td><?php echo $transaction['PAYMENTSTATUS']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['PENDINGREASON'])) { ?>
          <tr>
            <td><?php echo $text_pending_reason; ?></td>
            <td><?php echo $transaction['PENDINGREASON']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['REASONCODE'])) { ?>
          <tr>
            <td><?php echo $text_reason_code; ?></td>
            <td><?php echo $transaction['REASONCODE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['PROTECTIONELIGIBILITY'])) { ?>
          <tr>
            <td><?php echo $text_protect_elig; ?></td>
            <td><?php echo $transaction['PROTECTIONELIGIBILITY']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['PROTECTIONELIGIBILITYTYPE'])) { ?>
          <tr>
            <td><?php echo $text_protect_elig_type; ?></td>
            <td><?php echo $transaction['PROTECTIONELIGIBILITYTYPE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['STOREID'])) { ?>
          <tr>
            <td><?php echo $text_store_id; ?></td>
            <td><?php echo $transaction['STOREID']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['TERMINALID'])) { ?>
          <tr>
            <td><?php echo $text_terminal_id; ?></td>
            <td><?php echo $transaction['TERMINALID']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['INVNUM'])) { ?>
          <tr>
            <td><?php echo $text_invoice_number; ?></td>
            <td><?php echo $transaction['INVNUM']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['CUSTOM'])) { ?>
          <tr>
            <td><?php echo $text_custom; ?></td>
            <td><?php echo $transaction['CUSTOM']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['NOTE'])) { ?>
          <tr>
            <td><?php echo $text_note; ?></td>
            <td><?php echo $transaction['NOTE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['SALESTAX'])) { ?>
          <tr>
            <td><?php echo $text_sales_tax; ?></td>
            <td><?php echo $transaction['SALESTAX']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['BUYERID'])) { ?>
          <tr>
            <td><?php echo $text_buyer_id; ?></td>
            <td><?php echo $transaction['BUYERID']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['CLOSINGDATE'])) { ?>
          <tr>
            <td><?php echo $text_close_date; ?></td>
            <td><?php echo $transaction['CLOSINGDATE']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['MULTIITEM'])) { ?>
          <tr>
            <td><?php echo $text_multi_item; ?></td>
            <td><?php echo $transaction['MULTIITEM']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['AMOUNT'])) { ?>
          <tr>
            <td><?php echo $text_sub_amt; ?></td>
            <td><?php echo $transaction['AMOUNT']; ?></td>
          </tr>
          <?php } ?>
          <?php if (isset($transaction['PERIOD'])) { ?>
          <tr>
            <td><?php echo $text_sub_period; ?></td>
            <td><?php echo $transaction['PERIOD']; ?></td>
          </tr>
          <?php } ?>
        </table>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>