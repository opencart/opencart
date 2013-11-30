<?php echo $header; ?>
<div class="container">
  <h1><?php echo $heading_step_3; ?></h1>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="row">
    <div class="col-sm-9">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <p>1. Please enter your database connection details.</p>
        <fieldset>
        
        
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-db-driver"><?php echo $entry_db_driver; ?></label>
            <div class="col-sm-10">
              <select name="db_driver">
                  <?php if (extension_loaded('mysqli')) { ?>
                  <option value="mysqli">MySQLi</option>
                  <?php } ?>
                  <?php if (extension_loaded('mysql')) { ?>
                  <option value="mysql">MySQL</option>
                  <?php } ?>
                  <?php if (extension_loaded('pdo')) { ?>
                  <option value="mpdo">PDO</option>
                  <?php } ?>
                </select>
            </div>
          </div>        
        
        
          <table class="form">
            <tr>
              <td>Database Driver:</td>
              <td><select name="db_driver">
                  <?php if (extension_loaded('mysqli')) { ?>
                  <option value="mysqli">MySQLi</option>
                  <?php } ?>
                  <?php if (extension_loaded('mysql')) { ?>
                  <option value="mysql">MySQL</option>
                  <?php } ?>
                  <?php if (extension_loaded('pdo')) { ?>
                  <option value="mpdo">PDO</option>
                  <?php } ?>
                </select></td>
            </tr>
            <tr>
              <td><span class="required">*</span> Database Host:</td>
              <td><input type="text" name="db_host" value="<?php echo $db_host; ?>" />
                <br />
                <?php if ($error_db_host) { ?>
                <span class="required"><?php echo $error_db_host; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> User:</td>
              <td><input type="text" name="db_user" value="<?php echo $db_user; ?>" />
                <br />
                <?php if ($error_db_user) { ?>
                <span class="required"><?php echo $error_db_user; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td>Password:</td>
              <td><input type="text" name="db_password" value="<?php echo $db_password; ?>" /></td>
            </tr>
            <tr>
              <td><span class="required">*</span> Database Name:</td>
              <td><input type="text" name="db_name" value="<?php echo $db_name; ?>" />
                <br />
                <?php if ($error_db_name) { ?>
                <span class="required"><?php echo $error_db_name; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td>Database Prefix:</td>
              <td><input type="text" name="db_prefix" value="<?php echo $db_prefix; ?>" />
                <br />
                <?php if ($error_db_prefix) { ?>
                <span class="required"><?php echo $error_db_prefix; ?></span>
                <?php } ?></td>
            </tr>
          </table>
        </fieldset>
        <p>2. Please enter a username and password for the administration.</p>
        <fieldset>
          <table class="form">
            <tr>
              <td><span class="required">*</span> Username:</td>
              <td><input type="text" name="username" value="<?php echo $username; ?>" />
                <br />
                <?php if ($error_username) { ?>
                <span class="required"><?php echo $error_username; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> Password:</td>
              <td><input type="text" name="password" value="<?php echo $password; ?>" />
                <br />
                <?php if ($error_password) { ?>
                <span class="required"><?php echo $error_password; ?></span>
                <?php } ?></td>
            </tr>
            <tr>
              <td><span class="required">*</span> E-Mail:</td>
              <td><input type="text" name="email" value="<?php echo $email; ?>" />
                <br />
                <?php if ($error_email) { ?>
                <span class="required"><?php echo $error_email; ?></span>
                <?php } ?></td>
            </tr>
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
      <ul>
        <li><?php echo $text_license; ?></li>
        <li><?php echo $text_installation; ?></li>
        <li><b><?php echo $text_configuration; ?></b></li>
        <li><?php echo $text_finished; ?></li>
      </ul>
    </div>
  </div>
</div>
<?php echo $footer; ?>