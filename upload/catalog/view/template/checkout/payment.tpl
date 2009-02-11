<div class="top">
  <h1><?php echo $heading_title; ?></h1>
</div>
<div class="middle">
  <?php if ($error) { ?>
  <div class="warning"><?php echo $error; ?></div>
  <?php } ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="payment">
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_payment_address; ?></b>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px; display: inline-block;">
      <div style="width: 50%; display: inline-block; float: left;"><?php echo $text_payment_to; ?><br />
        <br />
        <div style="text-align: center;"><a onclick="location='<?php echo $change_address; ?>'" class="button"><span><?php echo $button_change_address; ?></span></a></div>
      </div>
      <div style="width: 50%; display: inline-block; float: right;"><b><?php echo $text_payment_address; ?></b><br />
        <?php echo $address; ?></div>
    </div>
    <?php if ($methods) { ?>
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_payment_method; ?></b>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
      <p><?php echo $text_payment_methods; ?></p>
      <table width="100%">
        <?php foreach ($methods as $method) { ?>
        <tr>
          <td width="1"><?php if ($method['id'] == $default) { ?>
            <input type="radio" name="payment" value="<?php echo $method['id']; ?>" id="<?php echo $method['id']; ?>" checked="checked" />
            <?php } else { ?>
            <input type="radio" name="payment" value="<?php echo $method['id']; ?>" id="<?php echo $method['id']; ?>" />
            <?php } ?></td>
          <td><label for="<?php echo $method['id']; ?>"><?php echo $method['title']; ?></label></td>
        </tr>
        <?php } ?>
      </table>
    </div>
    <?php } ?>
    <b style="margin-bottom: 3px; display: block;"><?php echo $text_comments; ?></b>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-bottom: 10px;">
      <textarea name="comment" rows="8" style="width: 99%;"><?php echo $comment; ?></textarea>
    </div>
    <div class="buttons">
      <table>
        <tr>
          <td align="left"><a onclick="location='<?php echo $back; ?>'" class="button"><span><?php echo $button_back; ?></span></a></td>
          <td align="right"><a onclick="$('#payment').submit();" class="button"><span><?php echo $button_continue; ?></span></a></td>
        </tr>
      </table>
    </div>
  </form>
</div>
<div class="bottom"></div>
