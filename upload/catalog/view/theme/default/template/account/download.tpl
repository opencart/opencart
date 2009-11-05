<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <h1><?php echo $heading_title; ?></h1>
  </div>
  <div class="middle">
    <?php foreach ($downloads as $download) { ?>
    <div style="display: inline-block; margin-bottom: 10px; width: 100%;">
      <div style="width: 45%; float: left; margin-bottom: 2px;"><b><?php echo $text_order; ?></b> <?php echo $download['order_id']; ?></div>
      <div style="width: 45%; float: right; margin-bottom: 2px; text-align: right;"><b><?php echo $text_size; ?></b> <?php echo $download['size']; ?></div>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; clear: both;">
        <div style="padding: 5px;">
          <table width="536">
            <tr>
              <td width="40%"><?php echo $text_name; ?> <?php echo $download['name']; ?></td>
              <td width="50%"><?php echo $text_remaining; ?> <?php echo $download['remaining']; ?></td>
              <td rowspan="2" style="text-align: right;"><a onclick="location='<?php echo $download['href']; ?>'" class="button"><span><?php echo $text_download; ?></span></a></td>
            </tr>
            <tr>
              <td colspan="2"><?php echo $text_date_added; ?> <?php echo $download['date_added']; ?></td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    <?php } ?>
    <div class="pagination"><?php echo $pagination; ?></div>
    <div class="buttons">
      <table>
        <tr>
          <td align="right"><a onclick="location='<?php echo $continue; ?>'" class="button"><span><?php echo $button_continue; ?></span></a></td>
        </tr>
      </table>
    </div>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
<?php echo $footer; ?> 