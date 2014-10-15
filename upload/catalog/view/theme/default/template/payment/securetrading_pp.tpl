<form action="https://payments.securetrading.net/process/payments/details" method="POST" class="form-horizontal">
    <fieldset id="payment">
        <legend><?php echo $text_payment_details; ?></legend>

        <input type="hidden" name="sitereference" value="<?php echo $site_reference ?>" />
        <input type="hidden" name="currencyiso3a" value="<?php echo $currency ?>" />
        <input type="hidden" name="mainamount" value="<?php echo $total ?>" />
        <input type="hidden" name="version" value="1" />
        <input type="hidden" name="orderreference" value="<?php echo $order_info['order_id'] ?>" />
        <input type="hidden" name="settlestatus" value="<?php echo $settle_status ?>" />
        <input type="hidden" name="settleduedate" value="<?php echo $settle_due_date ?>" />
        <input type="hidden" name="billingfirstname" value="<?php echo $order_info['payment_firstname'] ?>" />
        <input type="hidden" name="billinglastname" value="<?php echo $order_info['payment_lastname'] ?>" />
        <input type="hidden" name="billingpremise" value="<?php echo $order_info['payment_address_1'] ?>" />
        <input type="hidden" name="billingstreet" value="<?php echo $order_info['payment_address_2'] ?>" />
        <input type="hidden" name="billingtown" value="<?php echo $order_info['payment_city'] ?>" />
        <input type="hidden" name="billingcounty" value="<?php echo $billing_county ?>" />
        <input type="hidden" name="billingpostcode" value="<?php echo $order_info['payment_postcode'] ?>" />
        <input type="hidden" name="billingcountryiso2a" value="<?php echo $payment_country['iso_code_2'] ?>" />
        <input type="hidden" name="billingemail" value="<?php echo $order_info['email'] ?>" />
        <input type="hidden" name="customerpremise" value="<?php echo $order_info['shipping_address_1'] ?>" />
        <input type="hidden" name="customerstreet" value="<?php echo $order_info['shipping_address_2'] ?>" />
        <input type="hidden" name="customertown" value="<?php echo $order_info['shipping_city'] ?>" />
        <input type="hidden" name="customercounty" value="<?php echo $shipping_county ?>" />
        <input type="hidden" name="customerpostcode" value="<?php echo $order_info['shipping_postcode'] ?>" />
        <input type="hidden" name="customercountryiso2a" value="<?php echo $shipping_country['iso_code_2'] ?>" />
        <input type="hidden" name="customeremail" value="<?php echo $order_info['email'] ?>" />

        <?php if ($parent_css) { ?>
        <input type="hidden" name="parentcss" value="<?php echo $parent_css; ?>" />
        <?php } ?>

        <?php if ($child_css) { ?>
        <input type="hidden" name="childcss" value="<?php echo $child_css; ?>" />
        <?php } ?>

        <?php if ($site_security) { ?>
        <input type="hidden" name="sitesecurity" value="g<?php echo $site_security; ?>" />
        <?php } ?>
        
        <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_card_type; ?></label>
            <div class="col-sm-10">
                <select name="paymenttypedescription" id="input-type" class="form-control">
                    <?php foreach ($cards as $key => $title) { ?>
                    <option value="<?php echo $key ?>"><?php echo $title; ?></option>
                    <?php } ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <div class="col-sm-12">
                <div class="buttons">
                    <div class="pull-right">
                        <input type="submit" value="<?php echo $button_confirm; ?>" class="btn btn-primary" />
                    </div>
                </div>
            </div>
        </div>
    </fieldset>
</form>
