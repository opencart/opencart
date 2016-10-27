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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-puzzle-piece"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <fieldset>
          <legend><?php echo $text_type; ?></legend>
          <div class="well">
            <div class="btn-group ext-types">
              <?php foreach ($categories as $category) { ?>
              <?php $active = ($type == $category['code'])?' active':''; ?>
              <button type="button" class="ext-type btn btn-default<?php echo $active; ?>" data-value="<?php echo $category['href']; ?>"><i class="fa fa-filter"></i> <?php echo $category['text']; ?></button>
              <?php } ?>
            </div>
          </div>
        </fieldset>
        <div id="extension"></div>
      </div>
    </div>
  </div>

<?php if ($categories) { ?>
<script type="text/javascript"><!--
$('.ext-type').on('click', function() {
	if ($(this).hasClass('active')) return;
	$(this).addClass('active').siblings().removeClass('active');
	$('.ext-types').trigger('change');
});

$('.ext-types').on('change', function() {
	$.ajax({
		url: $('.ext-type.active').attr('data-value'),
		dataType: 'html',
		beforeSend: function() {
			$('.ext-type.active .fa-filter').addClass('fa-circle-o-notch fa-spin').removeClass('fa-filter');
		},
		complete: function() {
			$('.ext-type.active .fa-circle-o-notch').addClass('fa-filter').removeClass('fa-circle-o-notch fa-spin');
		},
		success: function(html) {
			$('#extension').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

if ($('.ext-type.active').length > 0) {
	$('.ext-types').trigger('change');
} else {
	$('.ext-type:first').click();
}

$('#extension').on('click', '.btn-success', function(e) {
	e.preventDefault();

	var node = this;

	$.ajax({
		url: $(node).attr('href'),
		dataType: 'html',
		beforeSend: function() {
			$(node).button('loading');
		},
		complete: function() {
			$(node).button('reset');
		},
		success: function(html) {
			$('#extension').html(html);
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});

$('#extension').on('click', '.btn-danger, .btn-warning', function(e) {
	e.preventDefault();

	if (confirm('<?php echo $text_confirm; ?>')) {
		var node = this;

		$.ajax({
			url: $(node).attr('href'),
			dataType: 'html',
			beforeSend: function() {
				$(node).button('loading');
			},
			complete: function() {
				$(node).button('reset');
			},
			success: function(html) {
				$('#extension').html(html);
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});
	}
});
//--></script>
<?php } ?>

</div>
<?php echo $footer; ?>