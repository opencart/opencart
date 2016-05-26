<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_name; ?></td>
        <td class="text-right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($modules) { ?>
      <?php foreach ($modules as $module) { ?>
      <tr>
        <td><b><?php echo $module['name']; ?></b></td>
        <td class="text-right"><?php if (!$module['installed']) { ?>
          <a href="<?php echo $module['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
          <?php } else { ?>
          <a onClick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $module['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
          <?php } ?>
          <?php if ($module['installed']) { ?>
          <?php if ($module['module']) { ?>
          <a href="<?php echo $module['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
          <?php } else { ?>
          <a href="<?php echo $module['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
          <?php } ?>
          <?php } else { ?>
          <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
          <?php } ?></td>
      </tr>
      <?php foreach ($module['module'] as $module) { ?>
      <tr>
        <td class="text-left">&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<?php echo $module['name']; ?></td>
        <td class="text-right"><a onClick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $module['delete']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a> <a href="<?php echo $module['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
