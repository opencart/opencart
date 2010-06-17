<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
  <div class="top">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center">
      <h1><?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="middle"><b><?php echo $text_critea; ?></b>
    <div id="content_search" style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-top: 3px; margin-bottom: 10px;">
      <table>
        <tr>
          <td><?php echo $entry_search; ?></td>
          <td><?php if ($keyword) { ?>
            <input type="text" value="<?php echo $keyword; ?>" id="keyword" />
            <?php } else { ?>
            <input type="text" value="<?php echo $text_keyword; ?>" id="keyword" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" />
            <?php } ?>
            <select id="category_id">
              <option value="0"><?php echo $text_category; ?></option>
              <?php foreach ($categories as $category) { ?>
              <?php if ($category['category_id'] == $category_id) { ?>
              <option value="<?php echo $category['category_id']; ?>" selected="selected"><?php echo $category['name']; ?></option>
              <?php } else { ?>
              <option value="<?php echo $category['category_id']; ?>"><?php echo $category['name']; ?></option>
              <?php } ?>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td colspan="2"><?php if ($description) { ?>
            <input type="checkbox" name="description" id="description" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="description" id="description" />
            <?php } ?>
            <?php echo $entry_description; ?></td>
        </tr>
		<tr>
          <td colspan="2"><?php if ($model) { ?>
            <input type="checkbox" name="model" id="model" checked="checked" />
            <?php } else { ?>
            <input type="checkbox" name="model" id="model" />
            <?php } ?>
            <?php echo $entry_model; ?></td>
        </tr>
      </table>
    </div>
    <div class="buttons">
      <table>
        <tr>
          <td align="right"><a onclick="contentSearch();" class="button"><span><?php echo $button_search; ?></span></a></td>
        </tr>
      </table>
    </div>
    <div class="heading"><?php echo $text_search; ?></div>
    <?php if (isset($products)) { ?>
    <div class="sort">
      <div class="div1">
        <select name="sort" onchange="location = this.value">
          <?php foreach ($sorts as $sorts) { ?>
          <?php if (($sort . '-' . $order) == $sorts['value']) { ?>
          <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
          <?php } else { ?>
          <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
          <?php } ?>
          <?php } ?>
        </select>
      </div>
      <div class="div2"><?php echo $text_sort; ?></div>
    </div>
    <table class="list">
      <?php for ($i = 0; $i < sizeof($products); $i = $i + 4) { ?>
      <tr>
        <?php for ($j = $i; $j < ($i + 4); $j++) { ?>
        <td width="25%"><?php if (isset($products[$j])) { ?>
          <a href="<?php echo $products[$j]['href']; ?>"><img src="<?php echo $products[$j]['thumb']; ?>" title="<?php echo $products[$j]['name']; ?>" alt="<?php echo $products[$j]['name']; ?>" /></a><br />
          <a href="<?php echo $products[$j]['href']; ?>"><?php echo $products[$j]['name']; ?></a><br />
          <span style="color: #999; font-size: 11px;"><?php echo $products[$j]['model']; ?></span><br />
          <?php if ($display_price) { ?>
          <?php if (!$products[$j]['special']) { ?>
          <span style="color: #900; font-weight: bold;"><?php echo $products[$j]['price']; ?></span>
          <?php } else { ?>
          <span style="color: #900; font-weight: bold; text-decoration: line-through;"><?php echo $products[$j]['price']; ?></span> <span style="color: #F00;"><?php echo $products[$j]['special']; ?></span>
          <?php } ?>
		  <a class="button_add_small" href="<?php echo $products[$j]['add']; ?>" title="<?php echo $button_add_to_cart; ?>" >&nbsp;</a>
          <?php } ?>
          <br />
          <?php if ($products[$j]['rating']) { ?>
          <img src="catalog/view/theme/default/image/stars_<?php echo $products[$j]['rating'] . '.png'; ?>" alt="<?php echo $products[$j]['stars']; ?>" />
          <?php } ?>
          <?php } ?></td>
        <?php } ?>
      </tr>
      <?php } ?>
    </table>
    <div class="pagination"><?php echo $pagination; ?></div>
    <?php } else { ?>
    <div style="background: #F7F7F7; border: 1px solid #DDDDDD; padding: 10px; margin-top: 3px; margin-bottom: 15px;"><?php echo $text_empty; ?></div>
    <?php }?>
  </div>
  <div class="bottom">
    <div class="left"></div>
    <div class="right"></div>
    <div class="center"></div>
  </div>
</div>
<script type="text/javascript"><!--
$('#content_search input').keydown(function(e) {
	if (e.keyCode == 13) {
		contentSearch();
	}
});

function contentSearch() {
	url = 'index.php?route=product/search';
	
	var keyword = $('#keyword').attr('value');
	
	if (keyword) {
		url += '&keyword=' + encodeURIComponent(keyword);
	}

	var category_id = $('#category_id').attr('value');
	
	if (category_id) {
		url += '&category_id=' + encodeURIComponent(category_id);
	}
	
	if ($('#description').attr('checked')) {
		url += '&description=1';
	}
	
	if ($('#model').attr('checked')) {
		url += '&model=1';
	}

	location = url;
}
//--></script>
<?php echo $footer; ?> 