<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-weight-class" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="form-horizontal" id="form-weight-class">
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_title; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="weight_class_description[<?php echo $language['language_id']; ?>][title]" value="<?php echo isset($weight_class_description[$language['language_id']]) ? $weight_class_description[$language['language_id']]['title'] : ''; ?>" placeholder="<?php echo $entry_title; ?>" class="form-control" />
              </div>
              <?php if (isset($error_title[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_title[$language['language_id']]; ?></div>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_unit; ?></label>
            <div class="col-sm-10">
              <?php foreach ($languages as $language) { ?>
              <div class="input-group"><span class="input-group-addon"><img src="language/<?php echo $language['code']; ?>/<?php echo $language['code']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                <input type="text" name="weight_class_description[<?php echo $language['language_id']; ?>][unit]" value="<?php echo isset($weight_class_description[$language['language_id']]) ? $weight_class_description[$language['language_id']]['unit'] : ''; ?>" placeholder="<?php echo $entry_unit; ?>" class="form-control" />
              </div>
              <?php if (isset($error_unit[$language['language_id']])) { ?>
              <div class="text-danger"><?php echo $error_unit[$language['language_id']]; ?></div>
              <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-value"><span data-toggle="tooltip" title="<?php echo $help_value; ?>"><?php echo $entry_value; ?></span></label>
            <div class="col-sm-10">
              <input type="text" name="value" value="<?php echo $value; ?>" placeholder="<?php echo $entry_value; ?>" id="input-value" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>