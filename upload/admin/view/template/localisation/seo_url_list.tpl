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
  <?php if ($success) { ?>
  <div class="alert alert-success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><i class="icon-list"></i> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="btn"><i class="icon-plus"></i> <?php echo $button_insert; ?></a> <a onclick="$('#form').submit();" class="btn"><i class="icon-trash"></i> <?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="table table-striped table-bordered table-hover">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="left"><?php if ($sort == 'u.keyword') { ?>
                <a href="<?php echo $sort_keyword; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_keyword; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_keyword; ?>"><?php echo $column_keyword; ?></a>
                <?php } ?></td>
              <td class="left"><?php echo $column_link; ?></td>
              <td class="left"><?php echo $column_type; ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td class="left"><input type="text" name="filter_keyword" value="<?php echo $filter_keyword; ?>" /></td>
              <td></td>
              <td class="left"><select name="filter_type">
                  <option value=""></option>
                  <?php foreach($types as $key => $value) { ?>
                  <?php if ($key == $filter_type) { ?>
                  <option value="<?php echo $key; ?>" selected="selected"><?php echo $value; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
              <td align="right"><a onclick="filter();" class="btn"><i class="icon-search"></i> <?php echo $button_filter; ?></a></td>
            </tr>
            <?php if ($seo_urls) { ?>
            <?php foreach ($seo_urls as $seo_url) { ?>
            <tr>
              <td style="text-align: center;"><?php if ($seo_url['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $seo_url['url_alias_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $seo_url['url_alias_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $seo_url['keyword']; ?></td>
              <td class="left">
                <?php if ($seo_url['link']) { ?>
                <a href="<?php echo $seo_url['link']; ?>"><?php echo $seo_url['name']; ?></a>
                <?php } else { ?>
                <?php echo $seo_url['name']; ?>
                <?php } ?></td>
              <td class="left"><?php echo $seo_url['type']; ?></td>
              <td class="right"><?php foreach ($seo_url['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="5"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
      <div class="results"><?php echo $results; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=localisation/seo_url&token=<?php echo $token; ?>';
	
	var filter_keyword = $('input[name=\'filter_keyword\']').val();
	
	if (filter_keyword) {
		url += '&filter_keyword=' + encodeURIComponent(filter_keyword);
	}
	
	var filter_type = $('select[name=\'filter_type\']').val();
	
	if (filter_type) {
		url += '&filter_type=' + encodeURIComponent(filter_type);
	}
	
	location = url;
}
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();

		return false;
	}
});
//--></script>
<?php echo $footer; ?>