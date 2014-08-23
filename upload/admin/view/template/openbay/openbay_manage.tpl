<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-openbay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn" onclick="validateForm(); return false;"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-openbay" class="form-horizontal">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-update" data-toggle="tab"><?php echo $tab_update; ?></a></li>
          <li><a href="#tab-setting" data-toggle="tab"><?php echo $tab_setting; ?></a></li>
          <li><a href="#tab-patch" data-toggle="tab"><?php echo $tab_patch; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-update">
            <div class="alert alert-info text-left">
              <?php echo $text_version_installed; ?><span id="text-version"><?php echo $text_version; ?></span>
            </div>

            <input type="hidden" name="openbay_version" value="<?php echo $openbay_version; ?>" />
            <input type="hidden" name="openbay_menu" value="<?php echo $openbay_menu; ?>" />

            <p><?php echo $text_update_description; ?></p>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="ftp-username"><?php echo $entry_ftp_username; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_ftp_username" value="<?php echo $openbay_ftp_username; ?>" placeholder="<?php echo $entry_ftp_username; ?>" id="ftp-username" class="form-control ftp-setting" />
                <span class="help-block"><?php echo $help_ftp_username; ?></span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ftp-password"><?php echo $entry_ftp_password; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_ftp_pw" value="<?php echo $openbay_ftp_pw;?>" placeholder="<?php echo $entry_ftp_password; ?>" id="ftp-password" class="form-control ftp-setting" />
                <span class="help-block"><?php echo $help_ftp_password; ?></span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ftp-server"><?php echo $entry_ftp_server; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_ftp_server" value="<?php echo $openbay_ftp_server;?>" placeholder="<?php echo $entry_ftp_server; ?>" id="ftp-server" class="form-control ftp-setting" />
                <span class="help-block"><?php echo $help_ftp_server; ?></span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ftp-root"><?php echo $entry_ftp_root; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_ftp_rootpath" value="<?php echo $openbay_ftp_rootpath;?>" placeholder="<?php echo $entry_ftp_root; ?>" id="ftp-root" class="form-control ftp-setting" />
                <span class="help-block"><?php echo $help_ftp_root; ?></span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="admin-directory"><?php echo $entry_ftp_admin; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_admin_directory" value="<?php echo $openbay_admin_directory;?>" placeholder="<?php echo $entry_ftp_admin; ?>" id="admin-directory" class="form-control ftp-setting" />
                <span class="help-block"><?php echo $help_ftp_admin; ?></span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ftp-pasv"><?php echo $entry_ftp_pasv; ?></label>
              <div class="col-sm-10">
                <select name="openbay_ftp_pasv" id="ftp-pasv" class="form-control ftp-setting">
                  <?php if ($openbay_ftp_pasv) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select>
                <span class="help-block"><?php echo $help_ftp_pasv; ?></span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="ftp-beta"><?php echo $entry_ftp_beta; ?></label>
              <div class="col-sm-10">
                <select name="openbay_ftp_beta" id="ftp-beta" class="form-control ftp-setting">
                  <?php if ($openbay_ftp_beta) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select>
                <span class="help-block"><?php echo $help_ftp_beta; ?></span>
              </div>
            </div>
            <div class="form-group" id="ftp-test-row">
              <label class="col-sm-2 control-label" for="button-clear-faq"><?php echo $text_test_connection; ?></label>
              <div class="col-sm-10">
                <button class="btn btn-primary" id="ftp-test"><?php echo $button_ftp_test; ?></button>
                <div class="btn btn-primary" style="display:none;" id="ftp-test-loading"><i class="fa fa-cog fa-lg fa-spin"></i></div>
              </div>
            </div>
            <div class="form-group" style="display:none;" id="ftp-update-row">
              <label class="col-sm-2 control-label" for="ftp-update-module"><?php echo $text_run_update; ?></label>
              <div class="col-sm-10">
                <button class="btn btn-primary" id="ftp-update-module"><?php echo $button_update; ?></button>
                <div class="btn btn-primary" style="display:none;" id="ftp-update-module-loading"><i class="fa fa-cog fa-lg fa-spin"></i></div>
              </div>
            </div>
            <div id="updateBox"></div>
          </div>
          <div class="tab-pane" id="tab-setting">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-language"><?php echo $text_language; ?></label>
              <div class="col-sm-10">
                <select name="openbay_language" id="input-language" class="form-control">
                  <?php foreach ($languages as $key => $language) { ?>
                    <option value="<?php echo $key; ?>" <?php if ($key == $openbay_language) { echo'selected="selected"'; } ?>><?php echo $language; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="button-clear-faq"><?php echo $text_clear_faq; ?></label>
              <div class="col-sm-10">
                <button class="btn btn-primary" id="button-clear-faq"><?php echo $button_faq_clear; ?></button>
                <span class="help-block"><?php echo $help_clear_faq; ?></span>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-patch">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="button-patch"><?php echo $text_patch; ?></label>
              <div class="col-sm-10">
                <button class="btn btn-primary" id="button-patch"><?php echo $button_patch; ?></button>
                <span class="help-block"><?php echo $help_patch; ?></span>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
  $('.ftp-setting').keypress(function() {
    $('#ftp-update-module').hide();
    $('#ftp-test-row').show();
    $('#ftp-update-row').hide();
  });

  $('#ftp-test').bind('click', function() {
    e.preventDefault();

    $.ajax({
      url: 'index.php?route=extension/openbay/ftptestconnection&token=<?php echo $token; ?>',
      type: 'post',
      data: $('.ftp-setting').serialize(),
      dataType: 'json',
      beforeSend: function() {
        $('#ftp-test').hide();
        $('#ftp-test-loading').show();
      },
      success: function(json) {
        alert(json.msg);

        if (json.connection == true) {
          $('#ftp-test-row').hide();
          $('#ftp-update-module').show();
          $('#ftp-update-row').show();
        }

        $('#ftp-test').show();
        $('#ftp-test-loading').hide();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  $('#button-patch').bind('click', function() {
    e.preventDefault();

    $.ajax({
      url: 'index.php?route=extension/openbay/runpatch&token=<?php echo $token; ?>',
      type: 'post',
      dataType: 'json',
      beforeSend: function() {
        $('#button-patch').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>');
        $("#button-patch").attr('disabled', 'disabled');
      },
      success: function() {
        $('#button-patch').empty().removeClass('btn-primary').addClass('btn-success').html('<?php echo $text_complete; ?>');
        alert('<?php echo $text_patch_complete; ?>');
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#button-patch').empty().html('<?php echo $button_patch; ?>');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  $('#button-clear-faq').bind('click', function() {
    e.preventDefault();

    $.ajax({
      url: 'index.php?route=extension/openbay/faqclear&token=<?php echo $token; ?>',
      beforeSend: function() {
        $('#button-clear-faq').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>');
        $("#button-clear-faq").attr('disabled','disabled');
      },
      type: 'post',
      dataType: 'json',
      success: function(json) {
        $('#button-clear-faq').empty().removeClass('btn-primary').addClass('btn-success').html('<?php echo $text_complete; ?>');
        alert('<?php echo $text_clear_faq_complete; ?>');
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#button-clear-faq').empty().html('<?php echo $text_clear; ?>');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  $('#ftp-update-module').bind('click', function() {
    e.preventDefault();

    $.ajax({
      url: 'index.php?route=extension/openbay/ftpupdatemodule&token=<?php echo $token; ?>',
      type: 'post',
      data: $('.ftp-setting').serialize(),
      dataType: 'json',
      beforeSend: function() {
        $('#ftp-update-module').hide();
        $('#ftp-update-module-loading').show();
      },
      success: function(json) {
        alert(json.msg);
        $('#text-version').text(json.version);
        $('input[name=\'openbay_version\']').val(json.version);
        $('#ftp-update-module').show();
        $('#ftp-update-module-loading').hide();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  function validateForm() {
    $('#form-openbay').submit();
  }
//--></script>
<?php echo $footer; ?>