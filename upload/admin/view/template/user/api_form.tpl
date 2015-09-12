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
            <li><a href="#tab-session" data-toggle="tab"><?php echo $tab_session; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-general">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                <div class="col-sm-10">
                  <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                  <?php if ($error_name) { ?>
                  <div class="text-danger"><?php echo $error_name; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-key"><?php echo $entry_key; ?></label>
                <div class="col-sm-10">
                  <textarea name="key" placeholder="<?php echo $entry_key; ?>" rows="5" id="input-key" class="form-control"><?php echo $key; ?></textarea>
                  <br />
                  <button type="button" id="button-generate" class="btn btn-primary"><i class="fa fa-refresh"></i> <?php echo $button_generate; ?></button>
                  <?php if ($error_key) { ?>
                  <div class="text-danger"><?php echo $error_key; ?></div>
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
              <div class="alert alert-info"><i class="fa fa-question-circle"></i> <?php echo $text_ip; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
              </div>
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
            <div class="tab-pane" id="tab-session">
              <table id="session" class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <td class="text-left"><?php echo $column_token; ?></td>
                    <td class="text-left"><?php echo $column_ip; ?></td>
                    <td class="text-left"><?php echo $column_date_added; ?></td>
                    <td class="text-left"><?php echo $column_date_modified; ?></td>
                    <td class="text-right"><?php echo $column_action; ?></td>
                  </tr>
                </thead>
                <tbody>
                  <?php if ($api_sessions) { ?>
                  <?php foreach ($api_sessions as $api_session) { ?>
                  <tr>
                    <td class="text-left"><?php echo $api_session['token']; ?></td>
                    <td class="text-left"><?php echo $api_session['ip']; ?></td>
                    <td class="text-left"><?php echo $api_session['date_added']; ?></td>
                    <td class="text-left"><?php echo $api_session['date_modified']; ?></td>
                    <td class="text-right"><button type="button" value="<?php echo $api_session['api_session_id']; ?>" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
                  </tr>
                  <?php } ?>
                  <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
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

	$('#input-key').val(rand);
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
  <script type="text/javascript"><!--
$('#session button').on('click', function(e) {
	e.preventDefault();

	if (confirm('<?php echo $text_confirm; ?>')) {
		var node = this;

		$.ajax({
			url: 'index.php?route=user/api/deletesession&token=<?php echo $token; ?>&api_session_id=' + $(node).val(),
			type: 'post',
			dataType: 'json',
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},
			success: function(json) {
				$('.alert').remove();

				if (json['error']) {
					$('#tab-session').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
				}

				if (json['success']) {
					$('#tab-session').prepend('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

					$(node).parent().parent().remove();
				}
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
//--></script>
</div>
<?php echo $footer; ?>
