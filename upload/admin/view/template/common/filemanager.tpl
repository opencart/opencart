<div class="modal-dialog">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      <h4 class="modal-title"><?php echo $heading_title; ?></h4>
    </div>
    <div class="modal-body">
      <div class="input-group">
        <input type="text" name="search" value="" placeholder="<?php echo $text_search; ?>" class="form-control">
        <span class="input-group-btn">
        <button type="button" id="button-search" class="btn btn-primary"><i class="icon-search"></i></button>
        </span></div>
      <hr />
      <?php foreach (array_chunk($images, 4) as $image) { ?>
      <div class="row">
        <?php foreach ($image as $image) { ?>
        <div class="col-sm-3"><a href="#" class="thumbnail"><img src="<?php echo $image['image']; ?>" alt="<?php echo $image['name']; ?>" title="<?php echo $image['name']; ?>" />
          <input type="hidden" name="image_id" value="<?php echo $image['image_id']; ?>" />
          </a></div>
        <?php } ?>
      </div>
      <br />
      <?php } ?>
    </div>
    <div class="modal-footer"><?php echo $pagination; ?></div>
  </div>
</div>
<script type="text/javascript"><!--
$('#modal-image #button-search').on('click', function(e) {
	e.preventDefault();

	$.ajax({
		url: 'index.php?route=common/filemanager&token=<?php echo $token; ?>&filter_tag=' +  encodeURIComponent(this.value),
		dataType: 'html',	
		success: function(html) {
			alert(html);
			
			$('#model-image').load(html);
		}
	});
});

$('#modal-image .pagination a').on('click', function(e) {
	e.preventDefault();
	
	$('#model-image').load(this.href);
});

$('#modal-image a.thumbnail').on('click', function(e) {
	e.preventDefault();
	
	$('#thumb').attr('src', $(this).find('img').attr('src'));
	
	$('#input-image').attr('value', $(this).find('input').attr('value'));
});		
//--></script> 