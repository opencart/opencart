<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $refresh; ?>" data-toggle="tooltip" title="<?php echo $button_refresh; ?>" class="btn btn-info"><i class="fa fa-refresh"></i></a> <a href="<?php echo $clear; ?>" data-toggle="tooltip" title="<?php echo $button_clear; ?>" class="btn btn-warning"><i class="fa fa-eraser"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-modification').submit() : false;"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
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
    <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_refresh; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
          <li><a href="#tab-log" data-toggle="tab"><?php echo $tab_log; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-general">
            <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-modification">
              <div class="table-responsive">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                      <td class="text-left"><?php if ($sort == 'name') { ?>
                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                        <?php } ?></td>
                      <td class="text-left"><?php if ($sort == 'author') { ?>
                        <a href="<?php echo $sort_author; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_author; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_author; ?>"><?php echo $column_author; ?></a>
                        <?php } ?></td>
                      <td class="text-left"><?php if ($sort == 'version') { ?>
                        <a href="<?php echo $sort_version; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_version; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_version; ?>"><?php echo $column_version; ?></a>
                        <?php } ?></td>
                      <td class="text-left"><?php if ($sort == 'status') { ?>
                        <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                        <?php } ?></td>
                      <td class="text-left"><?php if ($sort == 'date_added') { ?>
                        <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                        <?php } else { ?>
                        <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                        <?php } ?></td>
                      <td class="text-right"><?php echo $column_action; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php if ($modifications) { ?>
                    <?php foreach ($modifications as $modification) { ?>
                    <tr>
                      <td class="text-center"><?php if (in_array($modification['modification_id'], $selected)) { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $modification['modification_id']; ?>" checked="checked" />
                        <?php } else { ?>
                        <input type="checkbox" name="selected[]" value="<?php echo $modification['modification_id']; ?>" />
                        <?php } ?></td>
                      <td class="text-left"><?php echo $modification['name']; ?></td>
                      <td class="text-left"><?php echo $modification['author']; ?></td>
                      <td class="text-left"><?php echo $modification['version']; ?></td>
                      <td class="text-left"><?php echo $modification['status']; ?></td>
                      <td class="text-left"><?php echo $modification['date_added']; ?></td>
                      <td class="text-right"><?php if ($modification['link']) { ?>
                        <a href="<?php echo $modification['link']; ?>" data-toggle="tooltip" title="<?php echo $button_link; ?>" class="btn btn-info" target="_blank"><i class="fa fa-link"></i></a>
                        <?php } else { ?>
                        <button type="button" class="btn btn-info" disabled="disabled"><i class="fa fa-link"></i></button>
                        <?php } ?>
                        <?php if (!$modification['enabled']) { ?>
                        <a href="<?php echo $modification['enable']; ?>" data-toggle="tooltip" title="<?php echo $button_enable; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                        <?php } else { ?>
                        <a href="<?php echo $modification['disable']; ?>" data-toggle="tooltip" title="<?php echo $button_disable; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                        <?php } ?></td>
                    </tr>
                    <?php } ?>
                    <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="7"><?php echo $text_no_results; ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </form>
            <div class="row">
              <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
              <div class="col-sm-6 text-right"><?php echo $results; ?></div>
            </div>
          </div>
          <div class="tab-pane" id="tab-log">
            <p>
              <textarea wrap="off" rows="15" class="form-control"><?php echo $log; ?></textarea>
            </p>
            <div class="text-right"><a href="<?php echo $clear_log; ?>" class="btn btn-danger"><i class="fa fa-eraser"></i> <?php echo $button_clear; ?></a></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>