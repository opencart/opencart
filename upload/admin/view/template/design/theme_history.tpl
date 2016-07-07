<div class="table-responsive">
  <table class="table table-bordered">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_store; ?></td>
        <td class="text-left"><?php echo $column_route; ?></td>
        <td class="text-left"><?php echo $column_theme; ?></td>
        <td class="text-left"><?php echo $column_date_added; ?></td>
        <td class="text-right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($histories) { ?>
      <?php foreach ($histories as $history) { ?>
      <tr>
        <td class="text-left"><?php echo $history['store']; ?></td>
        <td class="text-left"><?php echo $history['route']; ?></td>
        <td class="text-left"><?php echo $history['theme']; ?></td>
        <td class="text-left"><?php echo $history['date_added']; ?></td>
        <td class="text-right"><a href="<?php echo $history['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a> <a href="<?php echo $history['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-danger"><i class="fa fa fa-trash-o"></i></a></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
