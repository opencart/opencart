
(function(){

	function hash(str) {
		let hash = 0;
		for (let i = 0, len = str.length; i < len; i++) {
			let chr = str.charCodeAt(i);
			hash = (hash << 5) - hash + chr;
			hash |= 0;
		}
		return hash;
	}

	$(window).on('load', function (){
		$(".popup-modal").each(function(){
			let $modal = $(this);
			let modalElement = this;
			let initialDelay = parseInt($modal.attr('data-initial-delay'));
			let minimumWidth = parseInt($modal.attr('data-minimum-width'));
			let languageId = parseInt($modal.attr('data-language-id'));
			let showEverytime = $modal.attr('data-show-everytime');
			let timeToClose = parseInt($modal.attr('data-time-to-close'));

			let showOnce = parseInt(showEverytime) !== 1;
			let modalHash = calculateModalHash($modal).toString();
			let showPopup = true;

			if(showOnce){
				let isPopupAlreadyShownBefore = localStorage.getItem('popup-modal-' + languageId.toString() + '-' + modalHash) === '1';
				if(isPopupAlreadyShownBefore){
					showPopup = false;
				}else{
					// After the modal is closed, register a record to user's local storage.
					modalElement.addEventListener('hide.bs.modal', function (event) {
						localStorage.setItem('popup-modal-' + languageId.toString() + '-' + modalHash, '1');
					});
				}
			}

			if(showPopup){
				$modal.find('.modal-dialog').css({'min-width': minimumWidth.toString() + 'px'});
				let bootstrapModal = null;

				function openPopup(){
					bootstrapModal = new bootstrap.Modal(modalElement, {
						backdrop: 'static',
						keyboard: false
					});
					bootstrapModal.show();
					startTimer();
				}

				function startTimer(){
					let $popupTimer = $modal.find('.popup-timer');
					let timerTextForPlural = $popupTimer.attr('data-text-for-plural');
					let timerTextForSingular = $popupTimer.attr('data-text-for-singular');
					let counter = timeToClose;

					if(counter === 1){
						$popupTimer.text(timerTextForSingular.replace(/%d/g, counter.toString()));
					}else if(counter > 1){
						$popupTimer.text(timerTextForPlural.replace(/%d/g, counter.toString()));
					}

					let intervalId = setInterval(function(){
						counter -= 1;
						if(counter === 0){
							clearInterval(intervalId);
							bootstrapModal.hide();
						}else if(counter === 1){
							$popupTimer.text(timerTextForSingular.replace(/%d/g, counter.toString()));
						}else if(counter > 1){
							$popupTimer.text(timerTextForPlural.replace(/%d/g, counter.toString()));
						}
					}, 1000);
				}

				if(initialDelay < 1){
					openPopup();
				}else{
					setTimeout(openPopup, 1000 * initialDelay);
				}
			}
		});
	});

	function calculateModalHash($modal){
		let totalContent = $modal.find('.modal-header').html() + $modal.find('.modal-body').html();
		totalContent = totalContent.replace(/\s+/ig, ' ');
		return hash(totalContent);
	}

})();

