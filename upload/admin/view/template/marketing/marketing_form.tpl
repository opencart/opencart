<?php echo $header; ?>
<div id="content" class="container">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="icon-exclamation-sign"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-marketing" class="btn btn-primary"><i class="icon-ok"></i> <?php echo $button_save; ?></button>
        <a href="<?php echo $cancel; ?>" class="btn btn-danger"><i class="icon-remove"></i> <?php echo $button_cancel; ?></a></div>
      <h1 class="panel-title"><i class="icon-edit"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-marketing" class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
          <div class="col-sm-10">
            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
            <?php if ($error_name) { ?>
            <div class="text-danger"><?php echo $error_name; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-description"><?php echo $entry_description; ?></label>
          <div class="col-sm-10">
            <textarea name="description" rows="5" placeholder="<?php echo $entry_description; ?>" id="input-description" class="form-control"><?php echo $description; ?></textarea>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-code"><?php echo $entry_code; ?></label>
          <div class="col-sm-10">
            <input type="text" name="code" value="<?php echo $code; ?>" placeholder="<?php echo $entry_code; ?>" id="input-code" class="form-control" />
            <span class="help-block"><?php echo $help_code; ?></span>
            <?php if ($error_code) { ?>
            <div class="text-danger"><?php echo $error_code; ?></div>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-example"><?php echo $entry_example; ?></label>
          <div class="col-sm-10">
            <input type="text" id="input-example1" class="form-control" />
            <br />
            <input type="text" id="input-example2" class="form-control" />
            <span class="help-block"><?php echo $help_example; ?></span></div>
        </div>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('#input-code').on('keydown', function() {
	$('#input-example1').val('<?php echo $store; ?>?tracking=' + $('#input-code').val());
	$('#input-example2').val('<?php echo $store; ?>index.php?route=common/home&tracking=' + $('#input-code').val());
});

$('#input-code').trigger('keydown');
//--></script> 
<?php echo $footer; ?>