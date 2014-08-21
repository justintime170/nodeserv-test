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
	if(isset($_GET["sub_info"])):
		
		$con_confirm = @mysql_connect("localhost", "pagelogin", "pagelogin");
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
		$username = $_POST["username"];
		$options = [ 'cost' => 12,];
		$password = password_hash($_POST["password"], PASSWORD_BCRYPT, $options);
		
		$entry = "INSERT INTO site.users (username, password, id) VALUES ('$username', '$password', NULL)";

		$curr_data = mysql_query("select Username from users");
		if(!$curr_data)
		{
			echo("<p>Query for existing data failed: " . mysql_erre() . "</p>");
			exit();
		}
		while($info_row = mysql_fetch_array($curr_data))
		{
			if(strcmp($username, $info_row["Username"]) == 0)
			{
				echo("<p>There is already an entry in the cue under that name.<br>");
				echo("Please do not repeat entries for the same user.</p>");
				exit();
			}
		}
		if(mysql_query($entry))
		{
			echo("<p><h1>New User Created Successfully!</h1></p>");
			?> 
				<script type="text/JavaScript"> setTimeout("location.href = 'home.php';",2000); </script> 
			<?php
		}
		else
		{
			echo("<p>Failed to submit information</p>");
			?> 
				<script type="text/JavaScript"> setTimeout("location.href = 'new_user_free.php';",2000); </script> 
			<?php
		}

		
			
		
	else:
		//if(strcmp($_SESSION['username'], "admin") == 0)
		//{
?>
<!-- <form action="new_user.php?sub_info=1" method=POST autocomplete=OFF>
	<p><input type=TEXT name="username"><label for="username">Username</label></p>
	<p><input type=PASSWORD name="password"><label for="password">Password</label></p>
	<p class="submit"><input type=SUBMIT value="Submit"></p>
</form> -->

<div class="container-full">
	<div class="col-md-6 col-md-offset-3 hidden-xs hidden-sm hidden-md">
		<div class="well">
			<form class="logform" role="form" action="new_user_free.php?sub_info=1" method=POST autocomplete=OFF>
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Enter Username">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Password">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-3 hidden-xs hidden-sm hidden-lg">
		<div class="well">
			<form class="logform" role="form" action="new_user_free.php?sub_info=1" method=POST autocomplete=OFF>
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Enter Username">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Password">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-3 hidden-lg hidden-md hidden-xs">
		<div class="well">
			<form class="logform" role="form" action="new_user_free.php?sub_info=1" method=POST autocomplete=OFF>
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Enter Username">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Password">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>
	<div class="col-md-6 col-md-offset-3 hidden-lg hidden-md hidden-sm">
		<div class="well">
			<form class="logform" role="form" action="new_user_free.php?sub_info=1" method=POST autocomplete=OFF>
				<div class="form-group">
					<label for="username">Username</label>
					<input type="text" class="form-control" id="username" name="username" placeholder="Enter Username">
				</div>
				<div class="form-group">
					<label for="password">Password</label>
					<input type="password" class="form-control" id="password" name="password" placeholder="Password">
				</div>
				<button type="submit" class="btn btn-default">Submit</button>
			</form>
		</div>
	</div>
</div>

<?php
		/*}
		else
		{
			echo("<p><h1>Sorry, You dont have access to this function.</h1></p>");
			?> 
				<script type="text/JavaScript"> setTimeout("location.href = 'home.php';",2500); </script> 
			<?php
		}*/
	endif;
?>
</body>
</html>