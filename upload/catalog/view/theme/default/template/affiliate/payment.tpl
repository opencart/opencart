<?php echo $header; ?>
<ul class="breadcrumb">
  <?php foreach ($breadcrumbs as $breadcrumb) { ?>
  <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
  <?php } ?>
</ul>
<div class="row"><?php echo $column_left; ?>
  <div id="content" class="span9"><?php echo $content_top; ?>
    <h1><?php echo $heading_title; ?></h1>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
      <fieldset>
        <legend><?php echo $text_your_payment; ?></legend>
        <div class="form-group">
          <label class="col-lg-2 control-label" for="input-tax"><?php echo $entry_tax; ?></label>
          <div class="col-lg-10">
            <input type="text" name="tax" value="<?php echo $tax; ?>" placeholder="<?php echo $entry_tax; ?>" id="input-tax" />
          </div>
        </div>
        <div class="form-group">
          <div class="col-lg-2 control-label"><?php echo $entry_payment; ?></div>
          <div class="col-lg-10">
            <label>
              <?php if ($payment == 'cheque') { ?>
              <input type="radio" name="payment" value="cheque" checked="checked" />
              <?php } else { ?>
              <input type="radio" name="payment" value="cheque" />
              <?php } ?>
              <?php echo $text_cheque; ?></label>
            <label>
              <?php if ($payment == 'paypal') { ?>
              <input type="radio" name="payment" value="paypal" checked="checked" />
              <?php } else { ?>
              <input type="radio" name="payment" value="paypal" />
              <?php } ?>
              <?php echo $text_paypal; ?></label>
            <label>
              <?php if ($payment == 'bank') { ?>
              <input type="radio" name="payment" value="bank" checked="checked" />
              <?php } else { ?>
              <input type="radio" name="payment" value="bank" />
              <?php } ?>
              <?php echo $text_bank; ?></label>
          </div>
        </div>
        <div class="control-group payment" id="payment-cheque">
          <label class="col-lg-2 control-label" for="input-cheque"><?php echo $entry_cheque; ?></label>
          <div class="col-lg-10">
            <input type="text" name="cheque" value="<?php echo $cheque; ?>" placeholder="<?php echo $entry_cheque; ?>" id="input-cheque" />
          </div>
        </div>
        <div class="control-group payment" id="payment-paypal">
          <label class="col-lg-2 control-label" for="input-paypal"><?php echo $entry_paypal; ?></label>
          <div class="col-lg-10">
            <input type="text" name="paypal" value="<?php echo $paypal; ?>" placeholder="<?php echo $entry_paypal; ?>" id="input-paypal" />
          </div>
        </div>
        <div class="payment" id="payment-bank">
          <div class="form-group">
            <label class="col-lg-2 control-label" for="input-bank-name"><?php echo $entry_bank_name; ?></label>
            <div class="col-lg-10">
              <input type="text" name="bank_name" value="<?php echo $bank_name; ?>" placeholder="<?php echo $entry_bank_name; ?>" id="input-bank-name" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label" for="input-bank-branch-number"><?php echo $entry_bank_branch_number; ?></label>
            <div class="col-lg-10">
              <input type="text" name="bank_branch_number" value="<?php echo $bank_branch_number; ?>" placeholder="<?php echo $entry_bank_branch_number; ?>" id="input-bank-branch-number" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label" for="input-bank-swift-code"><?php echo $entry_bank_swift_code; ?></label>
            <div class="col-lg-10">
              <input type="text" name="bank_swift_code" value="<?php echo $bank_swift_code; ?>" placeholder="<?php echo $entry_bank_swift_code; ?>" id="input-bank-swift-code" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label" for="input-bank-account-name"><?php echo $entry_bank_account_name; ?></label>
            <div class="col-lg-10">
              <input type="text" name="bank_account_name" value="<?php echo $bank_account_name; ?>" placeholder="<?php echo $entry_bank_account_name; ?>" id="input-bank-account-name" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-lg-2 control-label" for="input-bank-account-number"><?php echo $entry_bank_account_number; ?></label>
            <div class="col-lg-10">
              <input type="text" name="bank_account_number" value="<?php echo $bank_account_number; ?>" placeholder="<?php echo $entry_bank_account_number; ?>" id="input-bank-account-number" />
            </div>
          </div>
        </div>
      </fieldset>
      <div class="buttons clearfix">
        <div class="pull-left"><a href="<?php echo $back; ?>" class="btn"><?php echo $button_back; ?></a></div>
        <div class="pull-right">
          <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
        </div>
      </div>
    </form>
    <?php echo $content_bottom; ?> </div>
  <?php echo $column_right; ?> </div>
<script type="text/javascript"><!--
$('input[name=\'payment\']').bind('change', function() {
    $('.payment').hide();
    
    $('#payment-' + this.value).show();
});

$('input[name=\'payment\']:checked').trigger('change');
//--></script> 
<?php echo $footer; ?> 