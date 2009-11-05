<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <h1><?php echo $heading_title; ?></h1>
  </div>
  <div class="middle">
    <?php foreach ($orders as $order) { ?>
    <div style="display: inline-block; margin-bottom: 10px; width: 100%;">
      <div style="width: 49%; float: left; margin-bottom: 2px;"><b><?php echo $text_order; ?></b> #<?php echo $order['order_id']; ?></div>
      <div style="width: 49%; float: right; margin-bottom: 2px; text-align: right;"><b><?php echo $text_status; ?></b> <?php echo $order['status']; ?></div>
      <div style="background: #F7F7F7; border: 1px solid #DDDDDD; width: 100%; clear: both;">
        <div style="padding: 5px;">
          <table width="536">
            <tr>
              <td><?php echo $text_date_added; ?> <?php echo $order['date_added']; ?></td>
              <td><?php echo $text_customer; ?> <?php echo $order['name']; ?></td>
              <td rowspan="2" style="text-align: right;"><a onclick="location='<?php echo $order['href']; ?>'" class="button"><span><?php echo $button_view; ?></span></a></td>
            </tr>
            <tr>
              <td><?php echo $text_products; ?> <?php echo $order['products']; ?></td>
              <td><?php echo $text_total; ?> <?php echo $order['total']; ?></td>
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