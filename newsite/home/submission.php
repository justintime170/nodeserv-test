<html>
<head>
	<title>Welcome to the PHP Test Submission Page</title>
	<link rel="stylesheet" type="text/css" href="/stylesheets/submission.css">
</head>
<body>
<?php 
	session_start();
?>
<?php
	if(isset($_SESSION['username']) && isset($_SESSION['password']))
	{	
?>
<?php if(isset($_GET['sub_info'])):

	$con_confirm = @mysql_connect("localhost", "justin", "donkeykong");
	if(!$con_confirm)
	{
		echo("<p>Error Connecting to Server</p>");
		exit();
	}
	$sel_conf = @mysql_select_db("form_entries");
	if(!$sel_conf)
	{
		echo("<p>Error selecting database:</p>");
		exit();
	}
	$ent_name = $_POST['name'];
	$ent_id = $_POST['pid'];
	$ent_item1 = $_POST['item1'];
	$ent_item2 = $_POST['item2'];
	$ent_item3 = $_POST['item3'];
	$entry = "insert into form1 set " .
			"Name='$ent_name', " .
			"Number='$ent_id', " .
			"Item_1='$ent_item1', " .
			"Item_2='$ent_item2', " .
			"Item_3='$ent_item3', " .
			"Time=NOW()";
	$curr_data = mysql_query("select Name from form1");
	if(!$curr_data)
	{
		echo("<p>Query for existing data failed: " . mysql_erre() . "</p>");
		exit();
	}
	while($info_row = mysql_fetch_array($curr_data))
	{
		if(strcmp($ent_name, $info_row["Name"]) == 0)
		{
			echo("<p>There is already an entry in the cue under that name.<br>");
			echo("Please do not repeat entries for the same user.</p>");
			exit();
		}
	}
	if(mysql_query($entry))
	{
		echo("<p>Information submitted successfully:<br> ");
		echo("Name: " . $ent_name . "<br> ");
		echo("Number: " . $ent_id . "<br> ");
		echo("Item 1: " . $ent_item1 . "<br> ");
		echo("Item 2: " . $ent_item2 . "<br> ");
		echo("Item 3: " . $ent_item3 . "<br></p>");
	}
	else
	{
		echo("<p>Failed to submit information</p>");
	}
?>

<?php 
	else:
?>

<form action="submission.php?sub_info=1" method=post>
	<h1>Please enter information below. All fields are required.</h1>	
	<p><input type="text" name="name" onfocus="if(this.value==this.defaultValue)this.value=''" onblur="if(this.value=='')this.value=this.defaultValue" value="First Last"><label for="name">Name</label></p>	
	<p><input type="text" name="pid" onfocus="if(this.value==this.defaultValue)this.value=''" onblur="if(this.value=='')this.value=this.defaultValue" value="4-Digit Number"><label for="number">Number</label></p>	
	<p><input type="text" name="item1" onfocus="if(this.value==this.defaultValue)this.value=''" onblur="if(this.value=='')this.value=this.defaultValue" value="Item Name"><label for="item_1">Item 1</label></p>	
	<p><input type="text" name="item2" onfocus="if(this.value==this.defaultValue)this.value=''" onblur="if(this.value=='')this.value=this.defaultValue" value="Item Name"><label for="item_2">Item 2</label></p>	
	<p><input type="text" name="item3" onfocus="if(this.value==this.defaultValue)this.value=''" onblur="if(this.value=='')this.value=this.defaultValue" value="Item Name"><label for="item_3">Item 3</label></p>
	<p class="submit"><input type=submit name="submit_info" value="Submit"><br></p>
</form>

<?php 
	endif;
}
else
{
	echo("<font color='red'> Unauthorized User. You do not have permission to view this page.</font>");
?>
<script type="text/JavaScript">
<!--
setTimeout("location.href = 'login.php';",2000);
-->
</script>
<?php } ?>
</body>
</html>
