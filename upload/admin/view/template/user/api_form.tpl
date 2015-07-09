<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-api" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-api" class="form-horizontal">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-ip" data-toggle="tab"><?php echo $tab_ip; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="username" value="<?php echo $username; ?>" placeholder="<?php echo $entry_username; ?>" id="input-username" class="form-control" />
                  <?php if ($error_username) { ?>
                  <div class="text-danger"><?php echo $error_username; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
                <div class="col-sm-10">
                  <textarea name="password" placeholder="<?php echo $entry_password; ?>" rows="5" id="input-password" class="form-control"><?php echo $password; ?></textarea>
                  <br />
                  <button type="button" id="button-generate" class="btn btn-primary"><i class="fa fa-refresh"></i> <?php echo $button_generate; ?></button>
                  <?php if ($error_password) { ?>
                  <div class="text-danger"><?php echo $error_password; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group">
                <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
                <div class="col-sm-10">
                  <select name="status" id="input-status" class="form-control">
                    <?php if ($status) { ?>
                    <option value="0"><?php echo $text_disabled; ?></option>
                    <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                    <?php } else { ?>
                    <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                    <option value="1"><?php echo $text_enabled; ?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-ip">
              <div class="table-responsive">
                <table id="ip" class="table table-striped table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left"><?php echo $entry_ip; ?></td>
                      <td class="text-left"></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php $ip_row = 0; ?>
                    <?php foreach ($api_ips as $api_ip) { ?>
                    <tr id="ip-row<?php echo $ip_row; ?>">
                      <td class="text-left"><input type="text" name="api_ip[]" value="<?php echo $api_ip['ip']; ?>" placeholder="<?php echo $entry_ip; ?>" class="form-control" /></td>
                      <td class="text-left"><button type="button" onclick="$('#ip-row<?php echo $ip_row; ?>').remove()" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                    </tr>
                    <?php $ip_row++; ?>
                    <?php } ?>
                  </tbody>
                  <tfoot>
                    <tr>
                      <td></td>
                      <td class="text-left"><button type="button" onclick="addIp()" data-toggle="tooltip" title="<?php echo $button_ip_add; ?>" class="btn btn-primary"><i class="fa fa-plus-circle"></i></button></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-generate').on('click', function() {
	rand = '';
	
	string = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';

	for (i = 0; i < 256; i++) {
		rand += string[Math.floor(Math.random() * (string.length - 1))];
	}
	
	$('#input-password').val(rand);
});
//--></script> 
  <script type="text/javascript"><!--
var ip_row = <?php echo $ip_row; ?>;

function addIp() {
	html  = '<tr id="ip-row' + ip_row + '">'; 	
    html += '  <td class="text-right"><input type="text" name="api_ip[]" value="" placeholder="<?php echo $entry_ip; ?>" class="form-control" /></td>';
	html += '  <td class="text-left"><button type="button" onclick="$(\'#ip-row' + ip_row + '\').remove();" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>';
	html += '</tr>';
	
	$('#ip tbody').append(html);

	ip_row++;
}
//--></script> 
</div>
<?php echo $footer; ?> 