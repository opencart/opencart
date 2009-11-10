<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <h1><?php echo $heading_title; ?></h1>
  </div>
  <div class="middle">
    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="shipping">
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_shipping_address; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <table width="536">
          <tr>
            <td width="50%" valign="top"><?php echo $text_shipping_to; ?><br />
              <br />
              <div style="text-align: center;"><a onclick="location='<?php echo $change_address; ?>'" class="button"><span><?php echo $button_change_address; ?></span></a></div></td>
            <td width="50%" valign="top"><b><?php echo $text_shipping_address; ?></b><br />
              <?php echo $address; ?></td>
          </tr>
        </table>
      </div>
      <?php if ($methods) { ?>
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_shipping_method; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <p><?php echo $text_shipping_methods; ?></p>
        <table width="536" cellpadding="3">
          <?php foreach ($methods as $method) { ?>
          <tr>
            <td colspan="3"><b><?php echo $method['title']; ?></b></td>
          </tr>
          <?php if (!$method['error']) { ?>
          <?php foreach ($method['quote'] as $quote) { ?>
          <tr>
            <td width="1"><label for="<?php echo $quote['id']; ?>">
                <?php if ($quote['id'] == $default) { ?>
                <input type="radio" name="shipping_method" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>" checked="checked" style="margin: 0px;" />
                <?php } else { ?>
                <input type="radio" name="shipping_method" value="<?php echo $quote['id']; ?>" id="<?php echo $quote['id']; ?>" style="margin: 0px;" />
                <?php } ?>
              </label></td>
            <td><label for="<?php echo $quote['id']; ?>" style="cursor: pointer;"><?php echo $quote['title']; ?></label></td>
            <td align="right"><label for="<?php echo $quote['id']; ?>" style="cursor: pointer;"><?php echo $quote['text']; ?></label></td>
          </tr>
          <?php } ?>
          <?php } else { ?>
          <tr>
            <td colspan="2"><div class="error"><?php echo $method['error']; ?></div></td>
          </tr>
          <?php } ?>
          <?php } ?>
        </table>
      </div>
      <?php } ?>
      <b style="margin-bottom: 2px; display: block;"><?php echo $text_comments; ?></b>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
        <textarea name="comment" rows="8" style="width: 99%;"><?php echo $comment; ?></textarea>
      </div>
      <div class="buttons">
        <table>
          <tr>
            <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
            <td align="right"><a onclick="$('#shipping').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
          </tr>
        </table>
      </div>
    </form>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
<?php echo $footer; ?> 