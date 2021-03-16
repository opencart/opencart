$(document).ready(function () {
	let total = $('#button-upgrade').data('total');
	let step = 0;

	$('#button-upgrade').on('click', function () {
		step = 0;
		$('#button-upgrade').prop('disabled', true);
		$('#button-upgrade').button('loading');
		$('#button-upgrade > i').addClass('fa-spin');
		$('#progress-bar').addClass('bg-success progress-bar-animated').css('width', '0%').removeClass('bg-danger');
		$('#progress-text').html('');
		$('#button-upgrade').button('loading');

		upgrade('index.php?route=upgrade/upgrade|next');
	});

	var upgrade = function (url) {
		$.ajax({
			url: url,
			type: 'post',
			dataType: 'json',
			success: function (json) {
				if (json['error']) {
					$('#progress-bar').addClass('progress-bar-danger');
					$('#progress-text').html('<div class="text-danger">' + json['error'] + '</div>');

					$('#button-upgrade').prop('disabled', false);
					$('#button-upgrade > i').removeClass('fa-spin');
					$('#button-upgrade').button('reset');
				}

				if (json['success']) {
					$('#progress-text').html('<span class="text-success">' + json['success'] + '</span>');
					$('#progress-bar').css('width', ((step / total) * 100) + '%');
				}

				if (json['next']) {
					next = json['next'];
				} else {
					next = '';
				}

				if (!json['error']) {
					// $('#button-upgrade').addClass('d-none');
					// $('#button-upgrade').button('reset');
					// $('#button-continue').removeClass('d-none');
				}

				step++;
			},
			error: function (xhr, ajaxOptions, thrownError) {
				console.log(xhr, ajaxOptions, thrownError)
				$('#progress-bar').removeClass('bg-success progress-bar-animated').addClass('bg-danger');
				$('#progress-text').html('<span class="text-danger">' + (thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText) + '</span>');
				$('#button-upgrade').button('reset');
			}
		}).done(function () {
			if (next) {
				console.log(next)
				upgrade(next);
			}
		});
	}
});
