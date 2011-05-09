<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/category.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_name; ?></td>
            <td><?php foreach ($languages as $language) { ?>
              <input type="text" name="menu_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($menu_description[$language['language_id']]) ? $menu_description[$language['language_id']]['name'] : ''; ?>" />
              <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
              <?php if (isset($error_name[$language['language_id']])) { ?>
              <span class="error"><?php echo $error_name[$language['language_id']]; ?></span><br />
              <?php } ?>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_store; ?></td>
            <td><select name="store_id">
                <option value="0"><?php echo $text_default; ?></option>
                <?php foreach ($stores as $store) { ?>
                <?php if ($store['store_id'] == $store_id) { ?>
                <option value="<?php echo $store['store_id']; ?>" selected="selected"><?php echo $store['name']; ?></option>
                <?php } else { ?>
                <option value="<?php echo $store['store_id']; ?>"><?php echo $store['name']; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_link; ?></td>
            <td><input type="text" name="link" value="<?php echo $link; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_column; ?></td>
            <td><input type="text" name="column" value="<?php echo $column; ?>" size="1" />
              <?php if ($error_column) { ?>
              <span class="error"><?php echo $error_column; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="sort_order" value="<?php echo $sort_order; ?>" size="1" /></td>
          </tr>
        </table>
        <table id="link" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_name; ?></td>
              <td class="left"><?php echo $entry_heading; ?></td>
              <td class="left"><?php echo $entry_link; ?></td>
              <td class="right"><?php echo $entry_sort_order; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $link_row = 0; ?>
          <?php foreach ($menu_links as $menu_link) { ?>
          <tbody id="link-row<?php echo $link_row; ?>">
            <tr>
              <td class="left"><?php foreach ($languages as $language) { ?>
                <input type="text" name="menu_link[<?php echo $link_row; ?>][menu_link_description][<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($menu_link['menu_link_description'][$language['language_id']]) ? $menu_link['menu_link_description'][$language['language_id']]['name'] : ''; ?>" />
                <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
                <?php if (isset($error_menu_link[$link_row][$language['language_id']])) { ?>
                <span class="error"><?php echo $error_menu_link[$link_row][$language['language_id']]; ?></span>
                <?php } ?>
                <?php } ?></td>
              <td class="left"><?php if (isset($menu_link['heading']) && $menu_link['heading']) { ?>
                <input type="checkbox" name="menu_link[<?php echo $link_row; ?>][heading]" value="1" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="menu_link[<?php echo $link_row; ?>][heading]" value="1" />
                <?php } ?></td>
              <td class="left"><input type="text" name="menu_link[<?php echo $link_row; ?>][link]" value="<?php echo $menu_link['link']; ?>" /></td>
              <td class="right"><input type="text" name="menu_link[<?php echo $link_row; ?>][sort_order]" value="<?php echo $menu_link['sort_order']; ?>" size="1" /></td>
              <td class="left"><a onclick="$('#link-row<?php echo $link_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
            </tr>
          </tbody>
          <?php $link_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="4"></td>
              <td class="left"><a onclick="addLink();" class="button"><span><?php echo $button_add_link; ?></span></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var link_row = <?php echo $link_row; ?>;

function addLink() {
    html  = '<tbody id="link-row' + link_row + '">';
	html += '<tr>';
    html += '<td class="left">';
	<?php foreach ($languages as $language) { ?>
	html += '<input type="text" name="menu_link[' + link_row + '][menu_link_description][<?php echo $language['language_id']; ?>][name]" value="" /> <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />';
    <?php } ?>
	html += '</td>';	
	html += '<td class="left"><input type="checkbox" name="menu_link[' + link_row + '][heading]" value="1" /></td>';	
	html += '<td class="left"><input type="text" name="menu_link[' + link_row + '][link]" value="" /></td>';
	html += '<td class="right"><input type="text" name="menu_link[' + link_row + '][sort_order]" value="0" size="1" /></td>';
	html += '<td class="left"><a onclick="$(\'#link-row' + link_row  + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
	html += '</tbody>'; 
	
	$('#link tfoot').before(html);
	
	link_row++;
}
//--></script> 
<?php echo $footer; ?>