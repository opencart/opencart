<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <h1><?php echo $heading_title; ?></h1>
  </div>
  <div class="middle">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="payment">
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_payment_address; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px; display: inline-block;">
        <table width="536">
          <tr>
            <td width="50%" valign="top"><?php echo $text_payment_to; ?><br />
              <br />
              <div style="text-align: center;"><a onclick="location='<?php echo $change_address; ?>'" class="button"><span><?php echo $button_change_address; ?></span></a></div></td>
            <td width="50%" valign="top"><b><?php echo $text_payment_address; ?></b><br />
              <?php echo $address; ?></td>
          </tr>
        </table>
      </div>
      <?php if ($methods) { ?>
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_payment_method; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <p><?php echo $text_payment_methods; ?></p>
        <table width="536" cellpadding="3">
          <?php foreach ($methods as $method) { ?>
          <tr>
            <td width="1"><?php if ($method['id'] == $default) { ?>
              <input type="radio" name="payment_method" value="<?php echo $method['id']; ?>" id="<?php echo $method['id']; ?>" checked="checked" style="margin: 0px;" />
              <?php } else { ?>
              <input type="radio" name="payment_method" value="<?php echo $method['id']; ?>" id="<?php echo $method['id']; ?>" style="margin: 0px;" />
              <?php } ?></td>
            <td><label for="<?php echo $method['id']; ?>" style="cursor: pointer;"><?php echo $method['title']; ?></label></td>
          </tr>
          <?php } ?>
        </table>
      </div>
      <?php } ?>
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_comments; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <textarea name="comment" rows="8" style="width: 99%;"><?php echo $comment; ?></textarea>
      </div>
      <?php if ($text_agree) { ?>
      <div class="buttons">
        <table>
          <tr>
            <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
            <td align="right" style="padding-right: 5px;"><?php echo $text_agree; ?></td>
            <td width="5" style="padding-right: 10px;"><?php if ($agree) { ?>
              <input type="checkbox" name="agree" value="1" checked="checked" />
              <?php } else { ?>
              <input type="checkbox" name="agree" value="1" />
              <?php } ?></td>
            <td align="right" width="5"><a onclick="$('#payment').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
          </tr>
        </table>
      </div>
      <?php } else { ?>
      <div class="buttons">
        <table>
          <tr>
            <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
            <td align="right"><a onclick="$('#payment').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
          </tr>
        </table>
      </div>
      <?php } ?>
    </form>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
<?php echo $footer; ?> 