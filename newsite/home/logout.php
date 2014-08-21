<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Admin Log Out</title>	
    <link rel="stylesheet" type="text/css" href="/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="/stylesheets/basic.css">
</head>
<body>
<?php 
	session_start();
	session_unset();
	session_destroy();
	session_write_close();
	setcookie(session_name(),'',0,'/');
	session_regenerate_id(true);
	echo "<font color='red'><h1>You are now logged out!</h1></font>";
?>
<script type="text/JavaScript">
<!--
setTimeout("location.href = 'login.php';",2000);
-->
</script>
</body>
</html>