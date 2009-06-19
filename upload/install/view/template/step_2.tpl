<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <h1>Step 2 - Configuration</h1>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <p>1 . Please enter your database connection details.</p>
  <table>
    <tr>
      <td width="185"><span class="required">*</span>Database Host:</td>
      <td><input type="text" name="db_host" value="<?php echo $db_host; ?>" />
        <br />
        <?php if ($error_db_host) { ?>
        <span class="required"><?php echo $error_db_host; ?></span>
        <?php } ?></td>
    </tr>
    <tr>
      <td><span class="required">*</span>User:</td>
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
      <td><span class="required">*</span>Database Name:</td>
      <td><input type="text" name="db_name" value="<?php echo $db_name; ?>" />
        <br />
        <?php if ($error_db_name) { ?>
        <span class="required"><?php echo $error_db_name; ?></span>
        <?php } ?></td>
    </tr>
    <tr>
      <td>Database Prefix:</td>
      <td><input type="text" name="db_prefix" value="<?php echo $db_prefix; ?>" /></td>
    </tr>
  </table>
  <p>2. Please enter a username and password for the administration.</p>
  <table>
    <tr>
      <td width="185"><span class="required">*</span>Username:</td>
      <td><input type="text" name="username" value="<?php echo $username; ?>" />
        <?php if ($error_username) { ?>
        <span class="required"><?php echo $error_username; ?></span>
        <?php } ?></td>
    </tr>
    <tr>
      <td><span class="required">*</span>Password:</td>
      <td><input type="text" name="password" value="<?php echo $password; ?>" />
        <?php if ($error_password) { ?>
        <span class="required"><?php echo $error_password; ?></span>
        <?php } ?></td>
    </tr>
    <tr>
      <td><span class="required">*</span>E-Mail:</td>
      <td><input type="text" name="email" value="<?php echo $email; ?>" />
        <?php if ($error_email) { ?>
        <span class="required"><?php echo $error_email; ?></span>
        <?php } ?></td>
    </tr>
  </table>
  <div class="button">
    <input type="submit" value="Continue" />
  </div>
</form>
