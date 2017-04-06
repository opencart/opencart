<table class="table table-bordered">
  <tr>
    <td style="text-align:center; background-color:#ab1b1c; border:1px solid #ab1b1c;" colspan="2"><img src="https://www.fraudlabspro.com/images/logo_200.png" alt="FraudLabs Pro" /></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_transaction_id; ?>"><?php echo $text_transaction_id; ?></span></td>
    <td><a href="https://www.fraudlabspro.com/merchant/transaction-details/<?php echo $flp_id; ?>/" target="_blank"><?php echo $flp_id; ?></a></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_score; ?>"><?php echo $text_score; ?></span></td>
    <td><img class="img-responsive" alt="" src="//fraudlabspro.hexa-soft.com/images/fraudscore/fraudlabsproscore<?php echo $flp_score; ?>.png" /></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_status; ?>"><?php echo $text_status; ?></span></td>
    <td id="flp_status"><span style="font-weight:bold; color:<?php if (strtolower($flp_status) == 'approve') echo '#5cb85c'; else if (strtolower($flp_status) == 'review') echo '#f0ad4e'; else echo '#d9534f'; ?>"><?php echo $flp_status; ?></span></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_ip_address; ?>"><?php echo $text_ip_address; ?></span></td>
    <td><?php echo $flp_ip_address; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_ip_net_speed; ?>"><?php echo $text_ip_net_speed; ?></span></td>
    <td><?php echo $flp_ip_net_speed; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_ip_isp_name; ?>"><?php echo $text_ip_isp_name; ?></span></td>
    <td><?php echo $flp_ip_isp_name; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_ip_usage_type; ?>"><?php echo $text_ip_usage_type; ?></span></td>
    <td><?php echo $flp_ip_usage_type; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_ip_domain; ?>"><?php echo $text_ip_domain; ?></span></td>
    <td><?php echo $flp_ip_domain; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_ip_time_zone; ?>"><?php echo $text_ip_time_zone; ?></span></td>
    <td><?php echo $flp_ip_time_zone; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_ip_location; ?>"><?php echo $text_ip_location; ?></span></td>
    <td><?php echo $flp_ip_location; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_ip_distance; ?>"><?php echo $text_ip_distance; ?></span></td>
    <td><?php echo $flp_ip_distance; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_ip_latitude; ?>"><?php echo $text_ip_latitude; ?></span></td>
    <td><?php echo $flp_ip_latitude; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_ip_longitude; ?>"><?php echo $text_ip_longitude; ?></span></td>
    <td><?php echo $flp_ip_longitude; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_risk_country; ?>"><?php echo $text_risk_country; ?></span></td>
    <td><?php echo $flp_risk_country; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_free_email; ?>"><?php echo $text_free_email; ?></span></td>
    <td><?php echo $flp_free_email; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_ship_forward; ?>"><?php echo $text_ship_forward; ?></span></td>
    <td><?php echo $flp_ship_forward; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_using_proxy; ?>"><?php echo $text_using_proxy; ?></span></td>
    <td><?php echo $flp_using_proxy; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_bin_found; ?>"><?php echo $text_bin_found; ?></span></td>
    <td><?php echo $flp_bin_found; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_email_blacklist; ?>"><?php echo $text_email_blacklist; ?></span></td>
    <td><?php echo $flp_email_blacklist; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_credit_card_blacklist; ?>"><?php echo $text_credit_card_blacklist; ?></span></td>
    <td><?php echo $flp_credit_card_blacklist; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_credits; ?>"><?php echo $text_credits; ?></span></td>
    <td><?php echo $flp_credits . ' ' . $text_flp_upgrade; ?></td>
  </tr>
  <tr>
    <td><span data-toggle="tooltip" data-container="#tab-general" title="<?php echo $help_message; ?>"><?php echo $text_message; ?></span></td>
    <td><?php echo $flp_message; ?></td>
  </tr>
  
  
  <?php if (strtolower($flp_status) == 'review'){?>
  <tr style="background-color:#eee;">
    <td id="flp_action" colspan="2">
      <form id="review-action" method="post">
	<div align="center">
	  <button type="button" id="button-flp-approve" class="btn btn-primary"><i class="fa fa-check"></i> Approve</button>
	  <button type="button" id="button-flp-reject" class="btn btn-danger"><i class="fa fa-remove"></i> Reject</button>
	</div>
	<input type="hidden" id="flp_id" name="flp_id" value="<?php echo $flp_id; ?>" />
	<input type="hidden" id="new_status" name="new_status" value="" />
      </form>
      
      <script>
	$(document).ready(function(){
		$("#button-flp-approve").click(function(){
			$("#new_status").val("APPROVE");
			$("#review-action").submit();
		});
	});
	
	$(document).ready(function(){
		$("#button-flp-reject").click(function(){
			$("#new_status").val("REJECT");
			$("#review-action").submit();
		});
	});
      </script>
    </td>
  </tr>
  <?php } ?>
</table>
<div>
	<?php echo $text_flp_merchant_area; ?>
</div>