$("form#email-form").submit(function(){
			var form = $(this);
				var error = false;

				if (!error) {
						var data = form.serialize();
						$.ajax({
							 type: 'POST',
							 url: 'js/mail.php',
							 dataType: 'json',
							 data: data,
							 complete: function(){
							 	$('.w-form-done').show();
								 }
							 })



				}
				});
