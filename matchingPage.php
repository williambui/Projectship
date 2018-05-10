<!DOCTYPE html>
<html dir="ltr" lang="en-US">
<head>

	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="author" content="SemiColonWeb" />

	<!-- Stylesheets
	============================================= -->
	<link href="http://fonts.googleapis.com/css?family=Lato:300,400,400italic,600,700|Raleway:300,400,500,600,700|Crete+Round:400italic" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="css/bootstrap.css" type="text/css" />
	<link rel="stylesheet" href="css/animate.css" type="text/css" />>
	 <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<!-- Document Title
	============================================= -->
	<title>Matching Page</title>

</head>

<body>

<?php
	require_once("support.php");

	if (session_id() == NULL) {
		session_start();
	}

	$filterM = "checked=checked";
	$filterF = "checked=checked";
	$filterCS = "checked=checked";
	$filterBS = "checked=checked";
	$filterEN = "checked=checked";
	$filterSr = "checked=checked";
	$filterJr = "checked=checked";
	$filterSp = "checked=checked";
	$filterFr = "checked=checked";

	$queryFilter = "";

	if (isset($_POST['gender'])) {
		if (!in_array("Male", $_POST['gender'])) {
			$filterM = "";
		}
		if (!in_array("Female", $_POST['gender'])) {
			$filterF = "";
		}

		if (getFilter($_POST['gender'], "gender") != "") {
			$queryFilter .= "(".getFilter($_POST['gender'], "gender").")";
		}
	}

	if (isset($_POST['major'])) {
		if (!in_array("Computer Science", $_POST['major'])) {
			$filterCS = "";
		}
		if (!in_array("Business", $_POST['major'])) {
			$filterBS = "";
		}
		if (!in_array("Engineering", $_POST['major'])) {
			$filterEN = "";
		}

		if (getFilter($_POST['major'], "major") != "" && getFilter($_POST['gender'], "gender") != "") {
			$queryFilter .= " and ";
		}

		if (getFilter($_POST['major'], "major") != "") {
			$queryFilter .= "(".getFilter($_POST['major'], "major").")";
		}
	}

	if (isset($_POST['grade'])) {
		if (!in_array("Senior", $_POST['grade'])) {
			$filterSr = "";
		}
		if (!in_array("Junior", $_POST['grade'])) {
			$filterJr = "";
		}
		if (!in_array("Sophomore", $_POST['grade'])) {
			$filterSp = "";
		}
		if (!in_array("Freshman", $_POST['grade'])) {
			$filterFr = "";
		}

		if ($queryFilter != "" && getFilter($_POST['grade'], "grade") != "") {
			$queryFilter .= " and ";
		}
		if (getFilter($_POST['grade'], "grade") != "") {
			$queryFilter .= "(".getFilter($_POST['grade'], "grade").")";
		}
		
	}

	$host = "localhost";
	$user = "dbuser";
	$dbpassword = "goodbyeWorld";
	$database = "projectship";
	$table = "userprofiles";

	$currUser = $_SESSION['currUser'];

	/* Connecting to the database */		
	$db_connection = new mysqli($host, $user, $dbpassword, $database);
	if ($db_connection->connect_error) {
		die($db_connection->connect_error);
	}

	$profilesArray = [];
	$usersArray = [];

	if ($queryFilter != "") {
		$query = "select * from $table where email != \"$currUser\" and ($queryFilter)";
	} else {
		$query = "select * from $table where email != \"$currUser\"";
	}
	
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

				$usersArray[$row_index] = $dbEmail;

				$firstName = $row['firstname'];
				$lastName = $row['lastname'];
				$gender = $row['gender'];
				$major = $row['major'];
				$grade = $row['grade'];
				$numPartners = $row['numpartners'];
				$comments = $row['comments'];
				$profPic = $row['image'];

				$profilesArray[$dbEmail] = "<img src=\"" . $profPic . "\" alt=\"No Profile Picture\" width=250px height=250px><br>" . "<strong>Name: " . $firstName . " " . $lastName . "</strong><br> Gender: " . $gender . "<br> Major: " . $major . "<br> Grade: " . $grade . "<br> About: " . $comments . "<br>";
			}
		}
		$result->close();
	}  else {
		die("Retrieval failed: ". $db_connection->error);
	}

	$userEmailsJSON = json_encode($usersArray);
	$profilesJSON = json_encode($profilesArray);

	$body = <<<BODY

	<!-- Document Wrapper
	============================================= -->
	<div id="wrapper" class="clearfix">

		<!-- Content
		============================================= -->
		<section id="content">

			<div class="content-wrap nopadding">

				<div class="section nopadding nomargin" style="width: 100%; height: 100%; position: absolute; left: 0; top: 0; background-size: cover; overflow: auto;"></div>

				 <div class="section nobg full-screen nopadding nomargin">
				 	<div class="container vertical-middle divcenter clearfix">	
		<br>
		<br>	
		<center><h2> Here are the "Matched" friends! </h2>
		<br>

		<form action="{$_SERVER['PHP_SELF']}" method="post">
			<h2><strong>Filter:</strong></h2>

			<span class="filter">Gender: <br> 
			<input type="checkbox" name="gender[]" value="Male" $filterM>Male <br>
			<input type="checkbox" name="gender[]" value="Female" $filterF>Female <br>
			<input type="hidden" name="gender[]" value="">
			<br><br>
			</span>

			<span class="filter">Major: <br>
			<input type="checkbox" name="major[]" value="Computer Science" $filterCS>Computer Science <br>
			<input type="checkbox" name="major[]" value="Business" $filterBS>Business <br>
			<input type="checkbox" name="major[]" value="Engineering" $filterEN>Engineering <br>
			<input type="hidden" name="major[]" value="">
			<br>
			</span>

			<span class="filter">Grade: <br>
			<input type="checkbox" name="grade[]" value="Senior" $filterSr>Senior <br>
			<input type="checkbox" name="grade[]" value="Junior" $filterJr>Junior <br>
			<input type="checkbox" name="grade[]" value="Sophomore" $filterSp>Sophomore <br>
			<input type="checkbox" name="grade[]" value="Freshman" $filterFr>Freshman <br>
			<input type="hidden" name="grade[]" value="">
			</span>

			<br>

			<input type="submit" class="btn btn-default btn" name="submit" value="Filter Results">
			<br>
			<br>
		</form>
		<input type="button" class="btn btn-default btn-sm" id="prev" value="Previous Match"> 
	    <input type="button" class="btn btn-default btn-sm" id="next" value="Next Match">

		<br><br>

		<div class="card">
			<div id="profile">
			</div>
		 	<p><button id="contact">Contact</button></p>
		 	<!-- The Modal -->
			<div id="myModal" class="modal">

			  <!-- Modal content -->
			  <div class="modal-content">
			    <span class="close">&times;</span>
			    <p><h2>User has been contacted!</h2></p>
			  </div>

			</div>
		</div>	
		<input type="button" onclick="location.href='homePage.php';" class="btn btn-default" value="Home Page" /> </center>
		<br>
		<br>
		<br>
		<br>
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

		<script>
			"use strict";

			var profiles = $profilesJSON;
			var emails = $userEmailsJSON;
			var profileIndex = 0;

			console.log(profiles);
			console.log(emails);

			var noMatch = "<h1>Sorry!</h1><br><h2>We couldn't find any matches for you! </h2><h3>(Try changing your filter settings)</h3>"

			if (profiles.length == 0) {
				document.getElementById("profile").innerHTML = noMatch;
			} else {
				document.getElementById("profile").innerHTML = profiles[emails[profileIndex]];
			}

			document.getElementById("next").addEventListener("click", function() {
				if (profileIndex < emails.length - 1) {
					profileIndex++;
					document.getElementById("profile").innerHTML = profiles[emails[profileIndex]];	
				}
			});

			document.getElementById("prev").addEventListener("click", function() {
				if (profileIndex > 0) {
					profileIndex--;
					document.getElementById("profile").innerHTML = profiles[emails[profileIndex]];
				}
			});


			// Get the modal
			var modal = document.getElementById('myModal');

			// Get the button that opens the modal
			var btn = document.getElementById("contact");

			// Get the <span> element that closes the modal
			var span = document.getElementsByClassName("close")[0];

			// When the user clicks the button, open the modal 
			btn.onclick = function() {
			    modal.style.display = "block";
			}

			// When the user clicks on <span> (x), close the modal
			span.onclick = function() {
			    modal.style.display = "none";
			}

			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
			    if (event.target == modal) {
			        modal.style.display = "none";
			    }
			}



		</script>

BODY;

function getFilter($postArr, $category) {
	$filters = "";

	for ($i = 0; $i < count($postArr) - 1; $i++) {
		if ($i == count($postArr) - 2) {
			$filters .= "$category = \"$postArr[$i]\" ";
		} else {
			$filters .= "$category = \"$postArr[$i]\" or ";
		}
	}
	return $filters;
}

	$db_connection->close();

	$page = generatePage($body, "Matching");
	echo $page;
?>
</body>
</html>