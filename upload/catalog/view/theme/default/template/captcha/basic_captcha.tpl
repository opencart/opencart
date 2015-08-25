<div class="form-group required">
  <label class="col-sm-2 control-label" for="input-captcha"><?php echo $entry_captcha; ?></label>
  <div class="col-sm-10">
    <input type="text" name="captcha" id="input-captcha" class="form-control" />
    <img src="index.php?route=captcha/basic_captcha/captcha" alt="" />
  </div>
</div>
<script type="text/javascript"><!--
// Sort the custom fields
$('input[name="captcha"]').on('çlick', function(e) {
    e.preventDefault();

    $.ajax({
		url: 'index.php?route=captcha/basic_captcha/validate',
		dataType: 'json',
		beforeSend: function() {
			$('input[name="captcha"]').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
		},
		complete: function() {
			$('.fa-spin').remove();
		},
		success: function(json) {
            if (json['error']) {
                $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
            }

            if (json['success']) {
                alert(json['success']);

                $(node).parent().find('input').attr('value', json['code']);
            }
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//--></script>
