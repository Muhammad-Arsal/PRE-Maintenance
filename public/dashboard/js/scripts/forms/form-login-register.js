$(document).ready(function(){
	'use strict';
		$('#loginForm').validate({
				 highlight: function(element) {
				 $(element).parent('fieldset').addClass('form-error');
			 },
			 unhighlight: function(element) {
				 $(element).parent('fieldset').removeClass('form-error');
			 },
			  rules:{
				  email:{
					   required:true,
					   email:true,
				  },
				  password:{
					  required:true,
				  }
			  },
			  messages:{
				  email:'The email field is required',
				  password:{
					 required: "The password field is required",
					 minlength: "Your password must be at least 5 characters long"
				  }
			  },
			  errorPlacement: function (error, element) {

					 if (element.attr("class").indexOf("form-control") != -1) {
						 // $(".dropdown-toggle").text(error);
						  var mpar = $(element).closest(".form-group");
						  console.log(mpar);
						 error.insertAfter(mpar);

					 } else {
						 error.insertAfter(element);
					 }
			 }

			});
			$('#registerForm').validate({
				rules:{
                    fname: {
                        required: true,
                        minlength: 3,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    phone_number: 'required',
                    password: {
                        required: true,
                        minlength: 6,
                    },
                    confirmPassword: {
                        required: true,
                        minlength: 6,
                        equalTo: '#password',
                    },
				},
				messages:{
                    fname: 'The first name field is required',
                    lname: 'The last name field is required',
                    email: 'The email field is required',
                    phone_number: 'The phone number field is required',
                    password: {
                        required: "The password field is required",
                        minlength: "Your password must be at least 6 characters long",
                    },
                    confirmPassword: {
                        required: "The confirm password field is required",
                        minlength: "The confirm password must be at least 6 characters long",
                        equalTo:'Confirm Password should be equal to Password'
                    },
				},

		   });

		   $('#generalRegisterForm').validate({
			rules:{
				fname: {
					required: true,
					minlength: 3,
				},
				email: {
					required: true,
					email: true,
				},
				phone_number: 'required',
				password: {
					required: true,
					minlength: 6,
				},
				confirmPassword: {
					required: true,
					minlength: 6,
					equalTo: '#password',
				},
				user_type: 'required',
			},
			messages:{
				fname: 'The first name field is required',
				lname: 'The last name field is required',
				email: 'The email field is required',
				phone_number: 'The phone number field is required',
				password: {
					required: "The password field is required",
					minlength: "Your password must be at least 6 characters long",
				},
				confirmPassword: {
					required: "The confirm password field is required",
					minlength: "The confirm password must be at least 6 characters long",
					equalTo:'Confirm Password should be equal to Password'
				},
				user_type: "The User Type field is required",
			},

	   });
		  $('input').on('focusout keyup', function () {
			  $('.submit-error').remove();
			 $(this).valid();
		  });
	//Login Register Validation
	if($("form.form-horizontal").attr("novalidate")!=undefined){
		$("input,select,textarea").not("[type=submit]").jqBootstrapValidation();
	}

	// Remember checkbox
	if($('.chk-remember').length){
		$('.chk-remember').iCheck({
			checkboxClass: 'icheckbox_square-blue',
			radioClass: 'iradio_square-blue',
		});
	}
});
