function validateForm(form) {
	var elements = form.find('input[required], textarea[required]').not('input[type="file"], input[type="hidden"]'),
			valid = true;
		
		// Пройдемся по всем элементам формы
		$.each(elements, function (index, val) {
			var element = $(val),
				val = element.val();

			if(val.length === 0) {
				valid = false;
			}

			if (element.attr('type') == 'email') {
				var rv_email = /^([a-zA-Z0-9_.-])+@([a-zA-Z0-9_.-])+\.([a-zA-Z])+([a-zA-Z])+/;
				if(val != '' && !val.match(rv_email)) {
					valid = false;
				}
			}

			if (element.attr('type') == 'tel') {
				var rv_tel = /^\s*(?:\+?(\d{1,3}))?([-. (]*(\d{3})[-. )]*)?((\d{2,3})[-. ]*(\d{2,4})(?:[-.x ]*(\d+))?)\s*$/;
				if(val != '' && !val.match(rv_tel)) {
					valid = false;
				}
			}

		});

		if (valid) {
			form.removeClass('error');
		} else {
			form.addClass('error');
			form.find('.form__error').text('Неверно заполнены поля');
			valid = false;
		}
		return valid;
}


$(document).on('click', '.js-submit', function(event) {
	event.preventDefault();
	var form = $(this).parents('form');
	if(validateForm(form)){
		$.ajax({
			url: '/local/ajax/subscribe.php',
			type: 'post',
			data: form.serialize(),
			success: function (res) {
				if( parseInt(res) ){
					form.addClass('success').html('<div class="subscribe__wrap">Спасибо! Мы отправили вам письмо, чтобы подтвердить e-mail</div>');
				}else{
					form.addClass('error');
					form.find('.subscribe__error').html(res);
				}
			}
		});
	}
});