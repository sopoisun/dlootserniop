var Login = function () {

	var handleLogin = function() {
		$('.login-form').validate({
	            errorElement: 'span', //default input error message container
	            errorClass: 'help-block', // default input error message class
	            focusInvalid: false, // do not focus the last invalid input
	            rules: {
	                email: {
	                    required: true,
						email: true
	                },
	                password: {
	                    required: true
	                },
	                remember: {
	                    required: false
	                }
	            },

	            messages: {
	                email: {
	                    required: "E-mail tidak boleh kosong.",
						email: "Input harus e-mail"
	                },
	                password: {
	                    required: "Password tidak boleh kosong."
	                }
	            },

	            invalidHandler: function (event, validator) { //display error alert on form submit
	                $('.alert-error', $('.login-form')).show();
	            },

	            highlight: function (element) { // hightlight error inputs
	                $(element)
	                    .closest('.form-group').addClass('has-error'); // set error class to the control group
	            },

	            success: function (label) {
	                label.closest('.form-group').removeClass('has-error');
	                label.remove();
	            },

	            errorPlacement: function (error, element) {
	                error.insertAfter(element.closest('.input-icon'));
	            },

	            submitHandler: function (form) {
					//form.submit();

					var formObj = $(form);
					var email = formObj.find("input[name='email']");
					var password = formObj.find("input[name='password']");
					var button = formObj.find("button");

					email.attr('readonly', 'readonly');
					password.attr('readonly', 'readonly');
					button.addClass('disabled');

					CFirebase.login(email.val(), password.val())
						.then(function(error){
							console.log("Sukses login firebase")
							form.submit();
				    	})
						.catch(function(error){
							//console.log(error);
							if( error.code == "auth/user-not-found" || error.code == "auth/wrong-password" ){
								password.val("");
								email.parent().parent().addClass('has-error');
								email.parent().after('<span class="help-block">E-mail / password tidak terdaftar</span>');
							}else{
								toastr.options.closeButton = true;
					            toastr.options.positionClass = "toast-bottom-right";
								toastr.error(error.message);
							}

							email.removeAttr('readonly', 'readonly');
							password.removeAttr('readonly', 'readonly');
							button.removeClass('disabled');
					    });

					return false;
	            }
	        });

	        $('.login-form input').keypress(function (e) {
	            if (e.which == 13) {
	                if ($('.login-form').validate().form()) {
	                    $('.login-form').submit();
	                }
	                return false;
	            }
	        });
	}

    return {
        //main function to initiate the module
        init: function () {

            handleLogin();

        }

    };

}();
