<?php echo $header; ?>
<div class="container">
  <header>
    <div class="row">
      <div class="col-sm-6">
        <h1 class="pull-left">1<small>/2</small></h1>
        <h3><?php echo $heading_title; ?><br>
          <small><?php echo $text_upgrade; ?></small></h3>
      </div>
      <div class="col-sm-6">
        <div id="logo" class="pull-right hidden-xs"><img src="view/image/logo.png" alt="OpenCart" title="OpenCart" /></div>
      </div>
    </div>
  </header>
  <div class="row">
    <div class="col-sm-9">
      <h3><?php echo $text_server; ?></h3>
      <fieldset>
        <ol>
          <li><?php echo $text_error; ?></li>
          <li><?php echo $text_clear; ?></li>
          <li><?php echo $text_admin; ?></li>
          <li><?php echo $text_user; ?></li>
          <li><?php echo $text_setting; ?></li>
          <li><?php echo $text_store; ?></li>
        </ol>
      </fieldset>
      <h3><?php echo $text_steps; ?></h3>
      <fieldset>
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
      <div class="buttons">
        <div class="text-right">
          <input type="submit" value="<?php echo $button_continue; ?>" id="button-continue" class="btn btn-primary" />
        </div>
      </div>
    </div>
    <div class="col-sm-3"><?php echo $column_left; ?></div>
  </div>
  <script type="text/javascript"><!--
var step = 0;

$('#button-continue').on('click', function() {
	$('#progress-bar').addClass('progress-bar-success').css('width', '0%').removeClass('progress-bar-danger');
	$('#progress-text').html('');
	$('#button-continue').prop('disabled', true).before('<i class="fa fa-spinner fa-spin"></i> ');

	start('index.php?route=upgrade/upgrade/next');
});

function start(url) {
	setTimeout(function(){
		$.ajax({
			url: url,
			type: 'post',
			dataType: 'json',
			success: function(json) {
				if (json['error']) {
					$('#progress-bar').addClass('progress-bar-danger');
					$('#progress-text').html('<div class="text-danger">' + json['error'] + '</div>');

					$('#button-continue').prop('disabled', false);
					$('.fa-spinner').remove();
				}

				if (json['success']) {
					$('#progress-text').html('<span class="text-success">' + json['success'] + '</span>');
					$('#progress-bar').css('width', ((step / <?php echo $total; ?>) * 100) + '%');
				}

				if (json['next']) {
					start(json['next']);
				} else if (!json['error']) {
					$('#button-continue').replaceWith('<a href="<?php echo $store; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>');
					$('.fa-spinner').remove();
				}

				step++;
			},
			error: function(xhr, ajaxOptions, thrownError) {
				$('#progress-bar').addClass('progress-bar-danger');
				$('#progress-text').html('<div class="text-danger">' + (thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText) + '</div>');
				$('#button-continue').prop('disabled', false);
				$('.fa-spinner').remove();
			}
		});
	}, 1000);
}
//--></script></div>
<?php echo $footer; ?>