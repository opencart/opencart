/**
 * CSP handler delegation.
 *
 * Replaces inline event handler attributes (onclick="...") so pages can run
 * under a strict Content-Security-Policy (script-src with a per-request
 * nonce). Handlers are declared as data-attributes instead:
 *
 *   data-confirm="message"      ask for confirmation before following the action
 *   data-remove="#selector"     remove the matched element
 *   data-toggle="#selector"     toggle the d-none class on the matched element
 *   data-select-all             click all input[name*="selected"] checkboxes
 *   data-call="fn"              call window.fn(), optional data-call-arg="..."
 *   data-location="url"         navigate to the url
 *
 * Delegation on document also covers rows added dynamically via JS.
 */
document.addEventListener('click', function(e) {
	const target = e.target.closest('[data-confirm], [data-remove], [data-toggle], [data-select-all], [data-call], [data-location]');

	if (!target) {
		return;
	}

	if (target.hasAttribute('data-confirm')) {
		if (!window.confirm(target.getAttribute('data-confirm'))) {
			e.preventDefault();
			e.stopPropagation();
			return;
		}
	}

	if (target.hasAttribute('data-location')) {
		window.location = target.getAttribute('data-location');
		return;
	}

	if (target.hasAttribute('data-remove')) {
		const element = document.querySelector(target.getAttribute('data-remove'));

		if (element) {
			element.remove();
		}
	}

	if (target.hasAttribute('data-toggle')) {
		const element = document.querySelector(target.getAttribute('data-toggle'));

		if (element) {
			element.classList.toggle('d-none');
		}
	}

	if (target.hasAttribute('data-select-all')) {
		document.querySelectorAll('input[name*=\'selected\']').forEach(function(checkbox) {
			checkbox.click();
		});
	}

	if (target.hasAttribute('data-call')) {
		const fn = window[target.getAttribute('data-call')];

		if (typeof fn === 'function') {
			fn(target.getAttribute('data-call-arg'));
		}
	}

	if (target.hasAttribute('data-confirm')) {
		e.preventDefault();
	}
}, false);
