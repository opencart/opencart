<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $clearallcache; ?>" data-toggle="tooltip" title="<?php echo $button_clearallcache; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></a> <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
        <thead>
          <tr>
            <td class="text-left"><?php echo $column_description; ?></td>
			<td class="text-center"><?php echo $column_action;?></td>
   		</tr>
        </thead>
        <tbody>
          <tr>
            <td class="text-left"><?php echo $image_description; ?></td>
			<td class="text-center"><a href="<?php echo $clearcache; ?>" data-toggle="tooltip" title="<?php echo $button_clearcache; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></a></td>
   		</tr>
		<tr>
            <td class="text-left"><?php echo $system_description; ?></td>
			<td class="text-center"><a href="<?php echo $clearsystemcache; ?>" data-toggle="tooltip" title="<?php echo $button_clearsystemcache; ?>" class="btn btn-primary"><i class="fa fa-refresh"></i></a></td>
   		</tr>
        </tbody>
      </table>
	  </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>