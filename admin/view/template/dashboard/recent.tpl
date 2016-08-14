<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title"><i class="fa fa-shopping-cart"></i> <?php echo $heading_title; ?></h3>
  </div>
  <div class="table-responsive">
    <table class="table">
      <thead>
        <tr>
          <td class="text-right"><?php echo $column_order_id; ?></td>
          <td><?php echo $column_customer; ?></td>
          <td><?php echo $column_status; ?></td>
          <td><?php echo $column_date_added; ?></td>
          <td class="text-right"><?php echo $column_total; ?></td>
          <td class="text-right"><?php echo $column_action; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php if ($orders) { ?>
        <?php foreach ($orders as $order) { ?>
        <tr>
          <td class="text-right"><?php echo $order['order_id']; ?></td>
          <td><?php echo $order['customer']; ?></td>
          <td><?php echo $order['status']; ?></td>
          <td><?php echo $order['date_added']; ?></td>
          <td class="text-right"><?php echo $order['total']; ?></td>
          <td class="text-right"><a href="<?php echo $order['view']; ?>" data-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="6"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
