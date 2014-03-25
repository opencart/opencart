<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($error_warning) { ?>
  <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
    <button type="button" class="close" data-dismiss="alert">&times;</button>
  </div>
  <?php } ?>
  <div class="panel panel-default">
    <div class="panel-heading">
      <div class="pull-right">
        <button type="submit" form="form-location" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn"><i class="fa fa-check-circle"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn"><i class="fa fa-reply"></i></a></div>
      <h1 class="panel-title"><i class="fa fa-pencil-square fa-lg"></i> <?php echo $heading_title; ?></h1>
    </div>
    <div class="panel-body">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-location" class="form-horizontal">
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
          <div class="col-sm-10">
            <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
            <?php if ($error_name) { ?>
            <span class="text-danger"><?php echo $error_name; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-address"><?php echo $entry_address; ?></label>
          <div class="col-sm-10">
            <textarea type="text" name="address" placeholder="<?php echo $entry_address; ?>" rows="5" id="input-address" class="form-control"><?php echo $address; ?></textarea>
            <?php if ($error_address) { ?>
            <span class="text-danger"><?php echo $error_address; ?></span>
            <?php } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-geocode"><?php echo $entry_geocode; ?></label>
          <div class="col-sm-10">
            <input type="text" name="geocode" value="<?php echo $geocode; ?>" placeholder="<?php echo $entry_geocode; ?>" id="input-geocode" class="form-control" />
            <span class="help-block"><?php echo $help_geocode; ?></span></div>
        </div>
        <div class="form-group required">
          <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
          <div class="col-sm-10">
            <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
            <?php if ($error_telephone) { ?>
            <span class="text-danger"><?php echo $error_telephone; ?></span>
            <?php  } ?>
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-fax"><?php echo $entry_fax; ?></label>
          <div class="col-sm-10">
            <input type="text" name="fax" value="<?php echo $fax; ?>" placeholder="<?php echo $entry_fax; ?>" id="input-fax" class="form-control" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-image"><?php echo $entry_image; ?></label>
          <div class="col-sm-10">
            <?php if ($thumb) { ?>
            <a href="" id="thumb-image" class="img-thumbnail img-edit"><img src="<?php echo $thumb; ?>" alt="" title="" /></a>
            <?php } else { ?>
            <a href="" id="thumb-image" class="img-thumbnail img-edit"><i class="fa fa-camera fa-5x"></i></a>
            <?php } ?>
            <input type="hidden" name="image" value="<?php echo $image; ?>" id="input-image" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-open"><?php echo $entry_open; ?></label>
          <div class="col-sm-10">
            <textarea name="open" rows="5" placeholder="<?php echo $entry_open; ?>" id="input-open" class="form-control"><?php echo $open; ?></textarea>
            <span class="help-block"><?php echo $help_open; ?></span></div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label" for="input-comment"><?php echo $entry_comment; ?></label>
          <div class="col-sm-10">
            <textarea name="comment" rows="5" placeholder="<?php echo $entry_comment; ?>" id="input-comment" class="form-control"><?php echo $comment; ?></textarea>
            <span class="help-block"><?php echo $help_comment; ?></span></div>
        </div>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>