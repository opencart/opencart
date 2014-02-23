<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>

  <?php if ($error) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>

  <?php if ($success) { ?>
  <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>

  <?php if ($check['mcrypt'] != 1) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $lang_mcrypt_text_false; ?></div>
  <?php } ?>

  <?php if ($check['mbstring'] != 1) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $lang_mb_text_false; ?></div>
  <?php } ?>

  <?php if ($check['ftpenabled'] != 1) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $lang_ftp_text_false; ?></div>
  <?php } ?>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h1 class="panel-title"><i class="fa fa-puzzle-piece fa-lg"></i> <?php echo $lang_heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <div class="row">
        <div class="col-md-7">
          <div class="row">
            <div class="col-md-12">
              <table class="table table-bordered table-hover">
                <thead>
                <tr>
                  <td class="text-left" width="60%"><?php echo $lang_column_name; ?></td>
                  <td class="text-center" width="20%"><?php echo $lang_column_status; ?></td>
                  <td class="text-right" width="20%"><?php echo $lang_column_action; ?></td>
                </tr>
                </thead>
                <tbody>
                <?php if ($extensions) { ?><?php foreach ($extensions as $extension) { ?>
                <tr>
                  <td class="text-left"><?php echo $extension['name']; ?></td>
                  <td class="text-center"><?php echo $extension['status'] ?></td>
                  <td class="text-right">
                    <?php if ($extension['installed']) { ?>
                      <a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                    <?php } ?>

                    <?php if (!$extension['installed']) { ?>
                      <a href="<?php echo $extension['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                    <?php } else { ?>
                      <a href="<?php echo $extension['uninstall']; ?>" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                    <?php } ?>
                  </td>
                </tr>
                <?php } ?><?php } else { ?>
                <tr>
                  <td class="text-center" colspan="8"><?php echo $lang_text_no_results; ?></td>
                </tr>
                <?php } ?>
                </tbody>
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12 text-center">
                <div class="row">
                  <div class="col-md-3 text-center">
                    <div class="well">
                      <a href="<?php echo $manage_link; ?>">
                        <span class="fa-stack fa-3x">
                          <i class="fa fa-square-o fa-stack-2x"></i>
                          <i class="fa fa-wrench fa-stack-1x"></i>
                        </span>
                        <h4><?php echo $text_manage; ?></h4>
                      </a>
                    </div>
                  </div>
                  <div class="col-md-3 text-center">
                    <div class="well">
                      <a href="http://www.openbaypro.com/help" target="_BLANK">
                        <span class="fa-stack fa-3x">
                          <i class="fa fa-square-o fa-stack-2x"></i>
                          <i class="fa fa-comments-o fa-stack-1x"></i>
                        </span>
                        <h4><?php echo $text_help; ?></h4>
                      </a>
                    </div>
                  </div>
                  <div class="col-md-3 text-center">
                    <div class="well">
                      <a href="http://www.openbaypro.com/tutorials" target="_BLANK">
                        <span class="fa-stack fa-3x">
                          <i class="fa fa-square-o fa-stack-2x"></i>
                          <i class="fa fa-youtube-play fa-stack-1x"></i>
                        </span>
                        <h4><?php echo $text_tutorials; ?></h4>
                      </a>
                    </div>
                  </div>
                  <div class="col-md-3 text-center">
                    <div class="well">
                      <a href="http://www.openbaypro.com/suggestions" target="_BLANK">
                        <span class="fa-stack fa-3x">
                          <i class="fa fa-square-o fa-stack-2x"></i>
                          <i class="fa fa-bullhorn fa-stack-1x"></i>
                        </span>
                        <h4><?php echo $text_suggestions; ?></h4>
                      </a>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
        <div class="col-md-5" style="padding-left:10px;">
          <div id="openbay_version" class="alert alert-info text-left">
            <div id="openbay_version_loading">
              <i class="fa fa-refresh fa-spin"></i> <?php echo $lang_checking_version; ?>
            </div>
          </div>
          <div id="openbay_notification" class="alert alert-info text-left">
            <div id="openbay_loading">
              <i class="fa fa-refresh fa-spin"></i> <?php echo $lang_getting_messages; ?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var token = "<?php echo $_GET['token']; ?>";

function getOpenbayVersion() {
  var version = '<?php echo $openbay_version; ?>';

  $('#openbay_version').empty().html('<div id="openbay_version_loading"><i class="fa fa-refresh fa-spin"></i> <?php echo $lang_checking_version; ?></div>');

  setTimeout(function () {
    $.ajax({
      type: 'GET',
      url: 'index.php?route=extension/openbay/getVersion&token=' + token,
      dataType: 'json',
      success: function (json) {
        $('#openbay_version_loading').hide();

        if (version < json.version) {
          $('#openbay_version').removeClass('attention').addClass('alert-warning').append('<i class="fa fa-warning"></i> <?php echo $lang_version_old_1; ?> v.' + version + ', <?php echo $lang_version_old_2; ?> v.' + json.version);
        } else {
          $('#openbay_version').removeClass('attention').addClass('alert-success').append('<i class="fa fa-check"></i> <?php echo $lang_latest; ?> (v.' + version + ')');
        }
      },
      failure: function () {
        $('#openbay_version').html('<?php echo $lang_error_retry; ?><strong><span onclick="getOpenbayVersion();"><?php echo $lang_btn_retry; ?></span></strong>');
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }, 500);
}

function getOpenbayNotifications() {
  $('#openbay_notification').empty().html('<div id="openbay_loading"><i class="fa fa-refresh fa-spin"></i> <?php echo $lang_checking_messages; ?></div>');

  setTimeout(function () {
    $.ajax({
      type: 'GET',
      url: 'index.php?route=extension/openbay/getNotifications&token='+token,
      dataType: 'json',
      success: function (json) {
        html = '<h4><i class="fa fa-info-circle"></i>  <?php echo $lang_title_messages; ?></h4>';
        html += '<ul>';
        $.each(json, function (key, val) {
          html += '<li>' + val + '</li>';
        });
        html += '</ul>';

        $('#openbay_notification').html(html);
      },
      error: function (xhr, ajaxOptions, thrownError) {
        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
      }
    });
  }, 500);
}

$(document).ready(function () {
  getOpenbayVersion();
  getOpenbayNotifications();
});
//--></script>
<?php echo $footer; ?>