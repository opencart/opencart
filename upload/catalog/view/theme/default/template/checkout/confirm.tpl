<?php if (!isset($redirect)) { ?>
<div class="row-fluid">
  <div class="span12">
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <td class="left"><?php echo $column_name; ?></td>
          <td class="left"><?php echo $column_model; ?></td>
          <td class="right"><?php echo $column_quantity; ?></td>
          <td class="right"><?php echo $column_price; ?></td>
          <td class="right"><?php echo $column_total; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($products as $product) { ?>
        <tr>
          <td class="left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
            <?php foreach ($product['option'] as $option) { ?>
            <br />
            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
            <?php } ?></td>
          <td class="left"><?php echo $product['model']; ?></td>
          <td class="right"><?php echo $product['quantity']; ?></td>
          <td class="right"><?php echo $product['price']; ?></td>
          <td class="right"><?php echo $product['total']; ?></td>
        </tr>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td class="left"><?php echo $voucher['description']; ?></td>
          <td class="left"></td>
          <td class="right">1</td>
          <td class="right"><?php echo $voucher['amount']; ?></td>
          <td class="right"><?php echo $voucher['amount']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>
<div class="row-fluid">
  <div class="span4 offset8">
    <table class="table table-bordered">
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td class="right"><strong><?php echo $total['title']; ?>:</strong></td>
        <td class="right"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </table>
  </div>
</div>
<div class="row-fluid">
  <div class="span12"> <?php echo $payment; ?></div>
</div>
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>
