<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $x_response_reason_text; ?></h1>
    </div>
  </div>
  <div class="middle">
<?php if($x_response_code == '1') { ?>
   <p>Your payment was processed successfully. Here is your receipt:</p>
   <pre>
<?php echo $exact_ctr; ?></pre>

<?php if(!empty($exact_issname)) { ?>
     <p>Issuer: <?php echo $exact_issname; ?><br/>
     Confirmation Number: <?php echo $exact_issconf; ?>
     </p>
<?php } ?>
<div class="buttons">
  <table>
    <tr>
      <td align="left"></td>
      <td align="right"><a onclick="location = '<?php echo $confirm; ?>'" class="button"><span><?php echo $button_confirm; ?></span></a></td>
    </tr>
  </table>
</div>
<?php } elseif($_REQUEST['x_response_code'] == '2') { ?>
   <p>Your payment failed.  Here is your receipt.</p>
   <pre>
<?php echo $exact_ctr; ?></pre>
   <div class="buttons">
	  <table>
	    <tr>
	      <td align="left"><a onclick="location = '<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
	      <td align="right"></td>
	    </tr>
	  </table>
	</div>
<?php } else { ?>
   <p>An error occurred while processing your payment. Please try again later.</p>
   <div class="buttons">
	  <table>
	    <tr>
	      <td align="left"><a onclick="location = '<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
	      <td align="right"></td>
	    </tr>
	  </table>
	</div>
<?php } ?>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> 
				
