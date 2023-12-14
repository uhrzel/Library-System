<!DOCTYPE html>
<?php
session_start();
if (isset($_SESSION['admin_id'])) {
	header('location:home.php');
}

// Check if captcha code is not set in the session, then set a new one
if (!isset($_SESSION['captcha_code'])) {
	$_SESSION['captcha_code'] = rand(1000, 9999);
}
$captchaCode = $_SESSION['captcha_code'];
?>
<html lang="eng">

<head>
	<title>Library System</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="stylesheet" type="text/css" href="css/bootstrap.css" />
	<style>
		.captcha-agileits {
			margin-bottom: 20px;
		}

		.email {
			display: block;
			font-size: 16px;
			margin-bottom: 5px;
		}

		.captcha {
			padding: 10px;
			font-size: 18px;
			color: black;
			width: 100%;
			box-sizing: border-box;
			border: 2px solid #ccc;
			border-radius: 5px;
		}
	</style>
</head>

<body style="background-color:#d3d3d3;">
	<nav class="navbar navbar-default navbar-fixed-top">
		<div class="container-fluid">
			<div class="navbar-header">
				<img src="images/logo.png" width="50px" height="50px" />
				<h4 class="navbar-text navbar-right">Library System</h4>
			</div>
		</div>
	</nav>
	<div class="container-fluid" style="margin-top:70px;">
		<div class="col-lg-3 well">
			<br />
			<br />
			<h4>Login Here..</h4>
			<hr style="border:1px solid #d3d3d3; width:100%;" />
			<form enctype="multipart/form-data">
				<div id="email_warning" class="form-group">
					<label class="control-label">Email:</label>
					<input type="text" class="form-control" id="email" />
				</div>
				<div id="password_warning" class="form-group">
					<label class="control-label">Password:</label>
					<input type="password" class="form-control" id="password" />
				</div>
				<div class="captcha-container">
					<label class="control-label">CAPTCHA:</label>
					<input type="text" name="captcha" class="form-control captcha" id="captcha" placeholder="<?php echo $captchaCode; ?>" readonly />
				</div>
				<div class="captcha-container">
					<label class="control-label">Input Captcha:</label>
					<input type="text" name="captcha" class="form-control" id="captchaInput" placeholder="Enter CAPTCHA" required />
				</div>
				<br />
				<div class="form-group">
					<button type="button" id="login" class="btn btn-primary btn-block"><span class="glyphicon glyphicon-save"></span> Login</button>
				</div>
			</form>
			<div id="result"></div>
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
		</div>
		<div class="col-lg-1"></div>
		<div class="col-lg-8 well">
			<img src="images/back.jpg" height="449px" width="100%" />
		</div>
	</div>
	<nav class="navbar navbar-default navbar-fixed-bottom">
		<div class="container-fluid">
			<label class="navbar-text pull-right">Library System &copy; All rights reserved 2016</label>
		</div>
	</nav>
</body>
<script src="js/jquery.js"></script>
<script src="js/bootstrap.js"></script>
<script>
	$(document).ready(function() {
		$error = $(
			'<center><label class="text-danger">Please fill up the form</label></center>'
		);
		$error1 = $(
			'<center><label class="text-danger">Invalid email, password, or CAPTCHA</label></center>'
		);
		$error2 = $(
			'<center><label class="text-danger">Incorrect CAPTCHA</label></center>'
		);
		$loading = $('<center><img src="images/378.gif" height="10px"/></center>');
		$("#login").click(function() {
			$error.remove();
			$error1.remove();
			$error2.remove();
			$("#email").focus(function() {
				$("#email_warning").each(function() {
					$(this).removeClass("has-error has-feedback");
					$(this).find("span").remove();
				});
			});
			$("#password").focus(function() {
				$("#password_warning").each(function() {
					$(this).removeClass("has-error has-feedback");
					$(this).find("span").remove();
				});
			});
			$("#captchaInput").focus(function() {
				$(".captcha-container").each(function() {
					$(this).removeClass("has-error has-feedback");
					$(this).find("span").remove();
				});
			});

			$email = $("#email").val();
			$password = $("#password").val();
			$enteredCaptcha = $("#captchaInput").val();
			$generatedCaptcha = $("#captcha").attr("placeholder");

			if ($email == "" || $password == "" || $enteredCaptcha == "") {
				$error.appendTo("#result");
				if ($email == "") {
					$("#email_warning").addClass("has-error has-feedback");
					$(
						'<span class="glyphicon glyphicon-remove form-control-feedback"></span>'
					).appendTo("#email_warning");
				}
				if ($password == "") {
					$("#password_warning").addClass("has-error has-feedback");
					$(
						'<span class="glyphicon glyphicon-remove form-control-feedback"></span>'
					).appendTo("#password_warning");
				}
				if ($enteredCaptcha == "") {
					$(".captcha-container").addClass("has-error has-feedback");
					$(
						'<span class="glyphicon glyphicon-remove form-control-feedback"></span>'
					).appendTo(".captcha-container");
				}
			} else {
				// Validate CAPTCHA
				if ($enteredCaptcha != $generatedCaptcha) {
					$error2.appendTo("#result");
					$(".captcha-container").addClass("has-error has-feedback");
					$(
						'<span class="glyphicon glyphicon-remove form-control-feedback"></span>'
					).appendTo(".captcha-container");
					return;
				}

				$loading.appendTo("#result");
				setTimeout(function() {
					$.post(
						"check_admin.php", {
							email: $email,
							password: $password
						},
						function(result) {
							if (result == "Success") {
								sendEmailNotification($email); // Call the function to send email notification
								window.location = "home.php";
							} else {
								$loading.remove();
								$error1.appendTo("#result");
							}
						}
					);
				}, 3000);


			}
		});

		function sendEmailNotification($email) {
			$.ajax({
				type: "POST",
				url: "send_email.php",
				data: {
					email: $email
				},
				success: function(data) {
					console.log(data); // You can log the response or handle it as needed
				}
			});
		}
	});
</script>

</html>