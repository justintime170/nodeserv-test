<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Admin Login Page</title>
	<link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap.css">	
	<link rel="stylesheet" type="text/css" href="/stylesheets/login.css">
</head>
<body>
<?php
	require '/var/www/libraries/password.php';

	session_start();
	if(isset($_SESSION['username']) && isset($_SESSION['password']))
	{
		if(isset($_GET["sub_info"])):
?>
<script type="text/javascript">
	var new_pass = "<?php echo $_POST["new_pass"]; ?>";
	var new_pass_con = "<?php echo $_POST["new_pass_con"]; ?>";
	if(new_pass == new_pass_con)
	{
		<?php
			if(password_verify($_POST["old_pass"], $_SESSION["password"]))
			{
				$con_confirm = @mysql_connect("localhost", "justin", "donkeykong");
				if(!$con_confirm)
				{
					echo("<p>Error Connecting to Server</p>");
					exit();
				}
				$sel_conf = @mysql_select_db("site");
				if(!$sel_conf)
				{
					echo("<p>Error selecting database:</p>");
					exit();
				}
				$username = $_SESSION["username"];
				$options = [ 'cost' => 12,];
				$password = password_hash($_POST["new_pass"], PASSWORD_BCRYPT, $options);
				
				$entry = "UPDATE site.users SET Password='$password' WHERE Username='$username'";

				if(mysql_query($entry))
				{
					echo 'document.write("<p>Password Successfully Changed<br> ")';
					?> 
						setTimeout("location.href = 'home.php';",1000); 
					<?php
   				}
				else
				{
					echo 'document.write("<p>Failed to submit information</p>")';
				}
			}
			else
			{
				echo 'document.write("Incorrect Old Password!!")';
			}
		?>
	}
	else
	{
		document.write("Passwords Dont Match!!");
	}
</script>	

<?php	
	else:
?>
<!--<form action="chngpass.php?sub_info=1" method=POST autocomplete=OFF>
	<p><input type=PASSWORD name="old_pass"><label for="old_pass">Old Password</label></p>
	<p><input type=PASSWORD name="new_pass"><label for="new_pass">New Password</label></p>
	<p><input type=PASSWORD name="new_pass_con"><label for="new_pass_con">New Password Confirm</label></p>
	<p class="submit"><input type=SUBMIT value="Submit"></p>
</form>-->

<div class="container">
	<div class="col-md-6 col-md-offset-3 hidden-xs hidden-sm hidden-md">
		<div class="well">
			<form class="logform" role="form" action="chngpass.php?sub_info=1" method=POST autocomplete=OFF>
				<div class="form-group">
					<label for="old_pass">Old Password</label>
					<input type="password" class="form-control" id="old_pass" name="old_pass" placeholder="Old Password">
				</div>
				<div class="form-group">
					<label for="new_pass">New Password</label>
					<input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="New Password">
				</div>
				<div class="form-group">
					<label for="new_pass_con">Confirm New Password</label>
					<input type="password" class="form-control" id="new_pass_con" name="new_pass_con" placeholder="Confirm New Password">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-3 hidden-xs hidden-sm hidden-lg">
		<div class="well">
			<form class="logform" role="form" action="chngpass.php?sub_info=1" method=POST autocomplete=OFF>
				<div class="form-group">
					<label for="old_pass">Old Password</label>
					<input type="password" class="form-control" id="old_pass" name="old_pass" placeholder="Old Password">
				</div>
				<div class="form-group">
					<label for="new_pass">New Password</label>
					<input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="New Password">
				</div>
				<div class="form-group">
					<label for="new_pass_con">Confirm New Password</label>
					<input type="password" class="form-control" id="new_pass_con" name="new_pass_con" placeholder="Confirm New Password">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-3 hidden-lg hidden-md hidden-xs">
		<div class="well">
			<form class="logform" role="form" action="chngpass.php?sub_info=1" method=POST autocomplete=OFF>
				<div class="form-group">
					<label for="old_pass">Old Password</label>
					<input type="password" class="form-control" id="old_pass" name="old_pass" placeholder="Old Password">
				</div>
				<div class="form-group">
					<label for="new_pass">NewPassword</label>
					<input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="New Password">
				</div>
				<div class="form-group">
					<label for="new_pass_con">Confirm New Password</label>
					<input type="password" class="form-control" id="new_pass_con" name="new_pass_con" placeholder="Confirm New Password">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-3 hidden-lg hidden-md hidden-sm">
		<div class="well">
			<form class="logform" role="form" action="chngpass.php?sub_info=1" method=POST autocomplete=OFF>
				<div class="form-group">
					<label for="old_pass">Old Password</label>
					<input type="password" class="form-control" id="old_pass" name="old_pass" placeholder="Old Password">
				</div>
				<div class="form-group">
					<label for="new_pass">NewPassword</label>
					<input type="password" class="form-control" id="new_pass" name="new_pass" placeholder="New Password">
				</div>
				<div class="form-group">
					<label for="new_pass_con">Confirm New Password</label>
					<input type="password" class="form-control" id="new_pass_con" name="new_pass_con" placeholder="Confirm New Password">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>
</div>

<?php
	endif;
}
else
{
	echo "UNAUTHORIZED USER";
}
?>
</body>
</html>