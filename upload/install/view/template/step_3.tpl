<?php echo $header; ?>
<h1>Step 3 - Configuration</h1>
<div id="column-right">
  <ul>
    <li>License</li>
    <li>Pre-Installation</li>
    <li><b>Configuration</b></li>
    <li>Finished</li>
  </ul>
</div>
<div id="content">
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <p>1. Please enter your database connection details.</p>
    <fieldset>
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
      <div class="left"><a href="<?php echo $back; ?>" class="btn">Back</a></div>
      <div class="right">
        <input type="submit" value="Continue" class="btn" />
      </div>
    </div>
  </form>
</div>
<?php echo $footer; ?>