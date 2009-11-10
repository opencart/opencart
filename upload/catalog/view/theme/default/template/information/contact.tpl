<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <h1><?php echo $heading_title; ?></h1>
  </div>
  <div class="middle">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="contact">
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px; display: inline-block; width: 536px;">
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
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <table width="536">
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
              <input type="text" name="captcha" value="<?php echo $captcha; ?>" />
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
  <div class="bottom">&nbsp;</div>
</div>
<?php echo $footer; ?> 