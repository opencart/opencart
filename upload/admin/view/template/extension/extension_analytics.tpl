<fieldset>
  <legend><?php echo $text_analytics; ?></legend>
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
        <?php if ($analytics) { ?>
        <?php foreach ($analytics as $analytic) { ?>
        <tr>
          <td class="text-left" colspan="2"><b><?php echo $analytic['name']; ?></b></td>
          <td class="text-right"><?php if (!$analytic['installed']) { ?>
            <a href="<?php echo $analytic['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
            <?php } else { ?>
            <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $analytic['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
            <?php } ?></td>
        </tr>
        <?php } ?>
        <?php if ($analytic['installed']) { ?>
        <?php foreach ($analytic['store'] as $store) { ?>
        <tr>
          <td class="text-left">&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;<?php echo $store['name']; ?></td>
          <td class="text-left"><?php echo $store['status']; ?></td>
          <td class="text-right"><a href="<?php echo $store['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
        </tr>
        <?php } ?>
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
