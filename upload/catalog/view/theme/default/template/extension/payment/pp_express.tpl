<div class="buttons">
  <div class="pull-right">
    <a href="<?php echo $continue; ?>" class="btn btn-primary" id="button-confirm" data-loading-text="<?php echo $text_loading; ?>"><?php echo $button_continue; ?></a>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
	$('#button-confirm').button('loading');
});
//--></script>