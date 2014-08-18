<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-store').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><i class="fa fa-bars"></i> <?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid">
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
    <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-store">
      <div class="table-responsive">
        <table class="table table-striped table-hover">
          <thead>
            <tr>
              <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
              <td class="text-left"><?php echo $column_name; ?></td>
              <td class="text-left"><?php echo $column_url; ?></td>
              <td class="text-right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($stores) { ?>
            <?php foreach ($stores as $store) { ?>
            <tr>
              <td class="text-center"><?php if (in_array($store['store_id'], $selected)) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $store['store_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $store['store_id']; ?>" />
                <?php } ?></td>
              <td class="text-left"><?php echo $store['name']; ?></td>
              <td class="text-left"><?php echo $store['url']; ?></td>
              <td class="text-right"><a href="<?php echo $store['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
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
    </form>
  </div>
</div>
<?php echo $footer; ?> 