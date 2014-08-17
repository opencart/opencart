<?php echo $header; ?><?php echo $menu; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-location" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h1>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-location" class="form-horizontal">
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
        <div class="col-sm-10">
          <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
          <?php if ($error_name) { ?>
          <div class="text-danger"><?php echo $error_name; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-address"><?php echo $entry_address; ?></label>
        <div class="col-sm-10">
          <textarea type="text" name="address" placeholder="<?php echo $entry_address; ?>" rows="5" id="input-address" class="form-control"><?php echo $address; ?></textarea>
          <?php if ($error_address) { ?>
          <div class="text-danger"><?php echo $error_address; ?></div>
          <?php } ?>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-geocode"><span data-toggle="tooltip" data-container="#content" title="<?php echo $help_geocode; ?>"><?php echo $entry_geocode; ?></span></label>
        <div class="col-sm-10">
          <input type="text" name="geocode" value="<?php echo $geocode; ?>" placeholder="<?php echo $entry_geocode; ?>" id="input-geocode" class="form-control" />
        </div>
      </div>
      <div class="form-group required">
        <label class="col-sm-2 control-label" for="input-telephone"><?php echo $entry_telephone; ?></label>
        <div class="col-sm-10">
          <input type="text" name="telephone" value="<?php echo $telephone; ?>" placeholder="<?php echo $entry_telephone; ?>" id="input-telephone" class="form-control" />
          <?php if ($error_telephone) { ?>
          <div class="text-danger"><?php echo $error_telephone; ?></div>
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
        <label class="col-sm-2 control-label" for="input-open"><span data-toggle="tooltip" data-container="#content" title="<?php echo $help_open; ?>"><?php echo $entry_open; ?></span></label>
        <div class="col-sm-10">
          <textarea name="open" rows="5" placeholder="<?php echo $entry_open; ?>" id="input-open" class="form-control"><?php echo $open; ?></textarea>
        </div>
      </div>
      <div class="form-group">
        <label class="col-sm-2 control-label" for="input-comment"><span data-toggle="tooltip" data-container="#content" title="<?php echo $help_comment; ?>"><?php echo $entry_comment; ?></span></label>
        <div class="col-sm-10">
          <textarea name="comment" rows="5" placeholder="<?php echo $entry_comment; ?>" id="input-comment" class="form-control"><?php echo $comment; ?></textarea>
        </div>
      </div>
    </form>
  </div>
</div>
<?php echo $footer; ?>