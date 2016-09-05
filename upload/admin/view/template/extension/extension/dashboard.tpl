<fieldset>
  <legend><?php echo $heading_title; ?></legend>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="mi mi-exclamation-circle">error</i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="mi mi-check-circle">check_circle</i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <td class="text-left"><?php echo $column_name; ?></td>
          <td class="text-right"><?php echo $column_width; ?></td>
          <td class="text-left"><?php echo $column_status; ?></td>
          <td class="text-right"><?php echo $column_sort_order; ?></td>
          <td class="text-right"><?php echo $column_action; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php if ($extensions) { ?>
        <?php foreach ($extensions as $extension) { ?>
        <tr>
          <td class="text-left"><?php echo $extension['name']; ?></td>
          <td class="text-right"><?php echo $extension['width']; ?></td>
          <td class="text-left"><?php echo $extension['status']; ?></td>
          <td class="text-right"><?php echo $extension['sort_order']; ?></td>
          <td class="text-right"><?php if ($extension['installed']) { ?>
            <a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="mi mi-pencil">mode_edit</i></a>
            <?php } else { ?>
            <button type="button" class="btn btn-primary" disabled="disabled"><i class="mi mi-pencil">mode_edit</i></button>
            <?php } ?>
            <?php if (!$extension['installed']) { ?>
            <a href="<?php echo $extension['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="mi mi-plus-circle">add_circle</i></a>
            <?php } else { ?>
            <a href="<?php echo $extension['uninstall']; ?>" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="mi mi-minus-circle">remove circle</i></a>
            <?php } ?></td>
        </tr>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</fieldset>
