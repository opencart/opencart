<?php echo $header; ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<div class="box">
  <div class="left"></div>
  <div class="right"></div>
  <div class="heading">
    <h1 style="background: url('view/image/setting.png') 2px 9px no-repeat;"><?php echo $heading_title; ?></h1>
    <div class="buttons"><?php echo $text_edit_store; ?>
      <select onchange="location = this.value">
        <?php foreach ($stores as $store) { ?>
        <?php if ($store['store_id'] == $store_id) { ?>
        <option value="<?php echo $store['href']; ?>" selected="selected"><?php echo $store['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $store['href']; ?>"><?php echo $store['name']; ?></option>
        <?php } ?>
        <?php } ?>
      </select>
      &nbsp;&nbsp; <a onclick="location = '<?php echo $insert; ?>'" class="button"><span><?php echo $button_add_store; ?></span></a>
      <?php if ($delete) { ?>
      &nbsp;<a onclick="location = '<?php echo $delete; ?>'" class="button"><span><?php echo $button_delete_store; ?></span></a>
      <?php } ?>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <div id="tabs" class="htabs"><a tab="#tab_general"><?php echo $tab_general; ?></a><a tab="#tab_store"><?php echo $tab_store; ?></a><a tab="#tab_local"><?php echo $tab_local; ?></a><a tab="#tab_option"><?php echo $tab_option; ?></a><a tab="#tab_image"><?php echo $tab_image; ?></a><a tab="#tab_server"><?php echo $tab_server; ?></a></div>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <div id="tab_general">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><input type="text" name="name" value="<?php echo $name; ?>" size="40" />
              <?php if ($error_name) { ?>
              <span class="error"><?php echo $error_name; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_url; ?></td>
            <td><input type="text" name="url" value="<?php echo $url; ?>" size="40" />
              <?php if ($error_url) { ?>
              <span class="error"><?php echo $error_url; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </div>
      <div id="tab_store">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_title; ?></td>
            <td><input type="text" name="title" value="<?php echo $title; ?>" />
              <?php if ($error_title) { ?>
              <span class="error"><?php echo $error_title; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_meta_description; ?></td>
            <td><textarea name="meta_description" cols="40" rows="5"><?php echo $meta_description; ?></textarea></td>
          </tr>
          <tr>
            <td><?php echo $entry_template; ?></td>
            <td><select name="template" onchange="$('#template').load('index.php?route=setting/store/template&token=<?php echo $token; ?>&template=' + encodeURIComponent(this.value));">
                <?php foreach ($templates as $templates) { ?>
                <?php if ($templates == $template) { ?>
                <option value="<?php echo $templates; ?>" selected="selected"><?php echo $templates; ?></option>
                <?php } else { ?>
                <option value="<?php echo $templates; ?>"><?php echo $templates; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td></td>
            <td id="template"></td>
          </tr>
        </table>
        <br />
        <div id="languages" class="htabs">
          <?php foreach ($languages as $language) { ?>
          <a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
          <?php } ?>
        </div>
        <?php foreach ($languages as $language) { ?>
        <div id="language<?php echo $language['language_id']; ?>">
          <table class="form">
            <tr>
              <td><?php echo $entry_description; ?></td>
              <td><textarea name="store_description[<?php echo $language['language_id']; ?>][description]" id="description<?php echo $language['language_id']; ?>"><?php echo isset($store_description[$language['language_id']]) ? $store_description[$language['language_id']]['description'] : ''; ?></textarea></td>
            </tr>
          </table>
        </div>
        <?php } ?>
      </div>
      <div id="tab_local">
        <table class="form">
          <tr>
            <td><?php echo $entry_country; ?></td>
            <td><select name="country_id" id="country" onchange="$('#zone').load('index.php?route=setting/store/zone&token=<?php echo $token; ?>&country_id=' + this.value + '&zone_id=<?php echo $zone_id; ?>');">
                <?php foreach ($countries as $country) { ?>
                <?php if ($country['country_id'] == $country_id) { ?>
                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_zone; ?></td>
            <td><select name="zone_id" id="zone">
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_language; ?></td>
            <td><select name="language">
                <?php foreach ($languages as $language) { ?>
                <?php if ($language['code'] == $language_code) { ?>
                <option value="<?php echo $language['code']; ?>" selected="selected"><?php echo $language['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $language['code']; ?>"><?php echo $language['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_currency; ?></td>
            <td><select name="currency">
                <?php foreach ($currencies as $currency) { ?>
                <?php if ($currency['code'] == $currency_code) { ?>
                <option value="<?php echo $currency['code']; ?>" selected="selected"><?php echo $currency['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $currency['code']; ?>"><?php echo $currency['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
        </table>
      </div>
      <div id="tab_option">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_catalog_limit; ?></td>
            <td><input type="text" name="catalog_limit" value="<?php echo $catalog_limit; ?>" size="3" />
              <?php if ($error_catalog_limit) { ?>
              <span class="error"><?php echo $error_catalog_limit; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_tax; ?></td>
            <td><?php if ($tax) { ?>
              <input type="radio" name="tax" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="tax" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="tax" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="tax" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_customer_group; ?></td>
            <td><select name="customer_group_id">
                <?php foreach ($customer_groups as $customer_group) { ?>
                <?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                <option value="<?php echo $customer_group['customer_group_id']; ?>" selected="selected"><?php echo $customer_group['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $customer_group['customer_group_id']; ?>"><?php echo $customer_group['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_customer_price; ?></td>
            <td><?php if ($customer_price) { ?>
              <input type="radio" name="customer_price" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="customer_price" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="customer_price" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="customer_price" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_customer_approval; ?></td>
            <td><?php if ($customer_approval) { ?>
              <input type="radio" name="customer_approval" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="customer_approval" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="customer_approval" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="customer_approval" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_guest_checkout; ?></td>
            <td><?php if ($guest_checkout) { ?>
              <input type="radio" name="guest_checkout" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="guest_checkout" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="guest_checkout" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="guest_checkout" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_account; ?></td>
            <td><select name="account_id">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($informations as $information) { ?>
                <?php if ($information['information_id'] == $account_id) { ?>
                <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_checkout; ?></td>
            <td><select name="checkout_id">
                <option value="0"><?php echo $text_none; ?></option>
                <?php foreach ($informations as $information) { ?>
                <?php if ($information['information_id'] == $checkout_id) { ?>
                <option value="<?php echo $information['information_id']; ?>" selected="selected"><?php echo $information['title']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $information['information_id']; ?>"><?php echo $information['title']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_stock_display; ?></td>
            <td><?php if ($stock_display) { ?>
              <input type="radio" name="stock_display" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="stock_display" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="stock_display" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="stock_display" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_stock_checkout; ?></td>
            <td><?php if ($stock_checkout) { ?>
              <input type="radio" name="stock_checkout" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="stock_checkout" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="stock_checkout" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="stock_checkout" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_order_status; ?></td>
            <td><select name="order_status_id">
                <?php foreach ($order_statuses as $order_status) { ?>
                <?php if ($order_status['order_status_id'] == $order_status_id) { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
		  <tr>
            <td><?php echo $entry_cart_weight; ?></td>
            <td><?php if ($cart_weight) { ?>
              <input type="radio" name="cart_weight" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="cart_weight" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="cart_weight" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="cart_weight" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
        </table>
      </div>
      <div id="tab_image">
        <table class="form">
          <tr>
            <td><?php echo $entry_logo; ?></td>
            <td><input type="hidden" name="logo" value="<?php echo $logo; ?>" id="logo" />
              <img src="<?php echo $preview_logo; ?>" alt="" id="preview_logo" class="image" onclick="image_upload('logo', 'preview_logo');" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_icon; ?></td>
            <td><input type="hidden" name="icon" value="<?php echo $icon; ?>" id="icon" />
              <img src="<?php echo $preview_icon; ?>" alt="" id="preview_icon" class="image" onclick="image_upload('icon', 'preview_icon');" /></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_image_thumb; ?></td>
            <td><input type="text" name="image_thumb_width" value="<?php echo $image_thumb_width; ?>" size="3" />
              x
              <input type="text" name="image_thumb_height" value="<?php echo $image_thumb_height; ?>" size="3" />
              <?php if ($error_image_thumb) { ?>
              <span class="error"><?php echo $error_image_thumb; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_image_popup; ?></td>
            <td><input type="text" name="image_popup_width" value="<?php echo $image_popup_width; ?>" size="3" />
              x
              <input type="text" name="image_popup_height" value="<?php echo $image_popup_height; ?>" size="3" />
              <?php if ($error_image_popup) { ?>
              <span class="error"><?php echo $error_image_popup; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_image_category; ?></td>
            <td><input type="text" name="image_category_width" value="<?php echo $image_category_width; ?>" size="3" />
              x
              <input type="text" name="image_category_height" value="<?php echo $image_category_height; ?>" size="3" />
              <?php if ($error_image_category) { ?>
              <span class="error"><?php echo $error_image_category; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_image_product; ?></td>
            <td><input type="text" name="image_product_width" value="<?php echo $image_product_width; ?>" size="3" />
              x
              <input type="text" name="image_product_height" value="<?php echo $image_product_height; ?>" size="3" />
              <?php if ($error_image_product) { ?>
              <span class="error"><?php echo $error_image_product; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_image_additional; ?></td>
            <td><input type="text" name="image_additional_width" value="<?php echo $image_additional_width; ?>" size="3" />
              x
              <input type="text" name="image_additional_height" value="<?php echo $image_additional_height; ?>" size="3" />
              <?php if ($error_image_additional) { ?>
              <span class="error"><?php echo $error_image_additional; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_image_related; ?></td>
            <td><input type="text" name="image_related_width" value="<?php echo $image_related_width; ?>" size="3" />
              x
              <input type="text" name="image_related_height" value="<?php echo $image_related_height; ?>" size="3" />
              <?php if ($error_image_related) { ?>
              <span class="error"><?php echo $error_image_related; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_image_cart; ?></td>
            <td><input type="text" name="image_cart_width" value="<?php echo $image_cart_width; ?>" size="3" />
              x
              <input type="text" name="image_cart_height" value="<?php echo $image_cart_height; ?>" size="3" />
              <?php if ($error_image_cart) { ?>
              <span class="error"><?php echo $error_image_cart; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </div>
      <div id="tab_server">
        <table class="form">
          <tr>
            <td><?php echo $entry_ssl; ?></td>
            <td><?php if ($ssl) { ?>
              <input type="radio" name="ssl" value="1" checked="checked" />
              <?php echo $text_yes; ?>
              <input type="radio" name="ssl" value="0" />
              <?php echo $text_no; ?>
              <?php } else { ?>
              <input type="radio" name="ssl" value="1" />
              <?php echo $text_yes; ?>
              <input type="radio" name="ssl" value="0" checked="checked" />
              <?php echo $text_no; ?>
              <?php } ?></td>
          </tr>
        </table>
      </div>
    </form>
  </div>
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script>
<script type="text/javascript"><!--
<?php foreach ($languages as $language) { ?>
CKEDITOR.replace('description<?php echo $language['language_id']; ?>', {
	filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
	filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
});
<?php } ?>	
//--></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.draggable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.resizable.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/ui.dialog.js"></script>
<script type="text/javascript" src="view/javascript/jquery/ui/external/bgiframe/jquery.bgiframe.js"></script>
<script type="text/javascript"><!--
function image_upload(field, preview) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>',
					type: 'POST',
					data: 'image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + preview).replaceWith('<img src="' + data + '" alt="" id="' + preview + '" class="image" onclick="image_upload(\'' + field + '\', \'' + preview + '\');" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 700,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script>
<script type="text/javascript"><!--
$('#template').load('index.php?route=setting/store/template&token=<?php echo $token; ?>&template=' + encodeURIComponent($('select[name=\'template\']').attr('value')));

$('#zone').load('index.php?route=setting/store/zone&token=<?php echo $token; ?>&country_id=<?php echo $country_id; ?>&zone_id=<?php echo $zone_id; ?>');
//--></script>
<script type="text/javascript"><!--
$.tabs('#tabs a');
$.tabs('#languages a');
//--></script>
<?php echo $footer; ?>