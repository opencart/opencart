<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?><?php echo $breadcrumb['separator']; ?>
    <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a><?php } ?>
  </div>

  <div class="box">
    <div class="heading">
      <h1><?php echo $heading_title; ?></h1>

      <div class="buttons">
        <a class="button link-button"><?php echo $button_link; ?></a>
        <a class="button" onclick="location = '<?php echo $href_return; ?>';"><?php echo $button_return; ?></a>
      </div>
    </div>

    <div class="content">

      <?php if ($bulk_linking_status) { ?><p><?php echo $text_load_listings_help ?></p>

      <p id="text">
        <?php if ($marketplace_processing) { ?><?php echo $text_loading_listings ?>
        <img src="view/image/loading.gif" class="loading" style="padding-left: 5px;"/><?php } else { ?><?php echo $text_load_listings ?>
        <a class="button-load-listings button" href="<?php echo $href_load_listings ?>"><?php echo $button_load ?></a>

        <?php if ($unlinked_products) { ?>

      <table class="list">
        <thead>
        <tr>
          <td></td>
          <td class="center" colspan="4"><?php echo $text_amazon ?></td>
          <td class="center" colspan="4"><?php echo $text_local ?></td>
        </tr>
        <tr>
          <td class="center">
            <input type="checkbox" class="master-checkbox"/>
          </td>
          <td class="left"><?php echo $column_asin ?></td>
          <td class="left"><?php echo $column_sku ?></td>
          <td class="left"><?php echo $column_quantity ?></td>
          <td class="right"><?php echo $column_price ?></td>
          <td class="left"><?php echo $column_name ?></td>
          <td class="left"><?php echo $column_sku ?></td>
          <td class="left"><?php echo $column_quantity ?></td>
          <td class="left"><?php echo $column_combination ?></td>
        </tr>
        </thead>
        <tbody>
        <?php $row = 0; ?><?php foreach ($unlinked_products as $product) { ?><?php $row++ ?>
        <tr>
          <td class="center">
            <input type="checkbox" class="link-checkbox"/>
          </td>
          <td class="left">
            <a href="<?php echo $product['href_amazon'] ?>" target="_blank"><?php echo $product['asin'] ?></a></td>
          <td class="left"><?php echo $product['amazon_sku'] ?></td>
          <td class="left"><?php echo $product['amazon_quantity'] ?></td>
          <td class="right"><?php echo $product['amazon_price'] ?></td>
          <td class="left">
            <a href="<?php echo $product['href_product'] ?>" target="_blank"><?php echo $product['name'] ?></a></td>
          <td class="left"><?php echo $product['sku'] ?></td>
          <td class="left"><?php echo $product['quantity'] ?></td>
          <td class="left"><?php echo $product['combination'] ?></td>

          <input type="hidden" name="link[<?php echo $row ?>][amazon_sku]" value="<?php echo $product['amazon_sku'] ?>"/>
          <input type="hidden" name="link[<?php echo $row ?>][product_id]" value="<?php echo $product['product_id'] ?>"/>
          <input type="hidden" name="link[<?php echo $row ?>][var]" value="<?php echo $product['var'] ?>"/>
        </tr>

        <?php } ?>
        </tbody>
      </table>

      <div class="pagination"><?php echo $pagination; ?></div>
      <?php } ?><?php } ?></p><?php } else { ?>
      <div class="warning"><?php echo $error_bulk_linking_not_allowed ?></div>
      <?php } ?>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
$('.button-load-listings').live('click', function () {

  var paragraph = $(this).parent();
  var oldText = paragraph.html();

  $.ajax({
    url: $(this).attr('href'),
    dataType: 'json',
    beforeSend: function () {
      paragraph.html('<img src="view/image/loading.gif" class="loading" />');
      $('.success, .warning').remove();
    },
    success: function (json) {
      if (json['status'] == 1) {
        paragraph.before('<div class="success">' + json['message'] + '</div>');
        paragraph.html('<?php echo $text_loading_listings ?> <img src="view/image/loading.gif" class="loading" style="padding-left: 5px;" />');
      } else {
        paragraph.before('<div class="warning">' + json['message'] + '</div>');
        paragraph.html(oldText);
      }
    },
    error: function (xhr, ajaxOptions, thrownError) {
      alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
    }
  });

  return false;
});

$('.master-checkbox').click(function () {
  if ($(this).is(':checked')) {
    $('.link-checkbox').attr('checked', 'checked');
  } else {
    $('.link-checkbox').removeAttr('checked');
  }
});

$('.link-button').click(function () {
  $.ajax({
    url: '<?php echo html_entity_decode($href_do_bulk_linking) ?>',
    dataType: 'json',
    type: 'POST',
    data: $('.link-checkbox:checked').parent().siblings('input[type="hidden"]').serialize(),

    success: function (json) {
      document.location.reload(true);
    }
  });
});

//--></script> 