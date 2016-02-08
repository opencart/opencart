<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left" colspan="2"><?php echo $text_recurring_detail; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td style="width: 50%;"><table style="width: 100%;">
                <tr>
                  <td class="text-left" style="width: 50%;"><b><?php echo $text_order_recurring_id; ?></b></td>
                  <td class="text-left" style="width: 50%;">#<?php echo $order_recurring_id; ?></td>
                </tr>
                <tr>
                  <td class="text-left"><b><?php echo $text_date_added; ?></b></td>
                  <td class="text-left"><?php echo $date_added; ?></td>
                </tr>
                <tr>
                  <td class="text-left"><b><?php echo $text_status; ?></b></td>
                  <td class="text-left"><?php echo $status; ?></td>
                </tr>
                <tr>
                  <td class="text-left"><b><?php echo $text_payment_method; ?></b></td>
                  <td class="text-left"><?php echo $payment_method; ?></td>
                </tr>
              </table></td>
            <td style="width: 50%;"><table style="width: 100%;">
                <tr>
                  <td class="text-left" style="width: 50%;"><b><?php echo $text_order_id; ?></b></td>
                  <td class="text-left" style="width: 50%;"><a href="<?php echo $order; ?>">#<?php echo $order_id; ?></a></td>
                </tr>
                <tr>
                  <td class="text-left"><b><?php echo $text_product; ?></b></td>
                  <td class="text-left"><a href="<?php echo $product; ?>"><?php echo $product_name; ?></a></td>
                </tr>
                <tr>
                  <td class="text-left"><b><?php echo $text_quantity; ?></b></td>
                  <td class="text-left"><?php echo $product_quantity; ?></td>
                </tr>
              </table></td>
          </tr>
        </tbody>
      </table>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $text_description; ?></td>
            <td class="text-left"><?php echo $text_reference; ?></td>
          </tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left" style="width: 50%;"><p style="margin: 5px;"><?php echo $recurring_description; ?></p></td>
            <td class="text-left" style="width: 50%;"><p style="margin: 5px;"><?php echo $reference; ?></p></td>
          </tr>
        </tbody>
      </table>
      <h3><?php echo $text_transaction; ?></h3>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $column_date_added; ?></td>
            <td class="text-center"><?php echo $column_type; ?></td>
            <td class="text-right"><?php echo $column_amount; ?></td>
          </tr>
        </thead>
        <tbody>
          <?php if ($transactions) { ?>
          <?php foreach ($transactions as $transaction) { ?>
          <tr>
            <td class="text-left"><?php echo $transaction['date_added']; ?></td>
            <td class="text-center"><?php echo $transaction['type']; ?></td>
            <td class="text-right"><?php echo $transaction['amount']; ?></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td colspan="3" class="text-center"><?php echo $text_no_results; ?></td>
          </tr>
          <?php } ?>
        </tbody>
      </table>
      <?php echo $recurring; ?><?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>