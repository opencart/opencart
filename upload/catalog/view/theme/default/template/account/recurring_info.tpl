<?php echo $header; ?>

	  <?php if ($error_warning) { ?>
	  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
	  <?php } ?>
	  
	  <h2><?php echo $heading_title; ?></h2>
      <table class="table table-bordered table-hover">
        <thead>
        <tr>
          <td class="text-left" colspan="2"><?php echo $text_recurring_detail; ?></td>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td class="text-left" style="width: 50%;">
            <p><b><?php echo $text_recurring_id; ?></b> #<?php echo $profile['order_recurring_id']; ?></p>
            <p><b><?php echo $text_date_added; ?></b> <?php echo $profile['created']; ?></p>
            <p><b><?php echo $text_status; ?></b> <?php echo $status_types[$profile['status']]; ?></p>
            <p><b><?php echo $text_payment_method; ?></b> <?php echo $profile['payment_method']; ?></p>
          </td>
          <td class="left" style="width: 50%; vertical-align: top;">
            <p><b><?php echo $text_product; ?></b><a href="<?php echo $profile['product_link']; ?>"><?php echo $profile['product_name']; ?></a></p>
            <p><b><?php echo $text_quantity; ?></b> <?php echo $profile['product_quantity']; ?></p>
            <p><b><?php echo $text_order; ?></b><a href="<?php echo $profile['order_link']; ?>">#<?php echo $profile['order_id']; ?></a></p>
          </td>
        </tr>
        </tbody>
      </table>
      <table class="table table-bordered table-hover">
        <thead>
        <tr>
          <td class="text-left"><?php echo $text_recurring_description; ?></td>
          <td class="text-left"><?php echo $text_ref; ?></td>
        </tr>
        </thead>
        <tbody>
        <tr>
          <td class="text-left" style="width: 50%;">
            <p style="margin:5px;"><?php echo $profile['profile_description']; ?></p></td>
          <td class="text-left" style="width: 50%;">
            <p style="margin:5px;"><?php echo $profile['profile_reference']; ?></p></td>
        </tr>
        </tbody>
      </table>
      <h2><?php echo $text_transactions; ?></h2>
      <table class="table table-bordered table-hover">
        <thead>
        <tr>
          <td class="text-left"><?php echo $column_created; ?></td>
          <td class="text-center"><?php echo $column_type; ?></td>
          <td class="text-right"><?php echo $column_amount; ?></td>
        </tr>
        </thead>
        <tbody>
        <?php if (!empty($profile['transactions'])) { ?><?php foreach ($profile['transactions'] as $transaction) { ?>
        <tr>
          <td class="text-left"><?php echo $transaction['created']; ?></td>
          <td class="text-center"><?php echo $transaction_types[$transaction['type']]; ?></td>
          <td class="text-right"><?php echo $transaction['amount']; ?></td>
        </tr>
        <?php } ?><?php }else{ ?>
        <tr>
          <td colspan="3" class="text-center"><?php echo $text_empty_transactions; ?></td>
        </tr>
        <?php } ?>
        </tbody>
      </table>
      <?php echo $buttons; ?>

<?php echo $footer; ?>