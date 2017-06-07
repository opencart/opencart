<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a id="insert"" data-toggle="tooltip" title="<?php echo $button_insert; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a> <a onclick="location = '<?php echo $clear; ?>'" data-toggle="tooltip" title="<?php echo $button_clear_cache; ?>" class="btn btn-default"><i class="fa fa-refresh"></i></a>
		<button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" id="btn-delete" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
		<button type="submit" form="form-seomanager" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
		 <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
 <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
	<div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $heading_title; ?></h3>
      </div>
    <div class="panel-body">
	<ul class="nav nav-tabs">
			<li class="active"><a href="#tab_seourl" data-toggle="tab"><?php echo $tab_seourl; ?></a></li>
            <li><a href="#tab_meta" data-toggle="tab"><?php echo $tab_meta; ?></a></li>
          </ul>
	<div class="tab-content">
	<div class="tab-pane active" id="tab_seourl">
	<div id="form-add" class="well" style="display:none;">
	<div class="row">
	<form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form-insert">
	<div class="col-sm-6">
	<div class="form-group">
	<label class="control-label" for="input-query">Query:</label>
	<input type="text" name="query" class="form-control" />
	</div>
	</div>
	<div class="col-sm-6">
	<div class="form-group">
	<label class="control-label" for="input-keyword">SEO Keyword:</label>
	<input type="text" name="keyword" class="form-control" />
	</div>
	<div class="pull-right">
	<a onclick="$('#form-insert').submit();" class="button"><span data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></span></a>
	<a onclick="fnCancel();" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
	<input type="hidden" name="url_alias_id" value="0">
	</div>
	</div>
	</form>
	</div>
	</div>
	<!-- FORM -->
	<form action="delete" method="post" id="form"></form>
	<form action="<?php echo $delete ?>" method="post" enctype="multipart/form-data" id="alias_form">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="center"><?php if ($sort == 'ua.query') { ?>
                <a href="<?php echo $sort_query; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_query; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_query; ?>"><?php echo $column_query; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'ua.keyword') { ?>
                <a href="<?php echo $sort_keyword; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_keyword; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_keyword; ?>"><?php echo $column_keyword; ?></a>
                <?php } ?></td>
                <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <?php if ($url_aliases) { ?>
            <?php foreach ($url_aliases as $url_alias) { ?>
            <tr class="tr<?php echo $url_alias['url_alias_id']; ?>">
              <td style="text-align: center;"><?php if ($url_alias['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $url_alias['url_alias_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $url_alias['url_alias_id']; ?>" />
                <?php } ?></td>
              <td class="left"><?php echo $url_alias['query']; ?></td>
              <td class="left"><?php echo $url_alias['keyword']; ?></td>
			  <td class="text-right"><a onclick="itemEdit(<?php echo $url_alias['url_alias_id']; ?>)" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></a></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="4"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
	  <div class="pagination"><?php echo $pagination; ?></div>
	  </div>
      
	<div class="tab-pane" id="tab_meta">
		<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-seomanager" class="form-horizontal">
			<ul class="nav nav-tabs">
			<li class="active"><a href="#tab_special" data-toggle="tab"><?php echo $tab_special; ?></a></li>
            <li><a href="#tab_bestseller" data-toggle="tab"><?php echo $tab_bestseller; ?></a></li>
			<li><a href="#tab_latest" data-toggle="tab"><?php echo $tab_latest; ?></a></li>
            <li><a href="#tab_mostviewed" data-toggle="tab"><?php echo $tab_mostviewed; ?></a></li>
            </ul>	
			<div class="tab-content">
			<div class="tab-pane active" id="tab_special">
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-h1_special"><?php echo $entry_html_h1; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="seomanager_html_h1_special" value="<?php echo $seomanager_html_h1_special; ?>" placeholder="<?php echo $entry_html_h1; ?>" id="input-seomanager-h1-special" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-meta-title-special"><?php echo $entry_meta_title; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="seomanager_meta_title_special" value="<?php echo $seomanager_meta_title_special; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-seomanager-meta-title-special" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-meta-description-special"><?php echo $entry_meta_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="seomanager_meta_description_special" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-seomanager-meta-description-special" class="form-control"><?php echo $seomanager_meta_description_special; ?></textarea>
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-meta-keyword-special"><?php echo $entry_meta_keyword; ?></label>
                  <div class="col-sm-10">
                    <textarea name="seomanager_meta_keyword_special" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-seomanager-meta-keyword-special" class="form-control"><?php echo $seomanager_meta_keyword_special; ?></textarea>
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-description-special"><?php echo $entry_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="seomanager_description_special" placeholder="<?php echo $entry_description; ?>" id="input-seomanager-description-special" class="form-control summernote"><?php echo $seomanager_description_special; ?></textarea>
                  </div>
                </div>
		</div>
		<div class="tab-pane" id="tab_bestseller">
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-h1_bestseller"><?php echo $entry_html_h1; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="seomanager_html_h1_bestseller" value="<?php echo $seomanager_html_h1_bestseller; ?>" placeholder="<?php echo $entry_html_h1; ?>" id="input-seomanager-h1-bestseller" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-meta-title-bestseller"><?php echo $entry_meta_title; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="seomanager_meta_title_bestseller" value="<?php echo $seomanager_meta_title_bestseller; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-seomanager-meta-title-bestseller" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-meta-description-bestseller"><?php echo $entry_meta_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="seomanager_meta_description_bestseller" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-seomanager-meta-description-bestseller" class="form-control"><?php echo $seomanager_meta_description_bestseller; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-meta-keyword-bestseller"><?php echo $entry_meta_keyword; ?></label>
                  <div class="col-sm-10">
                    <textarea name="seomanager_meta_keyword_bestseller" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-seomanager-meta-keyword-bestseller" class="form-control"><?php echo $seomanager_meta_keyword_bestseller; ?></textarea>
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-description-bestseller"><?php echo $entry_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="seomanager_description_bestseller" placeholder="<?php echo $entry_description; ?>" id="input-seomanager-description-bestseller" class="form-control summernote"><?php echo $seomanager_description_bestseller; ?></textarea>
                  </div>
                </div>
		</div>
		<div class="tab-pane" id="tab_latest">
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-h1_latest"><?php echo $entry_html_h1; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="seomanager_html_h1_latest" value="<?php echo $seomanager_html_h1_latest; ?>" placeholder="<?php echo $entry_html_h1; ?>" id="input-seomanager-h1-latest" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-meta-title-latest"><?php echo $entry_meta_title; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="seomanager_meta_title_latest" value="<?php echo $seomanager_meta_title_latest; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-seomanager-meta-title-latest" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-meta-description-latest"><?php echo $entry_meta_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="seomanager_meta_description_latest" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-seomanager-meta-description-latest" class="form-control"><?php echo $seomanager_meta_description_latest; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-meta-keyword-latest"><?php echo $entry_meta_keyword; ?></label>
                  <div class="col-sm-10">
                    <textarea name="seomanager_meta_keyword_latest" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-seomanager-meta-keyword-latest" class="form-control"><?php echo $seomanager_meta_keyword_latest; ?></textarea>
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-description-latest"><?php echo $entry_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="seomanager_description_latest" placeholder="<?php echo $entry_description; ?>" id="input-seomanager-description-latest" class="form-control summernote"><?php echo $seomanager_description_latest; ?></textarea>
                  </div>
                </div>
		</div>
		<div class="tab-pane" id="tab_mostviewed">
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-h1_mostviewed"><?php echo $entry_html_h1; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="seomanager_html_h1_mostviewed" value="<?php echo $seomanager_html_h1_mostviewed; ?>" placeholder="<?php echo $entry_html_h1; ?>" id="input-seomanager-h1-mostviewed" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-meta-title-mostviewed"><?php echo $entry_meta_title; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="seomanager_meta_title_mostviewed" value="<?php echo $seomanager_meta_title_mostviewed; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-seomanager-meta-title-mostviewed" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-meta-description-mostviewed"><?php echo $entry_meta_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="seomanager_meta_description_mostviewed" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-seomanager-meta-description-mostviewed" class="form-control"><?php echo $seomanager_meta_description_mostviewed; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-meta-keyword-mostviewed"><?php echo $entry_meta_keyword; ?></label>
                  <div class="col-sm-10">
                    <textarea name="seomanager_meta_keyword_mostviewed" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-seomanager-meta-keyword-mostviewed" class="form-control"><?php echo $seomanager_meta_keyword_mostviewed; ?></textarea>
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-seomanager-description-mostviewed"><?php echo $entry_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="seomanager_description_mostviewed" placeholder="<?php echo $entry_description; ?>" id="input-seomanager-description-mostviewed" class="form-control summernote"><?php echo $seomanager_description_mostviewed; ?></textarea>
                  </div>
                </div>
		</div>
	</div>
	</div>
	 </div>
      </form> 
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function itemEdit(url_alias_id) {
	$('input[name="query"]').val($('.tr'+url_alias_id+' td:eq(1)').text());
	$('input[name="keyword"]').val($('.tr'+url_alias_id+' td:eq(2)').text());
	$('input[name="url_alias_id"]').val(url_alias_id);
	$('#form-add').show();
	$('input[name="query"]').focus();
	return false;
}
function fnCancel() {
	$('#form-add').hide();
	$('input[name="query"]').val('');
	$('input[name="keyword"]').val('');
	$('input[name="url_alias_id"]').val('0');
	return false;
}

$('#insert').click(function() {
	fnCancel();
	$('#form-add').show();
	return false;
});

$(document).ready(function() {
	$('#btn-delete').click(function() {
		if (!confirm('Удаление невозможно отменить! Вы уверены, что хотите это сделать?')) {
			return false;
		} else {
		    $('#alias_form').submit();
		}
	});
});
//--></script>
<script type="text/javascript" src="view/javascript/summernote/summernote.js"></script>
  <link href="view/javascript/summernote/summernote.css" rel="stylesheet" />
  <script type="text/javascript" src="view/javascript/summernote/opencart.js"></script>
<?php echo $footer; ?>