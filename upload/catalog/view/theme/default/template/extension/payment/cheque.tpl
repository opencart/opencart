<h2>{{ text_instruction }}</h2>
<div class="well well-sm">
  <p><b>{{ text_payable }}</b></p>
  <p>{{ payable }}</p>
  <b>{{ text_address }}</b><br />
  <p>{{ address }}</p>
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
		url: 'index.php?route=extension/payment/cheque/confirm',
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
