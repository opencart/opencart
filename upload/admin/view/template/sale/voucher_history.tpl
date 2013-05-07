<table class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <td class="right"><?php echo $column_order_id; ?></td>
      <td class="left"><?php echo $column_customer; ?></td>
      <td class="right"><?php echo $column_amount; ?></td>
      <td class="left"><?php echo $column_date_added; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($histories) { ?>
    <?php foreach ($histories as $history) { ?>
    <tr>
      <td class="right"><?php echo $history['order_id']; ?></td>
      <td class="left"><?php echo $history['customer']; ?></td>
      <td class="right"><?php echo $history['amount']; ?></td>
      <td class="left"><?php echo $history['date_added']; ?></td>
    </tr>
    <?php } ?>
    <?php } else { ?>
    <tr>
      <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<div class="pagination"><?php echo $pagination; ?></div>
<div class="results"><?php echo $results; ?></div>
