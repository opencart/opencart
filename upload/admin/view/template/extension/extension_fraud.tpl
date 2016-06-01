<fieldset>
  <legend><?php echo $text_fraud; ?></legend>
  <div class="table-responsive">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <td class="text-left"><?php echo $column_name; ?></td>
          <td class="text-left"><?php echo $column_status; ?></td>
          <td class="text-right"><?php echo $column_action; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php if ($frauds) { ?>
        <?php foreach ($frauds as $fraud) { ?>
        <tr>
          <td class="text-left"><?php echo $fraud['name']; ?></td>
          <td class="text-left"><?php echo $fraud['status']; ?></td>
          <td class="text-right"><?php if ($fraud['installed']) { ?>
            <a href="<?php echo $fraud['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
            <?php } else { ?>
            <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
            <?php } ?>
            <?php if (!$fraud['installed']) { ?>
            <a href="<?php echo $fraud['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
            <?php } else { ?>
            <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $fraud['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
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
