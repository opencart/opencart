<?php echo $text_shipping; ?>
(<?php echo $shipping_total; ?>)
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_name; ?></td>
        <td class="text-left"><?php echo $column_status; ?></td>
        <td class="text-right"><?php echo $column_sort_order; ?></td>
        <td class="text-right"><?php echo $column_action; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($shippings) { ?>
      <?php foreach ($shippings as $shipping) { ?>
      <tr>
        <td class="text-left"><?php echo $shipping['name']; ?></td>
        <td class="text-left"><?php echo $shipping['status']; ?></td>
        <td class="text-right"><?php echo $shipping['sort_order']; ?></td>
        <td class="text-right"><?php if (!$shipping['installed']) { ?>
          <a href="<?php echo $shipping['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
          <?php } else { ?>
          <a onClick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $shipping['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
          <?php } ?>
          <?php if ($shipping['installed']) { ?>
          <a href="<?php echo $shipping['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
          <?php } else { ?>
          <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-pencil"></i></button>
          <?php } ?></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
