{{ header }}
<div class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row">{{ column_left }}
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="{{ class }}">{{ content_top }}
      <h2>{{ heading_title }}</h2>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left" colspan="2">{{ text_recurring_detail }}</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-left" style="width: 50%;"><b>{{ text_order_recurring_id; ?></b> #<?php echo $order_recurring_id }}<br />
                <b>{{ text_date_added; ?></b> <?php echo $date_added }}<br />
                <b>{{ text_status; ?></b> <?php echo $status }}<br />
                <b>{{ text_payment_method; ?></b> <?php echo $payment_method }}</td>
              <td class="text-left" style="width: 50%;"><b>{{ text_order_id; ?></b> <a href="<?php echo $order; ?>">#<?php echo $order_id }}</a><br />
                <b>{{ text_product }}</b> <a href="<?php echo $product; ?>"><?php echo $product_name; ?></a><br />
                <b>{{ text_quantity; ?></b> <?php echo $product_quantity }}</td>
            </tr>
          </tbody>
        </table>
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left">{{ text_description }}</td>
              <td class="text-left">{{ text_reference }}</td>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td class="text-left" style="width: 50%;"><?php echo $recurring_description; ?></td>
              <td class="text-left" style="width: 50%;"><?php echo $reference; ?></td>
            </tr>
          </tbody>
        </table>
      </div>
      <h3>{{ text_transaction }}</h3>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-left">{{ column_date_added; }}</td>
              <td class="text-left">{{ column_type; }}</td>
              <td class="text-right">{{ column_amount; }}</td>
            </tr>
          </thead>
          <tbody>
            <?php if ($transactions) { ?>
            <?php foreach ($transactions as $transaction) { ?>
            <tr>
              <td class="text-left"><?php echo $transaction['date_added']; ?></td>
              <td class="text-left"><?php echo $transaction['type']; ?></td>
              <td class="text-right"><?php echo $transaction['amount']; ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td colspan="3" class="text-center">{{ text_no_results }}</td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <?php echo $recurring; ?>{{ content_bottom }}</div>
    {{ column_right }}</div>
</div>
{{ footer }}
