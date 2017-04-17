<?php if (isset($error)) { ?>

<div class="warning">Eway Payment Error: <?php echo $error; ?></div>

<?php } else { ?>

<script language="JavaScript" type="text/javascript" >
//<!--
  var submitcount = 0;
  function avoidDuplicationSubmit(){
    if (submitcount == 0) {
      // sumbit form
      submitcount++;
      return true;
    } else {
      alert("Transaction is in progress.");
      return false;
    }
  }
//-->
</script>
<form action="<?php echo $action; ?>" method="post" id="payment" onsubmit="return avoidDuplicationSubmit()">
<input type="hidden" name="EWAY_ACCESSCODE" value="<?php echo $AccessCode; ?>" />
  <?php if (isset($text_testing)) { ?>
  <div class="warning"><?php echo $text_testing; ?></div>
  <?php } ?>
    <font size="2pt"><strong>Credit Card Payment</strong></font>
    <div class="content">

<TABLE id="Table1" cellSpacing="0" cellPadding="3" border="0">
  <TR>
    <TD>
<span id="Label10">Card Holders Name:</span></TD>
    <TD>
<input name="EWAY_CARDNAME" type="text" id="EWAY_CARDNAME" /></TD></TR>
  <TR>
    <TD>
<span id="Label2">Card Number:</span></TD>
    <TD>
<input name="EWAY_CARDNUMBER" type="text" maxlength="17" id="EWAY_CARDNUMBER" />
</TD></TR>
  <TR>
    <TD>
<span id="Label3">Card Expiry:</span></TD>
    <TD>
<select name="EWAY_CARDEXPIRYMONTH" id="EWAY_CARDEXPIRYMONTH">
		<option value="01">01</option>
		<option value="02">02</option>
		<option value="03">03</option>
		<option value="04">04</option>
		<option value="05">05</option>
		<option value="06">06</option>
		<option value="07">07</option>
		<option value="08">08</option>
		<option value="09">09</option>
		<option value="10">10</option>
		<option value="11">11</option>
		<option value="12">12</option>
	</select>
<select name="EWAY_CARDEXPIRYYEAR" id="EWAY_CARDEXPIRYYEAR">

<?php
    $start = date ('Y');
    $end = $start + 7;
    for ($i = $start; $i <= $end; $i++) {
        $j = $i - 2000;
        echo "<option value='$j'>$i</option>";
    }
?>
	</select> month / year</TD></TR>
  <TR>
    <TD>
<span id="Label2">CVN Number:</span></TD>
    <TD>
<input name="EWAY_CARDCVN" type="text" maxlength="5" id="EWAY_CARDCVN" size="5" />
</TD></TR>

</TABLE>
</form>
</div>

<div class="buttons">
    <div class="right"><a onclick="$('#payment').submit();" class="button"><span><?php echo $button_confirm; ?></span></a></div>
</div>

<?php } ?>
