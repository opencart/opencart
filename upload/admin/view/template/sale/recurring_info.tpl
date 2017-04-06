<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="row">
      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_recurring_detail; ?></h3>
          </div>
          <div class="panel-body">
            <table class="table table-bordered">
              <tr>
                <td><?php echo $text_order_recurring_id; ?></td>
                <td><?php echo $order_recurring_id; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_reference; ?></td>
                <td><?php echo $reference; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_recurring_name; ?></td>
                <td><?php if ($recurring) { ?>
                  <a href="<?php echo $recurring; ?>"><?php echo $recurring_name; ?></a>
                  <?php } else { ?>
                  <?php echo $recurring_name; ?>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $text_recurring_description; ?></td>
                <td><?php echo $recurring_description; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_recurring_status; ?></td>
                <td><?php echo $recurring_status; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_payment_method; ?></td>
                <td><?php echo $payment_method; ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
      <div class="col-lg-6 col-md-6 col-sm-12">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_order_detail; ?></h3>
          </div>
          <div class="panel-body">
            <table class="table table-bordered">
              <tr>
                <td><?php echo $text_order_id; ?></td>
                <td><a href="<?php echo $order; ?>"><?php echo $order_id; ?></a></td>
              </tr>
              <tr>
                <td><?php echo $text_customer; ?></td>
                <td><?php if ($customer) { ?>
                  <a href="<?php echo $customer; ?>"><?php echo $firstname; ?> <?php echo $lastname; ?></a>
                  <?php } else { ?>
                  <?php echo $firstname; ?> <?php echo $lastname; ?>
                  <?php } ?></td>
              </tr>
              <tr>
                <td><?php echo $text_email; ?></td>
                <td><?php echo $email; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_order_status; ?></td>
                <td><?php echo $order_status; ?></td>
              </tr>
              <tr>
                <td><?php echo $text_date_added; ?></td>
                <td><?php echo $date_added; ?></td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_product_detail; ?></h3>
      </div>
      <div class="panel-body">
        <table class="table table-bordered">
          <thead>
            <tr>
              <td><?php echo $text_product; ?></td>
              <td><?php echo $text_quantity; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td><?php echo $product; ?></td>
              <td><?php echo $quantity; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_transaction; ?></h3>
      </div>
      <div class="panel-body"> <?php echo $buttons; ?>
        <table class="table table-bordered">
          <thead>
            <tr>
              <td class="text-left"><?php echo $column_date_added; ?></td>
              <td class="text-right"><?php echo $column_amount; ?></td>
              <td class="text-left"><?php echo $column_type; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($transactions) { ?>
            <?php foreach ($transactions as $transaction) { ?>
            <tr>
              <td class="text-left"><?php echo $transaction['date_added']; ?></td>
              <td class="text-right"><?php echo $transaction['amount']; ?></td>
              <td class="text-left"><?php echo $transaction['type']; ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?> 