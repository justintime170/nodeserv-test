<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="description" content="">
	<meta name="author" content="">
	<title>Add New User</title>
	<link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap.css">	
</head>
<body>

<!-- <form action="new_user.php?sub_info=1" method=POST autocomplete=OFF>
	<p><input type=TEXT name="username"><label for="username">Username</label></p>
	<p><input type=PASSWORD name="password"><label for="password">Password</label></p>
	<p class="submit"><input type=SUBMIT value="Submit"></p>
</form> -->
<script type="text/javascript">
	function submit(id)
	{
		console.log("submit button clicked");
	}
</script>


<div class="container-full">
	<div class="col-md-6 col-md-offset-3">
		<div class="well">
			<div class="form-group">
				<label for="username">Username</label>
				<input type="text" class="form-control username-field" id="username" name="username" placeholder="Enter Username">
			</div>
			<div class="form-group">
				<label for="password">Password</label>
				<input type="password" class="form-control password-field" id="password" name="password" placeholder="Password">
			</div>
			<button class="btn btn-default" id='submit-button' onclick="submit(this.id)">Submit</button>
		</div>
	</div>
</div>

</body>
</html>