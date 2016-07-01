<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $refresh; ?>" data-toggle="tooltip" title="<?php echo $button_refresh; ?>" class="btn btn-info"><i class="fa fa-refresh"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-layout">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                  <tr>
                    <td class="text-left"><?php echo $column_flag; ?></td>
                    <td class="text-left"><?php echo $column_country; ?></td>
                    <td class="text-left"><?php echo $column_progress; ?></td>
                    <td class="text-right"><?php echo $column_action; ?></td>
                  </tr>
              </thead>
              <tbody>
                <?php if ($translations) { ?>
                <?php foreach ($translations as $list) { ?>
                <tr>
                  <td class="text-left"><img src="<?php echo $list['image']; ?>" height="48" width="48"></img></td>
                  <td class="text-left"><?php echo $list['name']; ?></td>
                  <td class="text-left">
                    <div class="progress">
                        <?php if ($list['progress'] > 75) { ?>
                        <div class="progress-bar progress-bar-success progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:<?php echo $list['progress']; ?>%"><?php echo $list['progress']; ?>% Complete (success)</div>
                        <?php }else if ($list['progress'] >25 && $list['progress'] < 75) { ?>
                        <div class="progress-bar progress-bar-info progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:<?php echo $list['progress']; ?>%"><?php echo $list['progress']; ?>% Complete (success)</div>
                        <?php }else if ($list['progress'] < 25) { ?>
                        <div class="progress-bar progress-bar-danger progress-bar-striped" role="progressbar" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width:<?php echo $list['progress']; ?>%"><?php echo $list['progress']; ?>% Complete (success)</div>
                        <?php } ?>
                    </div></td>
                  <td class="text-right">
                    <?php if (!$list['installed']) { ?>
                    <a href="<?php echo $list['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-magic"></i></a>
                    <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-minus-circle"></i></button>
                    <?php } else { ?>
                    <button type="button" class="btn btn-primary" disabled="disabled"><i class="fa fa-magic"></i></button>
                    <a href="<?php echo $list['uninstall']; ?>" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                    <?php } ?>
                    </td>
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
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
        </div>
      </div>
    </div>
</div>

 <!-- <script type="text/javascript"><!--
function install(code) {

          $.ajax({
              url: 'index.php?route=design/translation/install&token=<?php echo $token; ?>',
              method: 'post',
              dataType: 'json',
              data: {code: code},
              success: function(json) {
              },
              error: function(xhr, ajaxOptions, thrownError) {
                  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
              }
          });
};
</script> -->

<?php echo $footer; ?>
