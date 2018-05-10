<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="SemiColonWeb" />

	<!-- Stylesheets
	============================================= -->
	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="style.css" type="text/css" />
	<link rel="stylesheet" href="css/dark.css" type="text/css" />
	<link rel="stylesheet" href="css/font-icons.css" type="text/css" />
	<link rel="stylesheet" href="css/animate.css" type="text/css" />
	<link rel="stylesheet" href="css/magnific-popup.css" type="text/css" />

	<link rel="stylesheet" href="css/responsive.css" type="text/css" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!-- Document Title
	============================================= -->
	<title>Login</title>

</head>

<body class="stretched">

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">

		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap nopadding">

				<div class="section nopadding nomargin" style="width: 100%; height: 100%; position: absolute; left: 0; top: 0; background: url('images/parallax/home/7.jpg') center center no-repeat; background-size: cover;"></div>

				<div class="section nobg full-screen nopadding nomargin">
					<div class="container vertical-middle divcenter clearfix">

						<div class="row center">
							<a href="main.html"><img src="logo-white.png" alt="Canvas Logo"></a>
						</div>

						<div class="panel panel-default divcenter noradius noborder" style="max-width: 400px; background-color: rgba(255,255,255,0.93);">
							<div class="panel-body" style="padding: 40px;">
								<form id="login-form" name="login-form" class="nobottommargin" action="#" method="post">
									<h3>Login to your Account</h3>

									<div class="col_full">
										<label for="login-form-username">Email:</label>
										<input type="email" name="email" required="required" id="login-form-username" value="" class="form-control not-dark" />
									</div>

									<div class="col_full">
										<label for="login-form-password">Password:</label>
										<input type="password" name="password" required="required" id="login-form-password" value="" class="form-control not-dark" />
									</div>

									<div class="col_full nobottommargin">
										<input class="button button-3d button-black nomargin" type="submit" name="loginButton" value = "Login" />
										<input type="button" onclick="location.href='main.html';"  class="button button-3d button-black nomargin" value="Main Page" />
										<a href='signupPage.php' class="fright">Don't have an account?</a>
									</div>
								</form>

							</div>
						</div>
					</div>
				</div>

			</div>

		</section><!-- #content end -->

	</div><!-- #wrapper end -->

	<!-- Go To Top
	============================================= -->
	<div id="gotoTop" class="icon-angle-up"></div>

	<!-- External JavaScripts
	============================================= -->
	<script type="text/javascript" src="js/jquery.js"></script>
	<script type="text/javascript" src="js/plugins.js"></script>

	<!-- Footer Scripts
	============================================= -->
	<script type="text/javascript" src="js/functions.js"></script>

</body>
</html>

<?php
	require_once("support.php");

	if (session_id() == NULL) {
		session_start();
	}

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
	
	if (isset($_SESSION["currUser"])) {
		//session_unset();
		header("Location: homePage.php");
	}

	$bottomPart = "";

	if (isset($_POST["loginButton"])) {
		$email = trim($_POST["email"]);
		$password = trim($_POST["password"]);

		//CHECK DB FOR LOGIN

		/* VERIFY EMAIL AND PASSWORD */
		$verified = false;
		$query = "select email, password from $table";
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
					$dbPass = $row['password'];
					$passMatch = password_verify($password, $dbPass);
					
		     		if ($email == $dbEmail && $passMatch){
			     		$verified = true;
		     		}
				}
			}
			$result->close();
		}  else {
			die("Retrieval failed: ". $db_connection->error);
		}
		
		if ($email == "" || $password == "") {
			$bottomPart = "<center><h1>Invalid login information provided</h1><br /></center>";
		} else if (!$verified) {
			$bottomPart = "<center><h1>Email and password did not match</h1><br /></center>";
		} else {
			$_SESSION['currUser'] = $email;
			header("Location: homePage.php");
		}
	}

	$db_connection->close();
?>