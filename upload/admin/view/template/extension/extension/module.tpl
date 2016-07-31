<fieldset>
  <legend><?php echo $heading_title; ?></legend>
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
  <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_layout; ?></div>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <td class="text-left"><?php echo $column_name; ?></td>
          <td class="text-right"><?php echo $column_action; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php if ($extensions) { ?>
        <?php foreach ($extensions as $extension) { ?>
        <tr>
          <td><b><?php echo $extension['name']; ?></b></td>
          <td class="text-right">
            <?php if ($extension['installed']) { ?>
            <?php if ($extension['module']) { ?>
            <a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></a>
            <?php } else { ?>
            <a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
            <?php } ?>
            <?php } else { ?>
            <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
            <?php } ?>
            <?php if (!$extension['installed']) { ?>
            <a href="<?php echo $extension['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-magic"></i></a>
            <?php } else { ?>
            <a href="<?php echo $extension['uninstall']; ?>" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
            <?php } ?>
            </td>
        </tr>
        <?php foreach ($extension['module'] as $module) { ?>
        <tr>
          <td class="text-left">&nbsp;&nbsp;&nbsp;<i class="fa fa-folder-open"></i>&nbsp;&nbsp;&nbsp;<?php echo $module['name']; ?></td>
          <td class="text-right"><a href="<?php echo $module['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-info"><i class="fa fa-pencil"></i></a> <a href="<?php echo $module['delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-warning"><i class="fa fa-trash-o"></i></a></td>
        </tr>
        <?php } ?>
        <?php } ?>
        <?php } else { ?>
        <tr>
          <td class="text-center" colspan="2"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</fieldset>
