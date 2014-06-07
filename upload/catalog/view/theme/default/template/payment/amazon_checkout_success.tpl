<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <h2><?php echo $heading_title; ?></h2>
  <p><?php echo $text_payment_success ?></p>
  <div id="AmazonOrderDetail"></div>
  <?php echo $content_bottom; ?>
</div>
<script type="text/javascript"><!--
  new CBA.Widgets.OrderDetailsWidget ({
    merchantId: "<?php echo $merchant_id ?>",
    orderID: "<?php echo $amazon_order_id ?>"
  }).render ("AmazonOrderDetail");
//--></script>
<?php echo $footer; ?>