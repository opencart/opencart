<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
      <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $column_name; ?></td>
            <td class="text-center"><?php echo $column_action; ?></td>
          </tr>
        </thead>
        <tbody>
        <?php if ($templates) { ?>
          <?php foreach ($templates as $template) { ?>
            <tr>
              <td class="text-left"><?php echo $template['name']; ?></td>
              <td class="text-right">
                <a href="<?php echo $template['link_edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>&nbsp;
                <a href="<?php echo $template['link_delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger profile-delete"><i class="fa fa-minus-circle"></i></a>
              </td>
            </tr>
          <?php } ?>
        <?php } else { ?>
          <tr>
            <td class="text-center" colspan="2"><?php echo $text_no_results; ?></td>
          </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
  $(document).ready(function() {
    $('a.profile-delete').click(function(event) {
      event.preventDefault();
      var url = $(this).attr('href');
      var confirm_box = confirm('<?php echo $text_confirm_delete; ?>');
      if (confirm_box) {
        window.location = url;
      }
    });
  });
//--></script>
<?php echo $footer; ?>