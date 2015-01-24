<?php echo $header; ?>
<div class="container">
  <header>
    <div class="row">
      <div class="col-sm-6">
        <h1 class="pull-left">3<small>/4</small></h1>
        <h3><?php echo $heading_step_3; ?><br><small><?php echo $heading_step_3_small; ?></small></h3>
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
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal">
        <p><?php echo $text_db_connection; ?></p>
        <fieldset>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-db-driver"><?php echo $entry_db_driver; ?></label>
            <div class="col-sm-10">
              <select name="db_driver" id="input-db-driver" class="form-control">
                <?php if ($mysqli) { ?>
                <?php if ($db_driver == 'mysqli') { ?>
                <option value="mysqli" selected="selected"><?php echo $text_mysqli; ?></option>
                <?php } else { ?>
                <option value="mysqli"><?php echo $text_mysqli; ?></option>
                <?php } ?>
                <?php } ?>
                <?php if ($pdo) { ?>
                <?php if ($db_driver == 'mpdo') { ?>
                <option value="mpdo" selected="selected"><?php echo $text_mpdo; ?></option>
                <?php } else { ?>
                <option value="mpdo"><?php echo $text_mpdo; ?></option>
                <?php } ?>
                <?php } ?>
                <?php if ($pgsql) { ?>
                <?php if ($db_driver == 'pgsql') { ?>
                <option value="pgsql" selected="selected"><?php echo $text_pgsql; ?></option>
                <?php } else { ?>
                <option value="pgsql"><?php echo $text_pgsql; ?></option>
                <?php } ?>
                <?php } ?>                
              </select>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-db-hostname"><?php echo $entry_db_hostname; ?></label>
            <div class="col-sm-10">
              <input type="text" name="db_hostname" value="<?php echo $db_hostname; ?>" id="input-db-hostname" class="form-control" />
              <?php if ($error_db_hostname) { ?>
              <div class="text-danger"><?php echo $error_db_hostname; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-db-username"><?php echo $entry_db_username; ?></label>
            <div class="col-sm-10">
              <input type="text" name="db_username" value="<?php echo $db_username; ?>" id="input-db-username" class="form-control" />
              <?php if ($error_db_username) { ?>
              <div class="text-danger"><?php echo $error_db_username; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-db-password"><?php echo $entry_db_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="db_password" value="<?php echo $db_password; ?>" id="input-db-password" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-db-database"><?php echo $entry_db_database; ?></label>
            <div class="col-sm-10">
              <input type="text" name="db_database" value="<?php echo $db_database; ?>" id="input-db-database" class="form-control" />
              <?php if ($error_db_database) { ?>
              <div class="text-danger"><?php echo $error_db_database; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-db-prefix"><?php echo $entry_db_prefix; ?></label>
            <div class="col-sm-10">
              <input type="text" name="db_prefix" value="<?php echo $db_prefix; ?>" id="input-db-prefix" class="form-control" />
              <?php if ($error_db_prefix) { ?>
              <div class="text-danger"><?php echo $error_db_prefix; ?></div>
              <?php } ?>           
           </div>
          </div>
        </fieldset>
        <p><?php echo $text_db_administration; ?></p>
        <fieldset>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-username"><?php echo $entry_username; ?></label>
            <div class="col-sm-10">
              <input type="text" name="username" value="<?php echo $username; ?>" id="input-username" class="form-control" />
              <?php if ($error_username) { ?>
              <div class="text-danger"><?php echo $error_username; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-password"><?php echo $entry_password; ?></label>
            <div class="col-sm-10">
              <input type="text" name="password" value="<?php echo $password; ?>" id="input-password" class="form-control" />
              <?php if ($error_password) { ?>
              <div class="text-danger"><?php echo $error_password; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-email"><?php echo $entry_email; ?></label>
            <div class="col-sm-10">
              <input type="text" name="email" value="<?php echo $email; ?>" id="input-email" class="form-control" />
              <?php if ($error_email) { ?>
              <div class="text-danger"><?php echo $error_email; ?></div>
              <?php } ?>
            </div>
          </div>
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
        <li class="list-group-item"><?php echo $text_installation; ?></li>
        <li class="list-group-item"><b><?php echo $text_configuration; ?></b></li>
        <li class="list-group-item"><?php echo $text_finished; ?></li>
      </ul>
    </div>
  </div>
</div>
<?php echo $footer; ?>