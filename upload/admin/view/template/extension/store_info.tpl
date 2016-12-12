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
        <div class="row">
          <div class="col-sm-8"><?php if ($banner) { ?>
            <div class="well well-sm"><img src="<?php echo $banner; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></div>
            <?php } ?>
            <div><?php foreach ($images as $image) { ?><a href="<?php echo $image['popup']; ?>" class="colorbox" rel="colorbox"><img src="<?php echo $image['thumb']; ?>" alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" class="image" /></a><?php } ?></div>
            <ul class="nav nav-tabs">
              <li class="active"><a href="#tab-description" data-toggle="tab"><?php echo $tab_description; ?></a></li>
              <li><a href="#tab-documentation" data-toggle="tab"><?php echo $tab_documentation; ?></a></li>
              <li><a href="#tab-comment" data-toggle="tab"><?php echo $tab_comment; ?></a></li>
            </ul>
            <div class="tab-content">
              <div id="tab-description" class="tab-pane active"><?php echo $description; ?>}</div>
              <div id="tab-documentation" class="tab-pane"><?php echo $documentation; ?></div>
              <div id="tab-comment" class="tab-pane"><?php echo $comment; ?></div>
            </div>
          </div>
          <div class="col-sm-4">
            <div class="well well-sm">
              <button type="button" class="btn btn-primary btn-block"><?php echo $button_buy; ?></button>
              <br />
              <div class="dropdown">
                <button type="button" data-loading="<?php echo $text_loading; ?>" data-toggle="dropdown" class="btn btn-default btn-block"><?php echo $button_install; ?> <span class="caret"></span></button>
                <ul class="dropdown-menu pull-right">
                  <?php foreach (downloads as $download) { ?>
                  <li><a href="<?php echo $download['extension_download_id']; ?>"><i class="fa fa-download"></i> <?php echo $download['name']; ?></a></li>
                  <?php } ?>
                </ul>
              </div>
              <br />
              <div id="progress">
                <div class="progress">
                  <div id="progress-bar" class="progress-bar" style="width: 0%;"></div>
                </div>
                <div id="progress-text"></div>
              </div>
              <hr />
              <?php if ($license) { ?>
              <div class="row">
                <div class="col-md-6"><?php echo $text_price; ?></div>
                <div class="col-md-6 text-right"><?php echo $price; ?></div>
              </div>
              <?php } else { ?>
              <div class="row">
                <div class="col-md-6"><?php echo $text_price; ?></div>
                <div class="col-md-6 text-right">Free</div>
              </div>
              <?php } ?>
              <hr />
              <div class="row">
                <div class="col-md-12">
                  <div class=""><i class="fa fa-check fa-fw text-success"></i> <?php echo $text_partner; ?></div>
                  <div class=""><i class="fa fa-check fa-fw text-success"></i> <?php echo $text_support; ?></div>
                  <div class=""><i class="fa fa-check fa-fw text-success"></i> <?php echo $text_documentation; ?></div>
                </div>
              </div>
              <hr />
              <div class="row">
                <div class="col-md-6"><?php echo $text_rating; ?></div>
                <div class="col-md-6 text-right">
                
                

                  
                                        <?php for ($i = 1; $i < 5; $i++) { ?>
                      <?php if ($i < $rating) { ?>
                      <i class="fa fa-star"></i>
                      <?php } else { ?>
                      <i class="fa fa-star-o"></i>
                      <?php } ?>
                      <?php } ?>
                  
                  </div>
              </div>
              <hr />
              <div class="row">
                <div class="col-md-6"><?php echo $text_compatibility; ?></div>
                <div class="col-md-6 text-right"><?php echo $compatibility; ?></div>
              </div>
              <hr />
              <div class="row">
                <div class="col-md-6"><?php echo $text_date_added; ?></div>
                <div class="col-md-6 text-right"><?php echo $date_added; ?></div>
              </div>
              <hr />
              <div class="row">
                <div class="col-md-6"><?php echo $text_date_modified; ?></div>
                <div class="col-md-6 text-right"><?php echo $date_modified; ?></div>
              </div>
            </div>
            {% if license %}
            <div class="well well-sm">
              <div class="row">
                <div class="col-md-12"><i class="fa fa-shopping-cart fa-2x text-success"></i> <?php echo $sales; ?> <?php echo $text_sales; ?></div>
              </div>
            </div>
            {% else %}
            <div class="well well-sm">
              <div class="row">
                <div class="col-md-12"><i class="fa fa-cloud-download fa-2x text-primary"></i> <?php echo $downloaded; ?> <?php echo $text_downloaded; ?></div>
              </div>
            </div>
            {% endif %}
            <div class="well well-sm">
              <div class="row">
                <div class="col-md-12"><i class="fa fa-comment fa-2x text-info"></i> <?php echo $comment_total; ?> <?php echo $text_comment; ?></div>
              </div>
            </div>
            <div class="well well-sm"> <b>Developer</b> <a href="<?php echo $filter_username; ?>"><?php echo $member; ?></a> </div>
          </div>
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