<?php echo $header; ?>
<div class="container">
  <header>
    <div class="row">
      <div class="col-sm-6">
        <h1 class="pull-left">2<small>/4</small></h1>
        <h3><?php echo $heading_step_2; ?><br><small><?php echo $heading_step_2_small; ?></small></h3>
      </div>
      <div class="col-sm-6">
        <div id="logo" class="pull-right hidden-xs">
          <img src="view/image/logo.png" alt="OpenCart" title="OpenCart" />
        </div>
      </div>
    </div>
  </header>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row">
    <div class="col-sm-9">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <p><?php echo $text_install_php; ?></p>
        <fieldset>
          <table class="table">
            <thead>
              <tr>
                <td width="35%"><b><?php echo $text_setting; ?></b></td>
                <td width="25%"><b><?php echo $text_current; ?></b></td>
                <td width="25%"><b><?php echo $text_required; ?></b></td>
                <td width="15%" class="text-center"><b><?php echo $text_status; ?></b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $text_version; ?></td>
                <td><?php echo $php_version; ?></td>
                <td>5.3+</td>
                <td class="text-center"><?php if ($php_version >= '5.3') { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { ?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $text_global; ?></td>
                <td><?php if ($register_globals) { ?>
                  <?php echo $text_on; ?>
                  <?php } else { ?>
                  <?php echo $text_off; ?>
                  <?php } ?>
                </td>
                <td><?php echo $text_off; ?></td>
                <td class="text-center"><?php if (!$register_globals) { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { ?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $text_magic; ?></td>
                <td><?php if ($magic_quotes_gpc) { ?>
                  <?php echo $text_on; ?>
                  <?php } else { ?>
                  <?php echo $text_off; ?>
                  <?php } ?>
                </td>
                <td><?php echo $text_off; ?></td>
                <td class="text-center"><?php if (!$magic_quotes_gpc) { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { ?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $text_file_upload; ?></td>
                <td><?php if ($file_uploads) { ?>
                  <?php echo $text_on; ?>
                  <?php } else { ?>
                  <?php echo $text_off; ?>
                  <?php } ?>
                </td>
                <td><?php echo $text_on; ?></td>
                <td class="text-center"><?php if ($file_uploads) { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { ?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $text_session; ?></td>
                <td><?php if ($session_auto_start) { ?>
                  <?php echo $text_on; ?>
                  <?php } else { ?>
                  <?php echo $text_off; ?>
                  <?php } ?>
                </td>
                <td><?php echo $text_off; ?></td>
                <td class="text-center"><?php if (!$session_auto_start) { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { ?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } ?>
                </td>
              </tr>
            </tbody>
          </table>
        </fieldset>
        <p><?php echo $text_install_extension; ?></p>
        <fieldset>
          <table class="table">
            <thead>
              <tr>
                <td width="35%"><b><?php echo $text_extension; ?></b></td>
                <td width="25%"><b><?php echo $text_current; ?></b></td>
                <td width="25%"><b><?php echo $text_required; ?></b></td>
                <td width="15%" class="text-center"><b><?php echo $text_status; ?></b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $text_db; ?></td>
                <td><?php if ($db) { ?>
                  <?php echo $text_on; ?>
                  <?php } else { ?>
                  <?php echo $text_off; ?>
                  <?php } ?>
                </td>
                <td><?php echo $text_on; ?></td>
                <td class="text-center"><?php if ($db) { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { ?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $text_gd; ?></td>
                <td><?php if ($gd) { ?>
                  <?php echo $text_on; ?>
                  <?php } else { ?>
                  <?php echo $text_off; ?>
                  <?php } ?>
                </td>
                <td><?php echo $text_on; ?></td>
                <td class="text-center"><?php if ($gd) { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { ?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $text_curl; ?></td>
                <td><?php if ($curl) { ?>
                  <?php echo $text_on; ?>
                  <?php } else { ?>
                  <?php echo $text_off; ?>
                  <?php } ?>
                </td>
                <td><?php echo $text_on; ?></td>
                <td class="text-center"><?php if ($curl) { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { ?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $text_mcrypt; ?></td>
                <td><?php if ($mcrypt_encrypt) { ?>
                  <?php echo $text_on; ?>
                  <?php } else { ?>
                  <?php echo $text_off; ?>
                  <?php } ?>
                </td>
                <td><?php echo $text_on; ?></td>
                <td class="text-center"><?php if ($mcrypt_encrypt) { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { ?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $text_zlib; ?></td>
                <td><?php if ($zlib) { ?>
                  <?php echo $text_on; ?>
                  <?php } else { ?>
                  <?php echo $text_off; ?>
                  <?php } ?>
                </td>
                <td><?php echo $text_on; ?></td>
                <td class="text-center"><?php if ($zlib) { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { ?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $text_zip; ?></td>
                <td><?php if ($zip) { ?>
                  <?php echo $text_on; ?>
                  <?php } else { ?>
                  <?php echo $text_off; ?>
                  <?php } ?>
                </td>
                <td><?php echo $text_on; ?></td>
                <td class="text-center"><?php if ($zip) { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { ?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } ?>
                </td>
              </tr>
              <?php if (!$iconv) { ?>
              <tr>
                <td><?php echo $text_mbstring; ?></td>
                <td>
                  <?php if ($mbstring) { ?>
                  <?php echo $text_on; ?>
                  <?php } else { ?>
                  <?php echo $text_off; ?>
                  <?php } ?>
                </td>
                <td><?php echo $text_on; ?></td>
                <td class="text-center">
                  <?php if ($mbstring) { ?>
                  <span class="text-success"><i class="fa fa-check-circle"></i></span>
                  <?php } else { ?>
                  <span class="text-danger"><i class="fa fa-minus-circle"></i></span>
                  <?php } ?>
                </td>
              </tr>
              <?php } ?>
            </tbody>
          </table>
        </fieldset>
        <p><?php echo $text_install_file; ?></p>
        <fieldset>
          <table class="table">
            <thead>
              <tr>
                <td><b><?php echo $text_file; ?></b></td>
                <td><b><?php echo $text_status; ?></b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $config_catalog; ?></td>
                <td><?php if (!file_exists($config_catalog)) { ?>
                  <span class="text-danger"><?php echo $text_missing; ?></span>
                  <?php } elseif (!is_writable($config_catalog)) { ?>
                  <span class="text-danger"><?php echo $text_unwritable; ?></span>
                  <?php } else { ?>
                  <span class="text-success"><?php echo $text_writable; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $config_admin; ?></td>
                <td><?php if (!file_exists($config_admin)) { ?>
                  <span class="text-danger"><?php echo $text_missing; ?></span>
                  <?php } elseif (!is_writable($config_admin)) { ?>
                  <span class="text-danger"><?php echo $text_unwritable; ?></span>
                  <?php } else { ?>
                  <span class="text-success"><?php echo $text_writable; ?></span>
                  <?php } ?>
                </td>
              </tr>
            </tbody>
          </table>
        </fieldset>
        <p><?php echo $text_install_directory; ?></p>
        <fieldset>
          <table class="table">
            <thead>
              <tr>
                <td align="left"><b><?php echo $text_directory; ?></b></td>
                <td align="left"><b><?php echo $text_status; ?></b></td>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?php echo $image . '/'; ?></td>
                <td><?php if (is_writable($image)) { ?>
                  <span class="text-success"><?php echo $text_writable; ?></span>
                  <?php } else { ?>
                  <span class="text-danger"><?php echo $text_unwritable; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $image_cache . '/'; ?></td>
                <td><?php if (is_writable($image_cache)) { ?>
                  <span class="text-success"><?php echo $text_writable; ?></span>
                  <?php } else { ?>
                  <span class="text-danger"><?php echo $text_unwritable; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $image_catalog . '/'; ?></td>
                <td><?php if (is_writable($image_catalog)) { ?>
                  <span class="text-success"><?php echo $text_writable; ?></span>
                  <?php } else { ?>
                  <span class="text-danger"><?php echo $text_unwritable; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $cache . '/'; ?></td>
                <td><?php if (is_writable($cache)) { ?>
                  <span class="text-success"><?php echo $text_writable; ?></span>
                  <?php } else { ?>
                  <span class="text-danger"><?php echo $text_unwritable; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $logs . '/'; ?></td>
                <td><?php if (is_writable($logs)) { ?>
                  <span class="text-success"><?php echo $text_writable; ?></span>
                  <?php } else { ?>
                  <span class="text-danger"><?php echo $text_unwritable; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $download . '/'; ?></td>
                <td><?php if (is_writable($download)) { ?>
                  <span class="text-success"><?php echo $text_writable; ?></span>
                  <?php } else { ?>
                  <span class="text-danger"><?php echo $text_unwritable; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $upload . '/'; ?></td>
                <td><?php if (is_writable($upload)) { ?>
                  <span class="text-success"><?php echo $text_writable; ?></span>
                  <?php } else { ?>
                  <span class="text-danger"><?php echo $text_unwritable; ?></span>
                  <?php } ?>
                </td>
              </tr>
              <tr>
                <td><?php echo $modification . '/'; ?></td>
                <td><?php if (is_writable($modification)) { ?>
                  <span class="text-success"><?php echo $text_writable; ?></span>
                  <?php } else { ?>
                  <span class="text-danger"><?php echo $text_unwritable; ?></span>
                  <?php } ?>
                </td>
              </tr>
            </tbody>
          </table>
        </fieldset>
        <div class="buttons">
          <div class="pull-left"><a href="<?php echo $back; ?>" class="btn btn-default"><?php echo $button_back; ?></a></div>
          <div class="pull-right">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
      </form>
    </div>
    <div class="col-sm-3">
      <ul class="list-group">
        <li class="list-group-item"><?php echo $text_license; ?></li>
        <li class="list-group-item"><b><?php echo $text_installation; ?></b></li>
        <li class="list-group-item"><?php echo $text_configuration; ?></li>
        <li class="list-group-item"><?php echo $text_finished; ?></li>
      </ul>
    </div>
  </div>
</div>
<?php echo $footer; ?>