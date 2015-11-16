<form action="https://secure.bluepay.com/interfaces/shpf" method="POST">
	<input type="hidden" name="ORDER_ID" value="<?php echo $ORDER_ID; ?>">
	<input type="hidden" name="NAME1" value="<?php echo $NAME1; ?>">
	<input type="hidden" name="NAME2" value="<?php echo $NAME2; ?>">
	<input type="hidden" name="ADDR1" value="<?php echo $ADDR1; ?>">
	<input type="hidden" name="ADDR2" value="<?php echo $ADDR2; ?>">
	<input type="hidden" name="CITY" value="<?php echo $CITY; ?>">
	<input type="hidden" name="STATE" value="<?php echo $STATE; ?>">
	<input type="hidden" name="ZIPCODE" value="<?php echo $ZIPCODE; ?>">
	<input type="hidden" name="COUNTRY" value="<?php echo $COUNTRY; ?>">
	<input type="hidden" name="PHONE" value="<?php echo $PHONE; ?>">
	<input type="hidden" name="EMAIL" value="<?php echo $EMAIL; ?>">
    <input type="hidden" name="SHPF_FORM_ID" value="<?php echo $SHPF_FORM_ID; ?>">
    <input type="hidden" name="SHPF_ACCOUNT_ID" value="<?php echo $SHPF_ACCOUNT_ID; ?>">
    <input type="hidden" name="SHPF_TPS" value="<?php echo $SHPF_TPS; ?>">
    <input type="hidden" name="SHPF_TPS_DEF" value="<?php echo $SHPF_TPS_DEF; ?>">
    <input type="hidden" name="MODE" value="<?php echo $MODE; ?>">
    <input type="hidden" name="TRANSACTION_TYPE" value="<?php echo $TRANSACTION_TYPE; ?>">
    <input type="hidden" name="MERCHANT" value="<?php echo $MERCHANT; ?>">
    <input type="hidden" name="DBA" value="<?php echo $DBA; ?>">
    <input type="hidden" name="TAMPER_PROOF_SEAL" value="<?php echo $TAMPER_PROOF_SEAL; ?>">
    <input type="hidden" name="CARD_TYPES" value="<?php echo $CARD_TYPES; ?>">
    <input type="hidden" name="REDIRECT_URL" value="<?php echo $REDIRECT_URL; ?>">
    <input type="hidden" name="APPROVED_URL" value="<?php echo $APPROVED_URL; ?>">
    <input type="hidden" name="DECLINED_URL" value="<?php echo $DECLINED_URL; ?>">
    <input type="hidden" name="MISSING_URL" value="<?php echo $MISSING_URL; ?>">
    <input type="hidden" name="TPS_DEF" value="<?php echo $TPS_DEF; ?>">
    <input type="hidden" name="AMOUNT" value="<?php echo $AMOUNT; ?>">
    <div class="buttons">
        <div class="pull-right">
            <input type="submit" value="<?php echo $button_confirm; ?>" id="button-submit" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary" />
        </div>
    </div>
</form>