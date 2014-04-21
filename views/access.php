<?php
session_start();
if (!isset($_SESSION['EXPIRES']) || $_SESSION['EXPIRES'] < time()) {
	session_destroy();
	$_SESSION = array();
}	

/*if(key_exists('uname', $_POST)){
	$uname = $_POST['uname'];
}
else*/
if (key_exists('uname', $_SESSION) && (!isset($uname)|| $uname!="")){
	$uname = $_SESSION['uname'];
}

if(!isset($uname)){
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
	<head>
		<title>PhotoBook - Login</title>
		<link href="/photobook/css/default.css" rel="stylesheet" />
		<script src="/photobook/js/jquery.js"></script>
		<script type="text/javascript">
			var self = "<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>";
			$(document).ready(function() {
				$("#loginform").submit(function(event) {
					event.preventDefault();
					$("#errmsg").hide();
					$.post('/photobook/php/validate_user.php', $(this).serialize(), function(data) {

						if (data.errorcode == 1) {
							$("#errmsg").html("<p>" + data.errormsg + "</p>").show();
						} else if (data.errorcode == 2) {
							$("#errmsg").html("<p>" + data.errormsg + "</p>").show();
						}
						else{
							window.location.replace(self);
						}
					},"json");
				});
				document.getElementsByName("uname")[0].focus();
			});
		</script>
	</head>
	<body>
		<div align="center">
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
			<br />
		</div>
		<div style="text-align: center;">
			<a href="/photobook"><img src="/photobook/css/images/logo_large.png" alt="logo" /></a>
			<h1 class="entry-title"> You must login to access PhotoBook </h1>
			<form id="loginform" method="post">
				<table>
					<tr><td><lable>
					User Name:
				</lable></td><td><input name="uname" type="text"></td></tr>
				<tr><td><lable>
					Password:
				</lable></td><td><input name="pwd" type="password"></td></tr>
				<tr><td><br></td><td><br></td></tr>
				<tr><td><input value="Login" type="submit"></td><td><a href="/photobook/views/signup.html">Signup here</a></td></tr>
				
				
				</table>
			</form>
		</div>
		<div class="ui-error" hidden id="errmsg"></div>
	</body>
</html>
<?php
	exit();
}
else{
	$_SESSION['EXPIRES'] = time()+360;
}
?>