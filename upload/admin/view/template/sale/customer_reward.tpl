<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<table class="list">
  <thead>
    <tr>
      <td class="left"><?php echo $column_date_added; ?></td>
      <td class="left"><?php echo $column_description; ?></td>
      <td class="right"><?php echo $column_points; ?></td>
    </tr>
  </thead>
  <tbody>
    <?php if ($rewards) { ?>
    <?php foreach ($rewards as $reward) { ?>
    <tr>
      <td class="left"><?php echo $reward['date_added']; ?></td>
      <td class="left"><?php echo $reward['description']; ?></td>
      <td class="right"><?php echo $reward['points']; ?></td>
    </tr>
    <?php } ?>
    <tr>
      <td></td>
      <td class="right"><b><?php echo $text_balance; ?></b></td>
      <td class="right"><?php echo $balance; ?></td>
    </tr>    
    <?php } else { ?>
    <tr>
      <td class="center" colspan="3"><?php echo $text_no_results; ?></td>
    </tr>
    <?php } ?>
  </tbody>
</table>
<div class="pagination"><?php echo $pagination; ?></div>