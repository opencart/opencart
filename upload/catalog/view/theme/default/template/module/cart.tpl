<div id="module_cart" class="box">
  <div class="top"><img src="catalog/view/theme/default/image/basket.png" alt="" /><?php echo $heading_title; ?></div>
  <div class="middle">
    <?php if ($products) { ?>
    <table cellpadding="2" cellspacing="0" style="width: 100%;">
      <?php foreach ($products as $product) { ?>
      <tr>
        <td align="left" valign="top" style="width:1px"><span class="cart_remove" id="remove_<?php echo $product['key']; ?>">&nbsp;</span></td><td valign="top" align="right" style="width:1px"><?php echo $product['quantity']; ?>&nbsp;x&nbsp;</td>
        <td align="left" valign="top"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <div>
            <?php foreach ($product['option'] as $option) { ?>
            - <small style="color: #999;"><?php echo $option['name']; ?> <?php echo $option['value']; ?></small><br />
            <?php } ?>
          </div></td>
      </tr>
      <?php } ?>
    </table>
    <br />
    <?php if ($display_price) { ?>
	<table cellpadding="0" cellspacing="0" align="right" style="display:inline-block;">
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td align="right"><span class="cart_module_total"><b><?php echo $total['title']; ?></b></span></td>
        <td align="right"><span class="cart_module_total"><?php echo $total['text']; ?></span></td>
      </tr>
      <?php } ?>
    </table>
	<?php } ?>
    <div style="padding-top:5px;text-align:center;clear:both;"><a href="<?php echo $view; ?>"><?php echo $text_view; ?></a> | <a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></div>
    <?php } else { ?>
    <div style="text-align: center;"><?php echo $text_empty; ?></div>
    <?php } ?>
  </div>
  <div class="bottom">&nbsp;</div>
</div>
<?php if ($ajax) { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajax_add.js"></script>
<?php } ?>

<script type="text/javascript"><!--

function getUrlParam(name) {
  var name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
  var regexS = "[\\?&]"+name+"=([^&#]*)";
  var regex = new RegExp(regexS);
  var results = regex.exec(window.location.href);
  if (results == null)
    return "";
  else
    return results[1];
}

$(document).ready(function () {
	$('.cart_remove').live('click', function () {
		if (!confirm('<?php echo $text_confirm; ?>')) {
			return false;
		}
		$(this).removeClass('cart_remove').addClass('cart_remove_loading');
		$.ajax({
			type: 'post',
			url: 'index.php?route=module/cart/callback',
			dataType: 'html',
			data: 'remove=' + this.id,
			success: function (html) {
				$('#module_cart .middle').html(html);
				if (getUrlParam('route').indexOf('checkout') != -1) {
					window.location.reload();
				}
			}
		});
	});
});
//--></script>