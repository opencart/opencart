<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-left"><?php echo $column_description; ?></td>
        <td class="text-right"><?php echo $column_points; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($rewards) { ?>
      <?php foreach ($rewards as $reward) { ?>
      <tr>
        <td class="text-left"><?php echo $reward['date_added']; ?></td>
        <td class="text-left"><?php echo $reward['description']; ?></td>
        <td class="text-right"><?php echo $reward['points']; ?></td>
      </tr>
      <?php } ?>
      <tr>
        <td></td>
        <td class="text-right"><b><?php echo $text_balance; ?></b></td>
        <td class="text-right"><?php echo $balance; ?></td>
      </tr>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
