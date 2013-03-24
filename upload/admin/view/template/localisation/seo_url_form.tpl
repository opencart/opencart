<?php echo $header; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-error"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="btn"><i class="icon-ok"></i> <?php echo $button_save; ?></a> <a href="<?php echo $cancel; ?>" class="btn"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><span class="required">*</span> <?php echo $entry_keyword; ?></td>
            <td><input type="text" name="keyword" value="<?php echo $keyword; ?>" size="100" />
              <?php if ($error_keyword) { ?>
              <span class="error"><?php echo $error_keyword; ?></span>
              <?php } ?></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_type; ?></td>
            <td class="left"><select name="type">
                <?php foreach($types as $key => $value) { ?>
                <?php if ($key == $type) { ?>
                <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
                <?php } else { ?>
                <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php } ?>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><span class="required">*</span> <?php echo $entry_route; ?></td>
            <td><input type="hidden" name="route" value="<?php echo $route; ?>" />
              <input type="text" id="name" name="" value="<?php echo $name; ?>" size="100" />
              <?php if ($error_route) { ?>
              <span class="error"><?php echo $error_route; ?></span>
              <?php } ?></td>
          </tr>
        </table>
      </form>
    </div>
  </div>
</div> 
<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';

		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');
				
				currentCategory = item.category;
			}
			
			self._renderItem(ul, item);
		});
	}
});

$('input#name').autocomplete({
	minLength: 0,
	delay: 500,
	source: function(request, response) {
		var url, name;
		var type = $('select[name=\'type\']').val();

		if (type == 'product_id') {
			url = 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term);
			name = 'name';
		} else if (type == 'category_id') {
			url = 'index.php?route=catalog/category/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term);
			name = 'name';
		} else if (type == 'information_id') {
			url = 'index.php?route=catalog/information/autocomplete&token=<?php echo $token; ?>&filter_title=' +  encodeURIComponent(request.term);
			name = 'title';
		} else if (type == 'manufacturer_id') {
			url = 'index.php?route=catalog/manufacturer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term);
			name = 'name';
		}

		if (!url) {
			$('input[name=\'route\']').val(request.term);
			return false;
		}

		$.ajax({
			url: url,
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					if (!(type in item) || !(name in item)) {
						return false;
					}

					return {
						label: item[name],
						value: item[type]
					}
				}));
			}
		});
	}, 
	focus: function( event, ui ) {
		$('input[name=\'route\']').val(ui.item.value);
		return false;
	},
	select: function(event, ui) {
		$('input#name').val(ui.item.label);
		$('input[name=\'route\']').val(ui.item.value);

		return false;
	},
	focus: function(event, ui) {
		return false;
	}
});
--></script>
<?php echo $footer; ?>