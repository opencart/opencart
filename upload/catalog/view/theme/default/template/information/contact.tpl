<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="middle">
    <form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" enctype="multipart/form-data" id="contact">
      <div class="content">
        <div style="display: inline-block; width: 100%;">
          <div style="float: left; display: inline-block; width: 50%;"><b><?php echo $text_address; ?></b><br />
            <?php echo $store; ?><br />
            <?php echo $address; ?></div>
          <div style="float: right; display: inline-block; width: 50%;">
            <?php if ($telephone) { ?>
            <b><?php echo $text_telephone; ?></b><br />
            <?php echo $telephone; ?><br />
            <br />
            <?php } ?>
            <?php if ($fax) { ?>
            <b><?php echo $text_fax; ?></b><br />
            <?php echo $fax; ?>
            <?php } ?>
          </div>
        </div>
      </div>
      <div class="content">
        <table width="100%">
          <tr>
            <td><?php echo $entry_name; ?><br />
              <input type="text" name="name" value="<?php echo $name; ?>" />
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_email; ?><br />
              <input type="text" name="email" value="<?php echo $email; ?>" />
              <?php if ($error_email) { ?>
              <span class="error"><?php echo $error_email; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_enquiry; ?><br />
              <textarea name="enquiry" style="width: 99%;" rows="10"><?php echo $enquiry; ?></textarea>
              <?php if ($error_enquiry) { ?>
              <span class="error"><?php echo $error_enquiry; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_captcha; ?><br />
              <input type="text" name="captcha" value="<?php echo $captcha; ?>" autocomplete="off" />
              <?php if ($error_captcha) { ?>
              <span class="error"><?php echo $error_captcha; ?></span>
              <?php } ?>
              <br />
              <img src="index.php?route=information/contact/captcha" /></td>
          </tr>
        </table>
      </div>
      <div class="buttons">
        <table>
          <tr>
            <td align="right"><a onclick="$('#contact').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
          </tr>
        </table>
      </div>
    </form>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<?php echo $footer; ?> 