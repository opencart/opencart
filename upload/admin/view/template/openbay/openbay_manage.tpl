<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-openbay" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary" onclick="validateForm(); return false;"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-openbay" class="form-horizontal">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab-update" data-toggle="tab"><?php echo $tab_update; ?></a></li>
        <li><a href="#tab-setting" data-toggle="tab"><?php echo $tab_setting; ?></a></li>
        <li><a href="#tab-developer" data-toggle="tab"><?php echo $tab_developer; ?></a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab-update">
          <div class="alert alert-info text-left">
            <?php echo $text_version_installed; ?><span id="text-version"><?php echo $text_version; ?></span>
          </div>

          <input type="hidden" name="openbay_version" value="<?php echo $openbay_version; ?>" />
          <input type="hidden" name="openbay_menu" value="<?php echo $openbay_menu; ?>" />

          <ul id="update-tabs" class="nav nav-tabs">
            <li class="active"><a href="#tab-update-v2" data-toggle="tab"><?php echo $tab_update_v1; ?></a></li>
            <li><a href="#tab-update-v1" data-toggle="tab"><?php echo $tab_update_v2; ?></a></li>
            <li><a href="#tab-update-patch" data-toggle="tab"><?php echo $tab_patch; ?></a></li>
          </ul>
          <div class="tab-content">
            <div class="tab-pane active" id="tab-update-v2">
              <p><?php echo $text_update_description; ?></p>
              <div class="well">
                <div class="alert alert-danger" id="update-error" style="display:none;"></div>
                <div id="update-v2-box">
                  <div class="form-group">
                    <label class="col-sm-3 control-label" for="update-v2-beta"><span data-toggle="tooltip" title="<?php echo $help_ftp_beta; ?>"><?php echo $entry_ftp_beta; ?></span></label>
                    <div class="col-sm-8">
                      <select id="update-v2-beta" class="form-control">
                        <option value="1"><?php echo $text_yes; ?></option>
                        <option value="0" selected="selected"><?php echo $text_no; ?></option>
                      </select>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-3 control-label" for="update-v2"><span data-toggle="tooltip" title="<?php echo $help_easy_update; ?>"><?php echo $entry_update; ?></span></label>
                    <div class="col-sm-8">
                      <button class="btn btn-primary" id="update-v2"><?php echo $button_update; ?></button>
                    </div>
                  </div>
                </div>
                <div id="update-v2-progress" style="display:none;">
                  <div class="progress" style="height:50px;">
                    <div class="progress-bar progress-bar-striped active progress-bar-info" role="progressbar" aria-valuenow="1" aria-valuemin="0" aria-valuemax="100" style="width: 0%;" id="loading-bar"></div>
                  </div>
                  <h4 class="text-center" id="update-text"></h4>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-update-v1">
              <p><?php echo $text_update_description; ?></p>
              <div class="well">
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="ftp-username"><span data-toggle="tooltip" title="<?php echo $help_ftp_username; ?>"><?php echo $entry_ftp_username; ?></span></label>
                  <div class="col-sm-8">
                    <input type="text" name="openbay_ftp_username" value="<?php echo $openbay_ftp_username; ?>" placeholder="<?php echo $entry_ftp_username; ?>" id="ftp-username" class="form-control ftp-setting" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="ftp-password"><span data-toggle="tooltip" title="<?php echo $help_ftp_password; ?>"><?php echo $entry_ftp_password; ?></span></label>
                  <div class="col-sm-8">
                    <input type="text" name="openbay_ftp_pw" value="<?php echo $openbay_ftp_pw;?>" placeholder="<?php echo $entry_ftp_password; ?>" id="ftp-password" class="form-control ftp-setting" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="ftp-server"><span data-toggle="tooltip" title="<?php echo $help_ftp_server; ?>"><?php echo $entry_ftp_server; ?></span></label>
                  <div class="col-sm-8">
                    <input type="text" name="openbay_ftp_server" value="<?php echo $openbay_ftp_server;?>" placeholder="<?php echo $entry_ftp_server; ?>" id="ftp-server" class="form-control ftp-setting" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="ftp-root"><span data-toggle="tooltip" title="<?php echo $help_ftp_root; ?>"><?php echo $entry_ftp_root; ?></span></label>
                  <div class="col-sm-8">
                    <input type="text" name="openbay_ftp_rootpath" value="<?php echo $openbay_ftp_rootpath;?>" placeholder="<?php echo $entry_ftp_root; ?>" id="ftp-root" class="form-control ftp-setting" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="admin-directory"><span data-toggle="tooltip" title="<?php echo $help_ftp_admin; ?>"><?php echo $entry_ftp_admin; ?></span></label>
                  <div class="col-sm-8">
                    <input type="text" name="openbay_admin_directory" value="<?php echo $openbay_admin_directory;?>" placeholder="<?php echo $entry_ftp_admin; ?>" id="admin-directory" class="form-control ftp-setting" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="ftp-pasv"><span data-toggle="tooltip" title="<?php echo $help_ftp_pasv; ?>"><?php echo $entry_ftp_pasv; ?></span></label>
                  <div class="col-sm-8">
                    <select name="openbay_ftp_pasv" id="ftp-pasv" class="form-control ftp-setting">
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
                  <label class="col-sm-3 control-label" for="ftp-beta"><span data-toggle="tooltip" title="<?php echo $help_ftp_beta; ?>"><?php echo $entry_ftp_beta; ?></span></label>
                  <div class="col-sm-8">
                    <select name="openbay_ftp_beta" id="ftp-beta" class="form-control ftp-setting">
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
                  <label class="col-sm-3 control-label" for="button-clear-faq"><?php echo $text_test_connection; ?></label>
                  <div class="col-sm-8">
                    <button class="btn btn-primary" id="ftp-test"><?php echo $button_ftp_test; ?></button>
                    <div class="btn btn-primary" style="display:none;" id="ftp-test-loading"><i class="fa fa-cog fa-lg fa-spin"></i></div>
                  </div>
                </div>
                <div class="form-group" style="display:none;" id="ftp-update-row">
                  <label class="col-sm-3 control-label" for="ftp-update-module"><?php echo $text_run_update; ?></label>
                  <div class="col-sm-8">
                    <button class="btn btn-primary" id="ftp-update-module"><?php echo $button_update; ?></button>
                    <div class="btn btn-primary" style="display:none;" id="ftp-update-module-loading"><i class="fa fa-cog fa-lg fa-spin"></i></div>
                  </div>
                </div>
              </div>
            </div>
            <div class="tab-pane" id="tab-update-patch">
              <p><?php echo $text_patch_description; ?></p>
              <div class="well">
                <div class="form-group">
                  <label class="col-sm-3 control-label" for="button-patch"><span data-toggle="tooltip" title="<?php echo $help_patch; ?>"><?php echo $entry_patch; ?></span></label>
                  <div class="col-sm-8">
                    <button class="btn btn-primary" id="button-patch"><?php echo $button_patch; ?></button>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-setting">
          <div class="well">
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
              <label class="col-sm-2 control-label" for="button-clear-faq"><span data-toggle="tooltip" title="<?php echo $help_clear_faq; ?>"><?php echo $text_clear_faq; ?></span></label>
              <div class="col-sm-10">
                <button class="btn btn-primary" id="button-clear-faq"><?php echo $button_clear; ?></button>
              </div>
            </div>
          </div>
        </div>
        <div class="tab-pane" id="tab-developer">
          <div class="well">
            <div class="form-group">
              <label class="col-sm-2 control-label" for="button-clear-data"><span data-toggle="tooltip" title="<?php echo $help_empty_data; ?>"><?php echo $entry_empty_data; ?></span></label>
              <div class="col-sm-10"> <a class="btn btn-primary" id="button-clear-data"><?php echo $button_clear; ?></a> </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>

<script type="text/javascript"><!--
  $('.ftp-setting').keypress(function() {
    $('#ftp-update-module').hide();
    $('#ftp-test-row').show();
    $('#ftp-update-row').hide();
  });

  $('#ftp-test').bind('click', function(e) {
    e.preventDefault();

    $.ajax({
      url: 'index.php?route=extension/openbay/updatetest&token=<?php echo $token; ?>',
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

  $('#button-patch').bind('click', function(e) {
    e.preventDefault();

    $.ajax({
      url: 'index.php?route=extension/openbay/patch&token=<?php echo $token; ?>',
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

  $('#button-clear-faq').bind('click', function(e) {
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
        $('#button-clear-faq').empty().html('<?php echo $button_clear; ?>');
        if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
      }
    });
  });

  $('#ftp-update-module').bind('click', function(e) {
    e.preventDefault();

    $.ajax({
      url: 'index.php?route=extension/openbay/update&token=<?php echo $token; ?>',
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

  $('#button-clear-data').bind('click', function(e) {
    e.preventDefault();

    var pass = prompt("<?php echo $entry_password_prompt; ?>", "");

    if (pass != '') {
      $.ajax({
        url: 'index.php?route=extension/openbay/purge&token=<?php echo $token; ?>',
        type: 'post',
        dataType: 'json',
        data: 'pass=' + pass,
        beforeSend: function() {
          $('#button-clear-data').empty().html('<i class="fa fa-cog fa-lg fa-spin"></i>');
        },
        success: function(json) {
          setTimeout(function() {
            alert(json.msg);
            $('#button-clear-data').empty().html('<?php echo $button_clear; ?>');
          }, 500);
        },
        error: function (xhr, ajaxOptions, thrownError) {
          if (xhr.status != 0) { alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText); }
        }
      });
    } else {
      alert('<?php echo $text_action_warning; ?>');
      $('#button-clear-data').empty().html('<?php echo $button_clear; ?>');
    }
  });

  $('#update-v2').bind('click', function(e) {
    e.preventDefault();

    var text_confirm = confirm('<?php echo $text_confirm_backup; ?>');

    if (text_confirm == true) {
      $('#update-error').hide();
      $('#update-v2-box').hide();
      $('#update-v2-progress').fadeIn();
      $('#update-text').text('<?php echo $text_check_server; ?>');
      $('#loading-bar').css('width', '5%');

      var beta = $('#update-v2-beta :selected').val();

      updateCheckServer(beta);
    }
  });

  function updateCheckServer(beta) {
    $.ajax({
      url: 'index.php?route=extension/openbay/updatev2&stage=check_server&token=<?php echo $token; ?>&beta=' + beta,
      type: 'post',
      dataType: 'json',
      beforeSend: function() { },
      success: function(json) {
        if (json.error == 1) {
          updateError(json.response);
        } else {
          $('#update-text').text(json.status_message);
          $('#loading-bar').css('width', json.percent_complete + '%');
          updateCheckVersion(beta);
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      }
    });
  }

  function updateCheckVersion(beta) {
    $.ajax({
      url: 'index.php?route=extension/openbay/updatev2&stage=check_version&token=<?php echo $token; ?>&beta=' + beta,
      type: 'post',
      dataType: 'json',
      beforeSend: function() { },
      success: function(json) {
        if (json.error == 1) {
          $('#update-error').removeClass('alert-danger').addClass('alert-info').html('<i class="fa fa-check"></i> ' + json.response).show();
          $('#update-v2-progress').hide();
          $('#update-v2-box').fadeIn();
        } else {
          $('#update-text').text(json.status_message);
          $('#loading-bar').css('width', json.percent_complete + '%');
          updateDownload(beta);
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      }
    });
  }

  function updateDownload(beta) {
    $.ajax({
      url: 'index.php?route=extension/openbay/updatev2&stage=download&token=<?php echo $token; ?>&beta=' + beta,
      type: 'post',
      dataType: 'json',
      beforeSend: function() { },
      success: function(json) {
        if (json.error == 1) {
          updateError(json.response);
        } else {
          $('#update-text').text(json.status_message);
          $('#loading-bar').css('width', json.percent_complete + '%');
          updateExtract(beta);
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      }
    });
  }

  function updateExtract(beta) {
    $.ajax({
      url: 'index.php?route=extension/openbay/updatev2&stage=extract&token=<?php echo $token; ?>&beta=' + beta,
      type: 'post',
      dataType: 'json',
      beforeSend: function() { },
      success: function(json) {
        if (json.error == 1) {
          updateError(json.response);
        } else {
          $('#update-text').text(json.status_message);
          $('#loading-bar').css('width', json.percent_complete + '%');
          updateRemove(beta);
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      }
    });
  }

  function updateRemove(beta) {
    $.ajax({
      url: 'index.php?route=extension/openbay/updatev2&stage=remove&token=<?php echo $token; ?>&beta=' + beta,
      type: 'post',
      dataType: 'json',
      beforeSend: function() { },
      success: function(json) {
        if (json.error == 1) {
          $('#update-v2-progress').prepend('<div class="alert alert-warning">' + json.response + '</div>');
        }

        $('#update-text').text(json.status_message);
        $('#loading-bar').css('width', json.percent_complete + '%');
        updatePatch(beta);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      }
    });
  }

  function updatePatch(beta) {
    $.ajax({
      url: 'index.php?route=extension/openbay/updatev2&stage=run_patch&token=<?php echo $token; ?>&beta=' + beta,
      type: 'post',
      dataType: 'json',
      beforeSend: function() { },
      success: function(json) {
        if (json.error == 1) {
          updateError(json.response);
        } else {
          $('#update-text').text(json.status_message);
          $('#loading-bar').css('width', json.percent_complete + '%');
          updateVersion(beta);
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      }
    });
  }

  function updateVersion(beta) {
    $.ajax({
      url: 'index.php?route=extension/openbay/updatev2&stage=update_version&token=<?php echo $token; ?>&beta=' + beta,
      type: 'post',
      dataType: 'json',
      beforeSend: function() { },
      success: function(json) {
        if (json.error == 1) {
          updateError(json.response);
        } else {
          $('#update-text').text(json.status_message);
          $('#text-version').text(json.response);
          $('#loading-bar').css('width', json.percent_complete + '%').removeClass('progress-bar-info').addClass('progress-bar-success');
        }
      },
      error: function (xhr, ajaxOptions, thrownError) {
        if (xhr.status != 0) {
          alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
      }
    });
  }

  function updateError(errors) {
    $('#update-error').text(errors).show();

    $('#update-v2-progress').hide();
    $('#update-v2-box').fadeIn();
  }

  function validateForm() {
    $('#form-openbay').submit();
  }
//--></script>
<?php echo $footer; ?>