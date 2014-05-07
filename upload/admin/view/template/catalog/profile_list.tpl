<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
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
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right"><a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn"><i class="fa fa-plus-circle"></i></a>
        <button type="submit" form="form-profile-list" formaction="<?php echo $copy; ?>" data-toggle="tooltip" title="<?php echo $button_copy; ?>" class="btn"><i class="fa fa-copy"></i></button>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-profile-list').submit() : false;"><i class="fa fa-times-circle"></i></button>
      </div>
      <h1 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-profile-list">
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <td class="text-center" width="1"><input type="checkbox" onclick="$('input[name*=\'profile_ids\']').attr('checked', this.checked)"/></td>
                <td class="text-left"><?php echo $column_name ?></td>
                <td class="text-left"><?php echo $column_sort_order ?></td>
                <td class="text-right"><?php echo $column_action ?></td>
              </tr>
            </thead>
            <tbody>
              <?php if ($profiles) { ?>
              <?php foreach ($profiles as $profile) { ?>
              <tr>
                <td class="text-center"><input type="checkbox" name="profile_ids[]" value="<?php echo $profile['profile_id'] ?>"/></td>
                <td class="text-left"><?php echo $profile['name'] ?></td>
                <td class="text-left"><?php echo $profile['sort_order'] ?></td>
                <td class="text-right"><?php foreach ($profile['action'] as $action): ?>
                  [<a href="<?php echo $action['href'] ?>"><?php echo $action['name'] ?></a>]
                  <?php endforeach;?></td>
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
      <div class="row">
        <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
        <div class="col-sm-6 text-right"><?php echo $results; ?></div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>