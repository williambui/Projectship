<!DOCTYPE html>
<html lang="en">
<head>
  <title>Sign Up Page</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>
<body>


<?php
	require_once("support.php");
	$host = "localhost";
	$user = "dbuser";
	$dbpassword = "goodbyeWorld";
	$database = "projectship";
	$table = "userprofiles";
	/* Connecting to the database */		
	$db_connection = new mysqli($host, $user, $dbpassword, $database);
	if ($db_connection->connect_error) {
		die($db_connection->connect_error);
	}
	
	$topPart = <<<BODY
	<div class="container">
	  <h1>Sign Up Form</h1>
	  <h2> Join With Your Email Address </h2>
	  <br>
	  <br>

	 <form action="{$_SERVER["PHP_SELF"]}" method="post">
	    <div class="form-group">
	      <label for="email">Email:</label>
	      <input type="email" class="form-control" id="email" placeholder="Enter email" name="email" required="required">
	    </div>
	    <div class="form-group">
	      <label for="pwd">Password:</label>
	      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="password" required="required">
	    </div>
	    <div class="form-group">
	      <label for="pwd">Re-enter Password:</label>
	      <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="repassword" required="required">
	    </div>
	    <input type="submit" class="btn btn-default" name="createAccount" value = "Create Account" /> 
	    <input type="button" onclick="location.href='main.html';" class="btn btn-default" value="Home Page" />
	  </form>
	</div>

BODY;
	
	$bottomPart = "";
	if (isset($_POST["createAccount"])) {
		$email = trim($_POST["email"]);
		$password = trim($_POST["password"]);
		$repassword = trim($_POST["repassword"]);
		/* CHECK IF EMAIL ALREADY EXISTS */
		$emailTaken = false;
		$query = "select email from $table";
		$result = $db_connection->query($query);
		
		if ($result) {
			$num_rows = $result->num_rows;
			if ($num_rows === 0) {
				echo "Empty Table<br>";
			} else {
				for ($row_index = 0; $row_index < $num_rows; $row_index++) {
					$result->data_seek($row_index);
					$row = $result->fetch_array(MYSQLI_ASSOC);
					$dbEmail = $row['email'];
		     		if ($email == $dbEmail){
			     		$emailTaken = true;
		     		}
				}
			}
			$result->close();
		}  else {
			die("Retrieval failed: ". $db_connection->error);
		}
		//Login Validation
		if ($email == "" || $password == "" || $repassword == "" || $password != $repassword) {
			$bottomPart = "<center><h1>Invalid login information provided</h1><br /></center>";
		} else if ($emailTaken) {
			$bottomPart = "<center><h1>An account with that email already exists</h1><br /></center>";
		} else {			
			$hashpass = password_hash($password, PASSWORD_DEFAULT);
			/* Query */
			$query = "insert into $table (email, password) values(\"$email\", \"$hashpass\")";
			/* Executing query */
			$result = $db_connection->query($query);
			if (!$result) {
				die("Insertion failed: " . $db_connection->error);
			}
			header("Location: accountCreated.html");
		}
	}
	
	$db_connection->close();
	$page = generatePage($topPart.$bottomPart, "Create Account");
	echo $page;
?>


</body>
</html>
