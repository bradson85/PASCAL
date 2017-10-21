/* Sign-in form validation */
alert("Admin Login = admin and any 8 character password \nTeacher Login = any 5 character username and any 8 character password");

$(function() {
	var $username = $("#login-username");
	var $password = $("#login-password");
	var $linkText = $("#theButton");
//	alert("in the funct");
	
	$linkText.click(function(event){
//		alert("got the click");
		var patUsr = /^[a-zA-Z0-9]{5,}$/;
		var patPass = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])[0-9a-zA-Z]{8,}$/;
		var isUsrValid = patUsr.test($username.val());
		var isPassValid = patPass.test($password.val());
		
		if($username.val() == null || $username.val() == ""){
			event.preventDefault();
			$username.attr("placeholder", 'Must enter a value');
			$username.addClass('fieldError');
//			alert("usrnm err");
		}
		if($password.val() == null || $password.val() == ""){
			event.preventDefault();
			$password.attr("placeholder", 'Must enter a value');
			$password.addClass('fieldError');
//			alert("psswrd err");
		}
		if(!isUsrValid){
			event.preventDefault();
			$username.attr("placeholder", 'Must be 5 or more characters');
			$username.addClass('fieldError');
		}
		if(!isPassValid){
			event.preventDefault();
			$password.attr("placeholder", 'Must be 8 or more characters');
			$password.addClass('fieldError');
		}
//		alert("checked fields");
	});
	
	$username.click(function(){
		$username.removeClass();
		$username.attr("placeholder", '');
	});
	$password.click(function(){
		$password.removeClass();
		$password.attr("placeholder", '');
	});
	
});