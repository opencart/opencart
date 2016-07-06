<h2>{{ text_instruction }}</h2>
<p><b>{{ text_description }}</b></p>
<div class="well well-sm">
  <p><?php echo $bank; ?></p>
  <p>{{ text_payment }}</p>
</div>
<div class="buttons">
  <div class="pull-right">
    <input type="button" value="{{ button_confirm }}" id="button-confirm" class="btn btn-primary" data-loading-text="{{ text_loading }}" />
  </div>
</div>
<script type="text/javascript"><!--
$('#button-confirm').on('click', function() {
	$.ajax({
		type: 'get',
		url: 'index.php?route=extension/payment/bank_transfer/confirm',
		cache: false,
		beforeSend: function() {
			$('#button-confirm').button('loading');
		},
		complete: function() {
			$('#button-confirm').button('reset');
		},
		success: function() {
			location = '{{ continue }}';
		}
	});
});
//--></script>
