<?php if (!isset($redirect)) { ?>
<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-left"><?php echo $column_name; ?></td>
        <td class="text-left"><?php echo $column_model; ?></td>
        <td class="text-right"><?php echo $column_quantity; ?></td>
        <td class="text-right"><?php echo $column_price; ?></td>
        <td class="text-right"><?php echo $column_total; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?>
          <?php if($product['recurring']) { ?>
          <br />
          <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
          <?php } ?></td>
        <td class="text-left"><?php echo $product['model']; ?></td>
        <td class="text-right"><?php echo $product['quantity']; ?></td>
        <td class="text-right">
          <?php if($product['original_price'] != $product['price'] ) { ?>
            <span class="text-decoration">
              <?php echo $product['original_price']; ?>
            </span>
          <br />
          <?php } ?>
          <span ><?php echo $product['price']; ?></span>
        </td>
        <td class="text-right"><?php echo $product['total']; ?></td>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td class="text-left"><?php echo $voucher['description']; ?></td>
        <td class="text-left"></td>
        <td class="text-right">1</td>
        <td class="text-right"><?php echo $voucher['amount']; ?></td>
        <td class="text-right"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot id="total">
      <?php foreach ($totals as $total) { ?>
      <?php if ($total['code'] != "sub_total") { ?>
      <tr class="<?php echo $total['class'] ?>">
        <td colspan="4" class="text-right"><?php echo $total['title']; ?>:</td>
        <td class="text-right"><?php echo $total['text']; ?></td>
      </tr>
      <?php } else { ?>
      <tr class="<?php echo $total['class'] ?>">
        <td colspan="4" class="text-right"><?php echo $total['title']; ?>:</td>
        <td class="text-right">
          <?php if($product['original_price'] != $product['price'] ) { ?>
          <span class="text-decoration">
            <?php echo $total['addin'] ?>
          </span>
          <br>
          <?php } ?>
          <span><?php echo $total['text']; ?></span>
        </td>
      </tr>
      <?php } ?>
      <?php } ?>
    </tfoot>
  </table>
</div>
<?php echo $payment; ?>

<!--The Xlight Guarantee-->
<div class="row gurantee">
  <div class="col-md-4 col-md-offset-8">
    <h4><b><?php echo $text_guarantee; ?></b></h4>
    <div class="panel panel-default">
      <!--Default panel contens-->
      <div class="panel-heading">
        <a href="javascript:void(0);"><?php echo $text_free_return; ?><i class="fa fa-plus pull-right"></i></a>
      </div>
      <div class="panel-body">
        <?php echo $text_free_return_content; ?>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-md-offset-8">
    <div class="panel panel-default">
      <!--Default panel contens-->
      <div class="panel-heading">
        <a href="javascript:void(0);"><?php echo $text_safe_secured; ?><i class="fa fa-plus pull-right"></i></a>
      </div>
      <div class="panel-body">
        <?php echo $text_safe_secured_content; ?>
      </div>
    </div>
  </div>
  <div class="col-md-4 col-md-offset-8">
    <div class="panel panel-default">
      <!--Default panel contens-->
      <div class="panel-heading">
        <a href="javascript:void(0);"><?php echo $text_instant_help; ?><i class="fa fa-plus pull-right"></i></a>
      </div>
      <div class="panel-body">
        <?php echo $text_instant_help_content; ?>
      </div>
    </div>
  </div>
</div>
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script>
<?php } ?>

<script type="text/javascript">
$(function () {

$('.gurantee .panel-heading > a').click(function () {
if($(this).find('i').hasClass('fa-plus')) {
$(this).parent().next('div.panel-body').fadeOut("normal");
$(this).find('i').removeClass('fa-plus');
$(this).find('i').addClass('fa-minus');
} else {
$(this).parent().next('div.panel-body').fadeIn("normal");
$(this).find('i').removeClass('fa-minus');
$(this).find('i').addClass('fa-plus');
}
});
});
</script>