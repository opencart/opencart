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
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;"><?php echo $entry_name; ?><br />
      <input type="text" name="name" value="<?php echo $name; ?>" />
      <br />
      <?php if ($error_name) { ?>
      <span class="error"><?php echo $error_name; ?></span>
      <?php } ?>
      <br />
      <?php echo $entry_email; ?><br />
      <input type="text" name="email" value="<?php echo $email; ?>" />
      <br />
      <?php if ($error_email) { ?>
      <span class="error"><?php echo $error_email; ?></span>
      <?php } ?>
      <br />
      <?php echo $entry_enquiry; ?><br />
      <textarea name="enquiry" style="width: 99%;" rows="10"><?php echo $enquiry; ?></textarea>
      <?php if ($error_enquiry) { ?>
      <span class="error"><?php echo $error_enquiry; ?></span>
      <?php } ?>
      <br />
      <?php echo $entry_captcha; ?><br />
      <input type="text" name="captcha" value="<?php echo $captcha; ?>" />
      <br />
      <?php if ($error_captcha) { ?>
      <span class="error"><?php echo $error_captcha; ?></span>
      <?php } ?>
      <img src="index.php?route=information/contact/captcha" /></div>
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
