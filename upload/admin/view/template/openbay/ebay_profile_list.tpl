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
        <form action="<?php echo $insert; ?>" method="post" id="add-profile-form">
          <input type="hidden" name="step1" value="1"/>
          <select name="type">
            <?php foreach($types as $key => $val){ ?>
              <option value="<?php echo $key; ?>"><?php echo $val['name']; ?></option>
            <?php } ?>
          </select>
          <a data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-default" onclick="$('#add-profile-form').submit();"><i class="fa fa-plus-circle"></i></a>
        </form>
      </div>
      <h1 class="panel-title"><i class="fa fa-file-text fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
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
            <a href="<?php echo $profile['link_edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>&nbsp;
            <a href="<?php echo $profile['link_delete']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger profile-delete"><i class="fa fa-minus-circle"></i></a>
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