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
        <button type="submit" form="form-openbay-manager" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn" onclick="validateForm(); return false;"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a>
      </div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $text_manager; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-openbay-manager" class="form-horizontal">
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-updates" data-toggle="tab"><?php echo $text_btn_update; ?></a></li>
          <li><a href="#tab-settings" data-toggle="tab"><?php echo $text_btn_settings; ?></a></li>
          <li><a href="#tab-patch" data-toggle="tab"><?php echo $text_btn_patch; ?></a></li>
        </ul>
        <div class="tab-content">
          <div class="tab-pane active" id="tab-updates">
            <div class="alert alert-info text-left">
              <?php echo $text_installed_version; ?><span id="version-text"><?php echo $txt_obp_version; ?></span>
            </div>

            <input type="hidden" name="openbay_version" value="<?php echo $openbay_version;?>" id="openbay_version" />
            <input type="hidden" name="openbaymanager_show_menu" value="<?php echo $openbaymanager_show_menu;?>" />

            <p><?php echo $text_update_description; ?></p>

            <div class="form-group">
              <label class="col-sm-2 control-label" for="openbay_ftp_username"><?php echo $field_ftp_user; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_ftp_username" value="<?php echo $openbay_ftp_username;?>" placeholder="<?php echo $field_ftp_user; ?>" id="openbay_ftp_username" class="form-control ftpsetting" />
                <span class="help-block"><?php echo $text_help_ftp_user; ?></span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="openbay_ftp_pw"><?php echo $field_ftp_pw; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_ftp_pw" value="<?php echo $openbay_ftp_pw;?>" placeholder="<?php echo $field_ftp_user; ?>" id="openbay_ftp_pw" class="form-control ftpsetting" />
                <span class="help-block"><?php echo $text_help_ftp_pw; ?></span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="openbay_ftp_server"><?php echo $field_ftp_server_address; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_ftp_server" value="<?php echo $openbay_ftp_server;?>" placeholder="<?php echo $field_ftp_server_address; ?>" id="openbay_ftp_server" class="form-control ftpsetting" />
                <span class="help-block"><?php echo $text_help_ftp_server_address; ?></span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="openbay_ftp_rootpath"><?php echo $field_ftp_root_path; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_ftp_rootpath" value="<?php echo $openbay_ftp_rootpath;?>" placeholder="<?php echo $field_ftp_root_path; ?>" id="openbay_ftp_rootpath" class="form-control ftpsetting" />
                <span class="help-block"><?php echo $text_help_ftp_root_path; ?></span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="openbay_admin_directory"><?php echo $field_ftp_admin; ?></label>
              <div class="col-sm-10">
                <input type="text" name="openbay_admin_directory" value="<?php echo $openbay_admin_directory;?>" placeholder="<?php echo $field_ftp_admin; ?>" id="openbay_admin_directory" class="form-control ftpsetting" />
                <span class="help-block"><?php echo $text_help_ftp_admin; ?></span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="openbay_ftp_pasv"><?php echo $field_ftp_pasv; ?></label>
              <div class="col-sm-10">
                <select name="openbay_ftp_pasv" id="openbay_ftp_pasv" class="form-control ftpsetting">
                  <?php if ($openbay_ftp_pasv) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="openbay_ftp_beta"><?php echo $field_ftp_beta; ?></label>
              <div class="col-sm-10">
                <select name="openbay_ftp_beta" id="openbay_ftp_beta" class="form-control ftpsetting">
                  <?php if ($openbay_ftp_beta) { ?>
                  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
                  <option value="0"><?php echo $text_no; ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $text_yes; ?></option>
                  <option value="0" selected="selected"><?php echo $text_no; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group" id="ftp-test-row">
              <label class="col-sm-2 control-label" for="button-clear-faq"><?php echo $text_test_connection; ?></label>
              <div class="col-sm-10">
                <a class="btn btn-primary" id="ftp-test" onclick="ftpTest();"><?php echo $text_btn_test; ?></a>
                <div class="btn btn-primary" style="display:none;" id="ftp-test-loading"><i class="fa fa-cog fa-lg fa-spin"></i></div>
              </div>
            </div>
            <div class="form-group" style="display:none;" id="ftp-update-row">
              <label class="col-sm-2 control-label" for="ftp-update-module"><?php echo $text_run_update; ?></label>
              <div class="col-sm-10">
                <a class="btn btn-primary" id="ftp-update-module" onclick="updateModule();"><?php echo $text_btn_update; ?></a>
                <div class="btn btn-primary" style="display:none;" id="ftp-update-module-loading"><i class="fa fa-cog fa-lg fa-spin"></i></div>
              </div>
            </div>
            <div id="updateBox"></div>
          </div>
          <div class="tab-pane" id="tab-settings">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="input-language"><?php echo $text_language; ?></label>
              <div class="col-sm-10">
                <select name="openbay_language" id="input-language" class="form-control">
                  <?php foreach($languages as $key => $language){ ?>
                    <option value="<?php echo $key; ?>" <?php if ($key == $openbay_language){ echo'selected="selected"'; } ?>><?php echo $language; ?></option>
                  <?php } ?>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="col-sm-2 control-label" for="button-clear-faq"><?php echo $text_clear_faq; ?></label>
              <div class="col-sm-10">
                <a class="btn btn-primary" id="button-clear-faq" onclick="clearFaq();"><?php echo $text_clear; ?></a>
                <span class="help-block"><?php echo $text_clear_faq_description; ?></span>
              </div>
            </div>
          </div>
          <div class="tab-pane" id="tab-patch">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="button-patch"><?php echo $text_patch; ?></label>
              <div class="col-sm-10">
                <a class="btn btn-primary" id="button-patch" onclick="runPatch();"><?php echo $text_patch_button; ?></a>
                <span class="help-block"><?php echo $text_patch_description; ?></span>
              </div>
            </div>
          </div>
        </div>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
  var token = "<?php echo $_GET['token']; ?>";

  $('.ftpsetting').keypress(function(){
        $('#ftp-update-module').hide();
        $('#ftp-test-row').show();
        $('#ftp-update-row').hide();
    });

  $('#ftp-test').bind('click', function() {
    $.ajax({
        url: 'index.php?route=extension/openbay/ftpTestConnection&token='+token,
        type: 'post',
        data: $('.ftpsetting').serialize(),
        dataType: 'json',
        beforeSend: function(){
            $('#ftp-test').hide();
            $('#ftp-test-loading').show();
        },
        success: function(json) {
            alert(json.msg);

            if (json.connection == true){
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
    $.ajax({
      url: 'index.php?route=extension/openbay/runPatch&token='+token,
      type: 'post',
      dataType: 'json',
      beforeSend: function(){
        $('#button-patch').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>');
        $("#button-patch").attr('disabled','disabled');
      },
      success: function() {
        $('#button-patch').empty().removeClass('btn-primary').addClass('btn-success').html('<?php echo $text_complete; ?>');
        alert('<?php echo $text_patch_complete; ?>');
      },
      error: function (xhr, ajaxOptions, thrownError) {
        $('#button-patch').empty().html('<?php echo $text_patch_button; ?>');
        $("#sync-cats").removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  $('#button-clear-faq').bind('click', function() {
    $.ajax({
      url: 'index.php?route=extension/openbay/faqClear&token='+token,
      beforeSend: function(){
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
        $("#sync-cats").removeAttr('disabled');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  $('#ftp-update-module').bind('click', function() {
    $.ajax({
      url: 'index.php?route=extension/openbay/ftpUpdateModule&token='+token,
      type: 'post',
      data: $('.ftpsetting').serialize(),
      dataType: 'json',
      beforeSend: function(){
        $('#ftp-update-module').hide();
        $('#ftp-update-module-loading').show();
      },
      success: function(json) {
        alert(json.msg);
        $('#version-text').text(json.version);
        $('#openbay_version').val(json.version);
        $('#ftp-update-module').show();
        $('#ftp-update-module-loading').hide();
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  function validateForm(){
    $('#form-openbay-manager').submit();
  }
//--></script>
<?php echo $footer; ?>