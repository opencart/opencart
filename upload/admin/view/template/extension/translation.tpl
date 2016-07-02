<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
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
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <fieldset>
          <legend>Install Progress</legend>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_progress; ?></label>
            <div class="col-sm-10">
              <div class="progress">
                <div id="progress-bar" class="progress-bar" style="width: 0%;"></div>
              </div>
              <div id="progress-text"></div>
            </div>
          </div>
        </fieldset>
        <br />
        <fieldset>
          <legend>Avaliable Translations</legend>
          <div class="alert alert-info"><i class="fa fa-info-circle"></i> The below translations are translated at the crowdsourcing project <a href="https://crowdin.com/project/opencart">Crowdin</a>. If you can not find </div>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-center"><?php echo $column_flag; ?></td>
                  <td class="text-left"><?php echo $column_name; ?></td>
                  <td class="text-left"><?php echo $column_progress; ?></td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($translations) { ?>
                <?php foreach ($translations as $translation) { ?>
                <tr>
                  <td class="text-center"><img src="<?php echo $translation['image']; ?>" alt="<?php echo $translation['name']; ?>" title="<?php echo $translation['name']; ?>" style="width: 48px; height: 48px;" /></td>
                  <td class="text-left"><?php echo $translation['name']; ?></td>
                  <td class="text-left"><div class="progress">
                      <?php if ($translation['progress'] > 75) { ?>
                      <div class="progress-bar progress-bar-success progress-bar-striped" style="width:<?php echo $translation['progress']; ?>%"><?php echo $translation['progress']; ?>%</div>
                      <?php }else if ($translation['progress'] > 25 && $translation['progress'] < 75) { ?>
                      <div class="progress-bar progress-bar-info progress-bar-striped" style="width:<?php echo $translation['progress']; ?>%"><?php echo $translation['progress']; ?>%</div>
                      <?php }else if ($translation['progress'] < 25) { ?>
                      <div class="progress-bar progress-bar-danger progress-bar-striped"  style="width:<?php echo $translation['progress']; ?>%"><?php echo $translation['progress']; ?>%</div>
                      <?php } ?>
                    </div></td>
                  <td class="text-right"><?php if (!$translation['installed']) { ?>
                    <a href="<?php echo $translation['install']; ?>" data-toggle="tooltip" title="<?php echo $button_install; ?>" class="btn btn-success"><i class="fa fa-plus-circle"></i></a>
                    <?php } else { ?>
                    <a href="<?php echo $translation['uninstall']; ?>" data-toggle="tooltip" title="<?php echo $button_uninstall; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></a>
                    <?php } ?></td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="row">
            <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
            <div class="col-sm-6 text-right"><?php echo $results; ?></div>
          </div>
        </fieldset>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('table .btn').on('click', function(e) {
	e.preventDefault();
	
	var node = this;
	
	$.ajax({
		url: $(node).attr('href'),
		method: 'post',
		dataType: 'json',
		beforeSend: function() {
			$(node).button('loading');
		},
		complete: function() {
			$(node).button('reset');
		},
		success: function(json) {
			$('.alert-success, .alert-danger').remove();

			if (json['error']['warning']) {
				$('#content > .container-fluid').prepend('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error']['warning'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
			}		
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});



</script> 
<?php echo $footer; ?> 