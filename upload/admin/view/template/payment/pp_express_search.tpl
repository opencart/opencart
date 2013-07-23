<?php echo $header; ?>
<div id="content">

    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <div class="box">
        <div class="heading">
            <h1><img src="view/image/payment.png" alt=""/> <?php echo $heading_title; ?></h1>
            <div class="buttons">
                <a class="button" onclick="editSearch();" id="btn_edit" style="display:none;"><?php echo $btn_edit_search; ?></a>
                <a class="button" onclick="doSearch();" id="btn_search"><?php echo $btn_search; ?></a>
            </div>
        </div>

        <div class="content">

            <form id="form">
                <div id="search_input">
                    <table class="form">
                        <tr>
                            <td><?php echo $entry_date; ?></td>
                            <td>
                                <input type="text" id="date_start" name="date_start" value="<?php echo $date_start; ?>" size="12" class="date" placeholder="<?php echo $entry_date_start; ?>" />
                                &nbsp;&nbsp;<?php echo $entry_date_to; ?>&nbsp;&nbsp;<input type="text" name="date_end" size="12" class="date" placeholder="<?php echo $entry_date_end; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_transaction; ?></td>
                            <td>
                                <?php echo $entry_transaction_type; ?>:
                                <select name="transaction_class">
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
                                &nbsp;&nbsp;
                                <?php echo $entry_transaction_status; ?>:
                                <select name="status">
                                    <option value=""><?php echo $entry_status_all; ?></option>
                                    <option value="Pending"><?php echo $entry_status_pending; ?></option>
                                    <option value="Processing"><?php echo $entry_status_processing; ?></option>
                                    <option value="Success"><?php echo $entry_status_success; ?></option>
                                    <option value="Denied"><?php echo $entry_status_denied; ?></option>
                                    <option value="Reversed"><?php echo $entry_status_reversed; ?></option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_email; ?></td>
                            <td>
                                <input maxlength="127" type="text" name="buyer_email" value="" placeholder="<?php echo $entry_email_buyer; ?>" />&nbsp;&nbsp;
                                <input maxlength="127" type="text" name="merchant_email" value="" placeholder="<?php echo $entry_email_merchant; ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_receipt; ?></td>
                            <td><input type="text" name="receipt_id" value="" maxlength="100" /></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_transaction_id; ?></td>
                            <td><input type="text" name="transaction_id" value="" maxlength="19" /></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_invoice_no; ?></td>
                            <td><input type="text" name="invoice_number" value="" maxlength="127" /></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_auction; ?></td>
                            <td><input type="text" name="auction_item_number" value="" /></td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_amount; ?></td>
                            <td>
                                <input type="text" name="amount" value="" size="6" />&nbsp;
                                <select name="currency_code">
                                    <?php foreach($currency_codes as $code){ ?>
                                        <option <?php if($code == $default_currency){ echo 'selected'; } ?>><?php echo $code; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td><?php echo $entry_profile_id; ?></td>
                            <td><input type="text" name="profile_id" value=""  /></td>
                        </tr>
                    </table>

                    <h3><?php echo $text_buyer_info; ?></h3>

                    <table class="form">
                        <tr>
                            <td><?php echo $text_name; ?></td>
                            <td>
                                <input type="text" name="name_salutation" value="" placeholder="<?php echo $entry_salutation; ?>" />&nbsp;&nbsp;
                                <input type="text" name="name_first" value="" placeholder="<?php echo $entry_firstname; ?>" />&nbsp;&nbsp;
                                <input type="text" name="name_middle" value="" placeholder="<?php echo $entry_middlename; ?>" />&nbsp;&nbsp;
                                <input type="text" name="name_last" value="" placeholder="<?php echo $entry_lastname; ?>" />
                                <input type="text" name="name_suffix" value="" placeholder="<?php echo $entry_suffix; ?>" />
                            </td>
                        </tr>
                    </table>

                </div>
            </form>

            <div id="search_box" style="display:none;">
                <div id="searching"><img src="<?php echo HTTPS_SERVER; ?>view/image/loading.gif" /> <?php echo $text_searching; ?></div>
                <div id="error" class="warning" style="display:none;"></div>
                <table id="search_results" style="display:none;" class="list" ></table>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
//--></script>
<script type="text/javascript"><!--

    function doSearch(){
        var html;

        $.ajax({
            type:'POST',
            dataType: 'json',
            data: $('#form').serialize(),
            url: 'index.php?route=payment/pp_express/doSearch&token=<?php echo $token; ?>',
            beforeSend: function(){
                $('#search_input').hide();
                $('#search_box').show();
                $('#btn_search').hide();
                $('#btn_edit').show();
            },
            success: function(data){
                if(data.error == true){
                    $('#searching').hide();
                    $('#error').text(data.error_msg).fadeIn();
                }else{
                    if(data.result != ''){
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

                        $.each(data.result, function(k,v){

                            if(!("L_EMAIL" in v)){
                                v.L_EMAIL = '';
                            }

                            html += '<tr>';
                                html += '<td class="left">'+ v.L_TIMESTAMP+'</td>';
                                html += '<td class="left">'+ v.L_TYPE+'</td>';
                                html += '<td class="left">'+ v.L_EMAIL +'</td>';
                                html += '<td class="left">'+ v.L_NAME+'</td>';
                                html += '<td class="left">'+ v.L_TRANSACTIONID+'</td>';
                                html += '<td class="left">'+ v.L_STATUS+'</td>';
                                html += '<td class="left">'+ v.L_CURRENCYCODE+'</td>';
                                html += '<td class="right">'+ v.L_AMT+'</td>';
                                html += '<td class="right">'+ v.L_FEEAMT+'</td>';
                                html += '<td class="right">'+ v.L_NETAMT+'</td>';
                                html += '<td class="center">';
                                    html += '<a href="<?php echo $view_link; ?>&transaction_id='+v.L_TRANSACTIONID+'"><?php echo $text_view; ?></a>';
                                html += '</td>';
                            html += '</tr>';
                        });

                        $('#searching').hide();
                        $('#search_results').append(html).fadeIn();
                    }
                }
            }
        });
    }

    function editSearch(){
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