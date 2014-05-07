<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right"> <a href="<?php echo $return; ?>" class="btn btn-danger"><i class="fa fa-times"></i> <?php echo $text_return; ?></a> </div>
      <h1 class="panel-title"><i class="fa fa-info-circle"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <table class="table table-striped table-bordered">
        <tr>
          <td><?php echo $entry_order_recurring; ?></td>
          <td><?php echo $order_recurring_id; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_order_id; ?></td>
          <td><a href="<?php echo $order_href; ?>"><?php echo $order_id; ?></a></td>
        </tr>
        <tr>
          <td><?php echo $entry_customer; ?></td>
          <td><?php if ($customer_href) { ?>
            <a href="<?php echo $customer_href ?>"><?php echo $customer; ?></a>
            <?php } else { ?>
            <?php echo $customer; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_email; ?></td>
          <td><?php echo $email; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><?php echo $status; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_date_created; ?></td>
          <td><?php echo $date_created; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_payment_reference; ?></td>
          <td><?php echo $profile_reference; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_payment_type; ?></td>
          <td><?php echo $payment_method; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_profile; ?></td>
          <td><?php if ($profile) { ?>
            <a href="<?php echo $profile; ?>"><?php echo $profile_name; ?></a>
            <?php } else { ?>
            <?php echo $profile_name; ?>
            <?php } ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_profile_description; ?></td>
          <td><?php echo $profile_description; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_product; ?></td>
          <td><?php echo $product; ?></td>
        </tr>
        <tr>
          <td><?php echo $entry_quantity; ?></td>
          <td><?php echo $quantity; ?></td>
        </tr>
      </table>
      <?php echo $buttons; ?>
      <h2><?php echo $text_transactions; ?></h2>
      <table class="table table-striped table-bordered">
        <thead>
          <tr>
            <td class="text-left"><?php echo $entry_date_created; ?></td>
            <td class="text-left"><?php echo $entry_amount; ?></td>
            <td class="text-left"><?php echo $entry_type; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($transactions as $transaction) { ?>
          <tr>
            <td class="text-left"><?php echo $transaction['created']; ?></td>
            <td class="text-left"><?php echo $transaction['amount']; ?></td>
            <td class="text-left"><?php echo $transaction['type']; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php echo $footer; ?>