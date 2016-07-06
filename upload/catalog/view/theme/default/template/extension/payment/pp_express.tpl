<div class="buttons">
  <div class="pull-right">
    <a href="{{ continue }}" class="btn btn-primary" id="button-confirm" data-loading-text="{{ text_loading }}">{{ button_continue }}</a>
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
	$('#button-confirm').button('loading');
});
//--></script>