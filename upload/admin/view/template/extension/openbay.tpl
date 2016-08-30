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
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="mi mi-check-circle">check_circle</i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if (!empty($error)) { ?>
      <?php foreach($error as $error_message) { ?>
        <div class="alert alert-danger"><i class="mi mi-exclamation-circle">error</i> <?php echo $error_message; ?></div>
      <?php } ?>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="mi mi-puzzle-piece">extension</i> <?php echo $text_dashboard; ?></h3>
      </div>
      <div class="panel-body">
        <div class="row">
          <div class="col-md-6">
            <div class="row">
              <div class="col-md-12">
                <table class="table table-bordered table-hover">
                  <thead>
                    <tr>
                      <td class="text-left" width="60%"><?php echo $column_name; ?></td>
                      <td class="text-center" width="20%"><?php echo $column_status; ?></td>
                      <td class="text-right" width="20%"><?php echo $column_action; ?></td>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach ($extensions as $extension) { ?>
                    <tr>
                      <td class="text-left"><?php echo $extension['name']; ?></td>
                      <td class="text-center"><?php echo $extension['status']; ?></td>
                      <td class="text-right">
                        <?php if (!$extension['installed']) { ?>
                          <a href="<?php echo $extension['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success" id="button-install-<?php echo $extension['code']; ?>"><i class="mi mi-plus-circle">add_circle</i></a>
                        <?php } else { ?>
                          <a href="<?php echo $extension['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary" id="button-edit-<?php echo $extension['code']; ?>"><i class="mi mi-pencil">mode_edit</i></a>
                          <a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href='<?php echo $extension['uninstall']; ?>' : false;" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger" id="button-uninstall-<?php echo $extension['code']; ?>"><i class="mi mi-minus-circle">remove circle</i></a>
                        <?php } ?></td>
                    </tr>
                    <?php } ?>
                  </tbody>
                </table>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 text-center">
                <div class="row">
                  <div class="col-md-4 text-center">
                    <div class="well"> <a href="<?php echo $product_link; ?>"> <span class="mi-stack mi-2x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-tags mi-stack-1x">local_offer</i> </span>
                      <h4><?php echo $text_products; ?></h4>
                      </a> </div>
                  </div>
                  <div class="col-md-4 text-center">
                    <div class="well"> <a href="<?php echo $order_link; ?>"> <span class="mi-stack mi-2x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-shopping-cart mi-stack-1x">shopping_cart</i> </span>
                      <h4><?php echo $text_orders; ?></h4>
                      </a> </div>
                  </div>
                  <div class="col-md-4 text-center">
                    <div class="well"> <a href="<?php echo $manage_link; ?>"> <span class="mi-stack mi-2x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-wrench mi-stack-1x">build</i> </span>
                      <h4><?php echo $text_manage; ?></h4>
                      </a> </div>
                  </div>
                  <div class="col-md-4 text-center">
                    <div class="well"> <a href="http://www.openbaypro.com/help" target="_BLANK"> <span class="mi-stack mi-2x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-comments-o mi-stack-1x">forum</i> </span>
                      <h4><?php echo $text_help; ?></h4>
                      </a> </div>
                  </div>
                  <div class="col-md-4 text-center">
                      <div class="well"> <a href="http://www.openbaypro.com/tutorials" target="_BLANK"> <span class="mi-stack mi-2x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-youtube-play mi-stack-1x">subscriptions</i> </span>
                      <h4><?php echo $text_tutorials; ?></h4>
                      </a> </div>
                  </div>
                  <div class="col-md-4 text-center">
                    <div class="well"> <a href="http://www.openbaypro.com/suggestions" target="_BLANK"> <span class="mi-stack mi-2x"> <i class="mi mi-square-o mi-stack-2x">crop_square</i> <i class="mi mi-bullhorn mi-stack-1x">announcement</i> </span>
                      <h4><?php echo $text_suggestions; ?></h4>
                      </a> </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6" style="padding-left:10px;">
            <div id="openbay-version" class="alert alert-info text-left">
              <div id="openbay-version-loading"> <i class="mi mi-cog mi-lg mi-spin">settings</i> <?php echo $text_version_check; ?> </div>
            </div>
            <div id="openbay-notification" class="alert alert-info text-left">
              <div id="openbay-loading"> <i class="mi mi-cog mi-lg mi-spin">settings</i> <?php echo $text_getting_messages; ?> </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
  function version() {
    var version = '<?php echo $openbay_version; ?>';

    $('#openbay-version').empty().html('<div id="openbay-version-loading"><i class="mi mi-cog mi-lg mi-spin">settings</i> <?php echo $text_version_check; ?></div>');

    setTimeout(function () {
      $.ajax({
        type: 'GET',
        url: 'index.php?route=extension/openbay/version&token=<?php echo $token; ?>',
        dataType: 'json',
        success: function (json) {
          $('#openbay-version-loading').hide();

          if (json.error) {
            $('#openbay-version').removeClass('attention').addClass('alert-warning').append(json.msg);
          } else {
            if (version < json.version) {
              $('#openbay-version').removeClass('attention').addClass('alert-warning').append('<i class="mi mi-warning">warning</i> <?php echo $text_version_current; ?> v.' + version + ', <?php echo $text_version_available; ?> v.' + json.version);
            } else {
              $('#openbay-version').removeClass('attention').addClass('alert-success').append('<i class="mi mi-check">check</i> <?php echo $text_version_latest; ?> (v.' + version + ')');
            }
          }
        },
        failure: function () {
          $('#openbay-version').html('<?php echo $error_failed; ?><strong><span onclick="version();"><?php echo $button_retry; ?></span></strong>');
        },
        error: function (xhr, ajaxOptions, thrownError) {
          if (xhr.status != 0) {
            alert(xhr.status + "\r\n" + thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        }
      });
    }, 500);
  }

  function getNotifications() {
    $('#openbay-notification').empty().html('<div id="openbay-loading"><i class="mi mi-cog mi-lg mi-spin">settings</i> <?php echo $text_getting_messages; ?></div>');

    setTimeout(function () {
      $.ajax({
        type: 'GET',
        url: 'index.php?route=extension/openbay/notifications&token=<?php echo $token; ?>',
        dataType: 'json',
        success: function (json) {
          html = '<h4><i class="mi mi-info-circle">info</i>  <?php echo $text_title_messages; ?></h4>';
          html += '<ul>';
          $.each(json, function (key, val) {
            html += '<li>' + val + '</li>';
          });
          html += '</ul>';

          $('#openbay-notification').html(html);
        },
        error: function (xhr, ajaxOptions, thrownError) {
          if (xhr.status != 0) {
            alert(xhr.status + "\r\n" +thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
          }
        }
      });
    }, 500);
  }

  $(document).ready(function () {
    version();
    getNotifications();
  });
//--></script></div>
<?php echo $footer; ?>
