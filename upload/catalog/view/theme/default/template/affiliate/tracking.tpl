<?php echo $header; ?>

<!-- Breadcrumb -->
<ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    
    <li>
        <a href="<?php echo $breadcrumb['href']; ?>">
            <?php echo $breadcrumb['text']; ?>
        </a>
    </li>
    <?php } ?>
</ul>

<div class="row">

	<?php echo $column_left; ?>

	<div class="span9">

		<?php echo $content_top; ?>

		<h1><?php echo $heading_title; ?></h1>
		<p><?php echo $text_description; ?></p>

		<label><?php echo $text_code; ?></label>
		<textarea cols="40" rows="5"><?php echo $code; ?></textarea>

		<label><?php echo $text_generator; ?></label>
		<input type="text" name="product" value="" />

		<label><?php echo $text_link; ?></label>
    	<textarea name="link" cols="40" rows="5"></textarea>

    	<div class="buttons clearfix">
    		<div class="pull-right"><a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a></div>
    	</div>

    	<?php echo $content_bottom; ?>
    </div>

    <?php echo $column_right; ?>

</div>

<script type="text/javascript"><!--
$('input[name=\'product\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=affiliate/tracking/autocomplete&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name,
						value: item.link
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'product\']').attr('value', ui.item.label);
		$('textarea[name=\'link\']').attr('value', ui.item.value);
						
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});
//--></script> 
<?php echo $footer; ?>