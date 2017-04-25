<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-puzzle-piece"></i> <?php echo $name; ?></h3>
      </div>
      <div class="panel-body">
        <?php if ($banner) { ?>
        <div class="well well-sm text-center"><img src="<?php echo $banner; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></div>
        <?php } ?>
        <div>
          <?php foreach ($images as $image) { ?>
          <a href="<?php echo $image['popup']; ?>" class="colorbox" rel="colorbox"><img src="<?php echo $image['thumb']; ?>" alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" class="image" /></a>
          <?php } ?>
        </div>
        <ul class="nav nav-tabs">
          <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
          <li><a href="#tab-documentation" data-toggle="tab"><?php echo $tab_documentation; ?></a></li>
          <li><a href="#tab-download" data-toggle="tab"><?php echo $tab_download; ?></a></li>
          <li><a href="#tab-comment" data-toggle="tab"><?php echo $tab_comment; ?> (<?php echo $comment_total; ?>)</a></li>
        </ul>
        <div class="tab-content">
          <div id="tab-description" class="tab-pane active">
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td><strong><?php echo $text_price; ?></strong></td>
                  <td><?php if ($license) { ?>
                    <?php echo $price; ?>
                    <?php } else { ?>
                    Free
                    <?php } ?></td>
                </tr>
                <?php if ($license) { ?>
                <tr>
                  <td><strong><?php echo $text_license; ?></strong></td>
                  <td><?php if ($license_period) { ?>
                    <?php echo $license_period; ?>
                    <?php } else { ?>
                    Unlimited
                    <?php } ?></td>
                </tr>
                <?php } ?>
                <tr>
                  <td><strong>Developer</strong></td>
                  <td><a href="<?php echo $filter_member; ?>"><?php echo $member_username; ?></a></td>
                </tr>
                <tr>
                  <td><strong><?php echo $text_compatibility; ?></strong></td>
                  <td><?php echo $compatibility; ?></td>
                </tr>
                <tr>
                  <td><strong><?php echo $text_rating; ?></strong></td>
                  <td><?php for ($i = 1; $i < 5; $i++) { ?>
                    <?php if ($i < $rating) { ?>
                    <i class="fa fa-star"></i>
                    <?php } else { ?>
                    <i class="fa fa-star-o"></i>
                    <?php } ?>
                    <?php } ?></td>
                </tr>
                <tr>
                  <td><strong><?php echo $text_date_added; ?></strong></td>
                  <td><?php echo $date_added; ?></td>
                </tr>
                <tr>
                  <td><strong><?php echo $text_date_modified; ?></strong></td>
                  <td><?php echo $date_modified; ?></td>
                </tr>
                <tr>
                  <td><strong><?php echo $text_sales; ?></strong></td>
                  <td><?php echo $sales; ?></td>
                </tr>
                <tr>
                  <td><strong><?php echo $text_downloaded; ?></strong></td>
                  <td><?php echo $downloaded; ?></td>
                </tr>
              </tbody>
            </table>
            <?php echo $description; ?></div>
          <div id="tab-documentation" class="tab-pane"><?php echo $documentation; ?></div>
          <div id="tab-download" class="tab-pane">
            <fieldset>
              <legend>Progress</legend>
              <div id="progress">
                <div class="progress">
                  <div id="progress-bar" class="progress-bar" style="width: 0%;"></div>
                </div>
                <div id="progress-text"></div>
              </div>
              <hr />
            </fieldset>
            <fieldset>
              <legend>Available Installs</legend>
              <button type="button" class="btn btn-primary btn-block"><?php echo $button_buy; ?></button>
              <table class="table table-bordered">
                <tbody>
                  <?php foreach ($downloads as $download) { ?>
                  <tr>
                    <td><?php echo $download['name']; ?></td>
                    <td><button type="button" data-loading="<?php echo $text_loading; ?>" class="btn btn-primary btn-block"><i class="fa fa-download"></i> Install</button></td>
                  </tr>
                  <?php } ?>
                </tbody>
              </table>
            </fieldset>
          </div>
          <div id="tab-comment" class="tab-pane"></div>
        </div>
      </div>
    </div>
  </div>
  <script type="text/javascript"><!--
$('#button-install').parent().find('a').on('click', function(e) {
	e.preventDefault();
	
	var node = this;
	
	// Reset everything
	$('#progress-bar').css('width', '0%');
	$('#progress-bar').removeClass('progress-bar-danger progress-bar-success');
	$('#progress-text').html('');

	$.ajax({
		url: 'index.php?route=extension/store/download&token={{ token }}&extension_download_id=' + this.value,
		dataType: 'json',
		beforeSend: function() {
			$('#button-install').button('loading');
		},
		complete: function() {
			$('#button-install').button('reset');
		},
		success: function(json) {
			console.log(json);
			
			if (json['error']) {
				$('#progress-bar').addClass('progress-bar-danger');
				$('#progress-text').html('<div class="text-danger">' + json['error'] + '</div>');
			}
			
			if (json['text']) {
				$('#progress-bar').css('width', '20%');
				$('#progress-text').html(json['text']);
			}
			
			if (json['next']) {
				next(json['next'], i = 2);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

function next(url, i) {
	$.ajax({
		url: url,
		dataType: 'json',
		success: function(json) {
			console.log(json);
			
			$('#progress-bar').css('width', (Math.ceil(i * 1.6) * 100) + '%');
			
			if (json['error']) {
				$('#progress-bar').addClass('progress-bar-danger');
				$('#progress-text').html('<div class="text-danger">' + json['error'] + '</div>');
			}
			
			if (json['text']) {
				$('#progress-bar').css('width', '20%');
				$('#progress-text').html(json['text']);
			}
			
			if (json['success']) {
				$('#progress-bar').addClass('progress-bar-success');
				$('#progress-text').html('<span class="text-success">' + json['success'] + '</span>');
			}

			if (json['next']) {
				next(json['next'], i = i + 1);
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
}
//--></script> 
</div>
<?php echo $footer; ?>