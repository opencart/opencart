<?php echo $header; ?>
<div id="content">
  <div class="container-fluid"><br />
    <br />
    <div class="row">
      <div class="col-sm-offset-4 col-sm-4">
        <div class="panel panel-default">
          <div class="panel-heading">
            <h1 class="panel-title"><i class="mi mi-repeat">repeat</i> <?php echo $heading_title; ?></h1>
          </div>
          <div class="panel-body">
            <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="mi mi-exclamation-circle">error</i> <?php echo $error_warning; ?>
              <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
            <?php } ?>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="input-email"><?php echo $entry_email; ?></label>
                <div class="input-group"><span class="input-group-addon"><i class="mi mi-envelope">mail</i></span>
                  <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                </div>
              </div>
              <div class="text-right">
                <button type="submit" class="btn btn-primary"><i class="mi mi-check">check</i> <?php echo $button_reset; ?></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="mi mi-reply">reply</i></a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
