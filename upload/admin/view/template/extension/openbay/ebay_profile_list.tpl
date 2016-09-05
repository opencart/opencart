<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
      <div class="alert alert-danger"><i class="mi mi-exclamation-circle">error</i> <?php echo $error_warning; ?></div>
    <?php } ?>
    <?php if ($success) { ?>
      <div class="alert alert-success"><i class="mi mi-check-circle">check_circle</i> <?php echo $success; ?></div>
    <?php } ?>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title"><i class="mi mi-list">view_list</i> <?php echo $text_list; ?></h3>
    </div>
    <div class="panel-body">
      <div class="well">
        <div class="row">
          <div class="col-sm-12">
            <form action="<?php echo $add; ?>" method="post" id="add-profile-form" class="form-inline pull-right" role="form">
              <input type="hidden" name="step1" value="1" />
              <div class="form-group">
                <div class="input-group">
                  <select name="type"class="form-control">
                    <?php foreach($types as $key => $val){ ?>
                      <option value="<?php echo $key; ?>"><?php echo $val['name']; ?></option>
                    <?php } ?>
                  </select>
                  <a data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary input-group-addon" onclick="$('#add-profile-form').submit();"><i class="mi mi-plus-circle">add_circle</i> <?php echo $button_add; ?></a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $text_profile_name; ?></td>
            <td class="text-left"><?php echo $text_profile_type; ?></td>
            <td class="text-left"><?php echo $text_profile_desc; ?></td>
            <td class="text-left"></td>
          </tr>
        </thead>
        <tbody>
        <?php if ($profiles) { ?><?php foreach ($profiles as $profile) { ?>
        <tr>
          <td class="text-left"><?php if ($profile['default'] == 1){ echo '<strong>['.$text_profile_default.'] </strong>'; } echo $profile['name'];?></td>
          <td class="text-left"><?php echo $types[$profile['type']]['name']; ?></td>
          <td class="text-left"><?php echo $profile['description']; ?></td>
          <td class="text-right">
            <a href="<?php echo $profile['link_edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="mi mi-pencil">mode_edit</i></a>&nbsp;
            <a href="<?php echo $profile['link_delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger profile-delete"><i class="mi mi-minus-circle">remove circle</i></a>
          </td>
        </tr>
        <?php } ?><?php } else { ?>
        <tr>
          <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
        </tr>
        <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
  </div>
</div>
<script type="text/javascript"><!--
$(document).ready(function () {
  $('a.profileDelete').click(function (event) {
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