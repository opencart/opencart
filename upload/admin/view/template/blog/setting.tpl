<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-setting" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-setting" class="form-horizontal">
          <ul class="nav nav-tabs">
			<li class="active"><a href="#tab-general" data-toggle="tab"><?php echo $tab_general; ?></a></li>
            <li><a href="#tab-option" data-toggle="tab"><?php echo $tab_option; ?></a></li>
            <li><a href="#tab-image" data-toggle="tab"><?php echo $tab_image; ?></a></li>
          </ul>
          <div class="tab-content">
		  <div class="tab-pane active" id="tab-general">
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="configblog_name" value="<?php echo $configblog_name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-h1"><?php echo $entry_html_h1; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="configblog_html_h1" value="<?php echo $configblog_html_h1; ?>" placeholder="<?php echo $entry_html_h1; ?>" id="input-h1" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-meta-title"><?php echo $entry_meta_title; ?></label>
                  <div class="col-sm-10">
                    <input type="text" name="configblog_meta_title" value="<?php echo $configblog_meta_title; ?>" placeholder="<?php echo $entry_meta_title; ?>" id="input-meta-title" class="form-control" />
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label" for="input-meta-description"><?php echo $entry_meta_description; ?></label>
                  <div class="col-sm-10">
                    <textarea name="configblog_meta_description" rows="5" placeholder="<?php echo $entry_meta_description; ?>" id="input-meta-description" class="form-control"><?php echo $configblog_meta_description; ?></textarea>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label" for="input-meta-keyword"><?php echo $entry_meta_keyword; ?></label>
                  <div class="col-sm-10">
                    <textarea name="configblog_meta_keyword" rows="5" placeholder="<?php echo $entry_meta_keyword; ?>" id="input-meta-keyword" class="form-control"><?php echo $configblog_meta_keyword; ?></textarea>
                  </div>
                </div>
		  </div>
            <div class="tab-pane" id="tab-option">
              <fieldset>
                <legend><?php echo $text_article; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_article_count; ?>"><?php echo $entry_article_count; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($configblog_article_count) { ?>
                      <input type="radio" name="configblog_article_count" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="configblog_article_count" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$configblog_article_count) { ?>
                      <input type="radio" name="configblog_article_count" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="configblog_article_count" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-catalog-limit"><span data-toggle="tooltip" title="<?php echo $help_article_limit; ?>"><?php echo $entry_article_limit; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="configblog_article_limit" value="<?php echo $configblog_article_limit; ?>" placeholder="<?php echo $entry_article_limit; ?>" id="input-catalog-limit" class="form-control" />
                    <?php if ($error_article_limit) { ?>
                    <div class="text-danger"><?php echo $error_article_limit; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-list-description-limit"><span data-toggle="tooltip" title="<?php echo $help_article_description_length; ?>"><?php echo $entry_article_description_length; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="configblog_article_description_length" value="<?php echo $configblog_article_description_length; ?>" placeholder="<?php echo $entry_article_description_length; ?>" id="input-list-description-limit" class="form-control" />
                    <?php if ($error_article_description_length) { ?>
                    <div class="text-danger"><?php echo $error_article_description_length; ?></div>
                    <?php } ?>
                  </div>
                </div>
                <div class="form-group required">
                  <label class="col-sm-2 control-label" for="input-admin-limit"><span data-toggle="tooltip" title="<?php echo $help_limit_admin; ?>"><?php echo $entry_limit_admin; ?></span></label>
                  <div class="col-sm-10">
                    <input type="text" name="configblog_limit_admin" value="<?php echo $configblog_limit_admin; ?>" placeholder="<?php echo $entry_limit_admin; ?>" id="input-admin-limit" class="form-control" />
                    <?php if ($error_limit_admin) { ?>
                    <div class="text-danger"><?php echo $error_limit_admin; ?></div>
                    <?php } ?>
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_blog_menu; ?>"><?php echo $entry_blog_menu; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($configblog_blog_menu) { ?>
                      <input type="radio" name="configblog_blog_menu" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="configblog_blog_menu" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$configblog_blog_menu) { ?>
                      <input type="radio" name="configblog_blog_menu" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="configblog_blog_menu" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
				<div class="form-group">
                  <label class="col-sm-2 control-label"><?php echo $entry_article_download; ?></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($configblog_article_download) { ?>
                      <input type="radio" name="configblog_article_download" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="configblog_article_download" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$configblog_article_download) { ?>
                      <input type="radio" name="configblog_article_download" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="configblog_article_download" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
              </fieldset>
              <fieldset>
                <legend><?php echo $text_review; ?></legend>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_review; ?>"><?php echo $entry_review; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($configblog_review_status) { ?>
                      <input type="radio" name="configblog_review_status" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="configblog_review_status" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$configblog_review_status) { ?>
                      <input type="radio" name="configblog_review_status" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="configblog_review_status" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_review_guest; ?>"><?php echo $entry_review_guest; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($configblog_review_guest) { ?>
                      <input type="radio" name="configblog_review_guest" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="configblog_review_guest" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$configblog_review_guest) { ?>
                      <input type="radio" name="configblog_review_guest" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="configblog_review_guest" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-sm-2 control-label"><span data-toggle="tooltip" title="<?php echo $help_review_mail; ?>"><?php echo $entry_review_mail; ?></span></label>
                  <div class="col-sm-10">
                    <label class="radio-inline">
                      <?php if ($configblog_review_mail) { ?>
                      <input type="radio" name="configblog_review_mail" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                      <?php } else { ?>
                      <input type="radio" name="configblog_review_mail" value="1" />
                      <?php echo $text_yes; ?>
                      <?php } ?>
                    </label>
                    <label class="radio-inline">
                      <?php if (!$configblog_review_mail) { ?>
                      <input type="radio" name="configblog_review_mail" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                      <?php } else { ?>
                      <input type="radio" name="configblog_review_mail" value="0" />
                      <?php echo $text_no; ?>
                      <?php } ?>
                    </label>
                  </div>
                </div>
              </fieldset>
            </div>
            <div class="tab-pane" id="tab-image">
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-image-category-width"><?php echo $entry_image_category; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-6">
                      <input type="text" name="configblog_image_category_width" value="<?php echo $configblog_image_category_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-category-width" class="form-control" />
                    </div>
                    <div class="col-sm-6">
                      <input type="text" name="configblog_image_category_height" value="<?php echo $configblog_image_category_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                    </div>
                  </div>
                  <?php if ($error_image_category) { ?>
                  <div class="text-danger"><?php echo $error_image_category; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-image-article-width"><?php echo $entry_image_article; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-6">
                      <input type="text" name="configblog_image_article_width" value="<?php echo $configblog_image_article_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-article-width" class="form-control" />
                    </div>
                    <div class="col-sm-6">
                      <input type="text" name="configblog_image_article_height" value="<?php echo $configblog_image_article_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                    </div>
                  </div>
                  <?php if ($error_image_article) { ?>
                  <div class="text-danger"><?php echo $error_image_article; ?></div>
                  <?php } ?>
                </div>
              </div>
              <div class="form-group required">
                <label class="col-sm-2 control-label" for="input-image-related"><?php echo $entry_image_related; ?></label>
                <div class="col-sm-10">
                  <div class="row">
                    <div class="col-sm-6">
                      <input type="text" name="configblog_image_related_width" value="<?php echo $configblog_image_related_width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-image-related" class="form-control" />
                    </div>
                    <div class="col-sm-6">
                      <input type="text" name="configblog_image_related_height" value="<?php echo $configblog_image_related_height; ?>" placeholder="<?php echo $entry_height; ?>" class="form-control" />
                    </div>
                  </div>
                  <?php if ($error_image_related) { ?>
                  <div class="text-danger"><?php echo $error_image_related; ?></div>
                  <?php } ?>
                </div>
              </div>
            </div>
        </form>
      </div>
    </div>
  </div>
  </div>
<?php echo $footer; ?>