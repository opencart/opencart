<?php echo $header; ?><?php echo $column_left; ?>
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
      <div class="pull-right">
        <a href="<?php echo $insert; ?>" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-default"><i class="fa fa-plus-circle"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-code fa-lg"></i> <?php echo $heading_title; ?></h1>
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