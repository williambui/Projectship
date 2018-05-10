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

	$db_connection = new mysqli($host, $user, $dbpassword, $database);
	if ($db_connection->connect_error) {
		die($db_connection->connect_error);
	}

	$currUser = $_SESSION["currUser"];
	$firstName = "";
	$lastName = "";
	$genderM = "";
	$genderF = "";
	$majorCS = "";
	$majorBS = "";
	$majorEN = "";
	$gradeSr = "";
	$gradeJr = "";
	$gradeSp = "";
	$gradeFr = "";
	$numPartners = 1;
	$comments = "";

	$query = "select * from $table";
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

				//Filling in form with current user profile
				
				if ($currUser == $dbEmail) {

						$firstName = $row['firstname'];
						$lastName = $row['lastname'];
						if ($row['gender'] == "Male") {
							$genderM = "checked = checked";
						}
						if ($row['gender'] == "Female") {
							$genderF = "checked = checked";
						}

						if ($row['major'] == "Computer Science") {
							$majorCS = "checked = checked";
						}
						if ($row['major'] == "Business") {
							$majorBS = "checked = checked";
						}
						if ($row['major'] == "Engineering") {
							$majorEN = "checked = checked";
						}

						if ($row['grade'] == "Senior") {
							$gradeSr = "checked = checked";
						}
						if ($row['grade'] == "Junior") {
							$gradeJr = "checked = checked";
						}
						if ($row['grade'] == "Sophomore") {
							$gradeSp = "checked = checked";
						}
						if ($row['grade'] == "Freshman") {
							$gradeFr = "checked = checked";
						}

						$numPartners = $row['numpartners'];
						$comments = $row['comments'];
						$profPic = $row['image'];
				}


			}
		}
		$result->close();
	}  else {
		die("Retrieval failed: ". $db_connection->error);
	}


	$body = <<<BODY
<html>
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
            
    <body style = "	background: url('startup-photos.jpg') no-repeat center center fixed; 
 	-webkit-background-size: cover;
 	-moz-background-size: cover;
 	-o-background-size: cover;
 	background-size: cover;
	opacity" >
	<div class = "container-fluid">
	<br>
	

		<form class = "form-horizontal" action="confirmationPage.php" method="post" enctype="multipart/form-data">
			<center> <h2> Please fill the blanks. We will match you with people</h2></center>
	
		<center><img src="$profPic" class="avatar img-square" alt="None" width=300px height=300px> </center>
			</br>
		<center>	<input type="file" name= "fileToUpload" id = "fileToUpload"> </center> </br>
			
			
			<div class="form-group">
            <label class="col-lg-2 control-label">First name:</label>
            <div class="col-lg-8">
              <input class="form-control" type="text" name="firstName" value="$firstName" required="required">
            </div>
          </div>
		  <div class="form-group">
            <label class="col-lg-2 control-label">Last Name:</label>
            <div class="col-lg-8">
			
              <input class="form-control" type="text" name ="lastName" value="$lastName" required="required">
            </div>
          </div>
		  
		  	  <div class="form-group">
            <label class="col-lg-2 control-label">Gender:</label>
            <div class="col-lg-8">
				<input type="radio" name="gender" value="Male" $genderM> Male <br>
				<input type="radio" name="gender" value="Female" $genderF> Female <br><br>
            </div>
			</div>
				
			<div class ="form-group">
		     <label class="col-lg-2 control-label">Major:</label>
            <div class="col-lg-8">
  				<input type="radio" name="major" value="Computer Science" $majorCS> Computer Science<br>
  				<input type="radio" name="major" value="Business" $majorBS> Business<br>
  				<input type="radio" name="major" value="Engineering" $majorEN> Engineering <br><br>
            </div>
			</div>
			
			<div class ="form-group">
		     <label class="col-lg-2 control-label">Year:</label>
            <div class="col-lg-8">
  				<input type="radio" name = "grade" value="Senior" $gradeSr> Senior <br>
  				<input type="radio" name = "grade" value="Junior" $gradeJr> Junior <br>
  				<input type="radio" name = "grade" value="Sophomore" $gradeSp> Sophomore <br>
  				<input type="radio" name = "grade" value="Freshman" $gradeFr> Freshmen <br>
            </div>
			</div>

			<div class ="form-group">
		     <label class="col-lg-2 control-label">Number of Parters you are looking for:</label>
            <div class="col-lg-8">
  				<input type = "number" name ="numPartners" size ="2" min="1" max="10" value="$numPartners">
            </div>
			</div>
			
			<div class ="form-group">
		    <label class="col-lg-2 control-label">Briefly introduce yourself:</label>
            <div class="col-lg-8">
				<textarea id="textfield" name ="comments" rows="7" cols="150">$comments</textarea>
            </div>
			</div>
			
			<div class ="form-group">
		    <div class="col-lg-2"></div>
            <div class="col-lg-8">
				<input type="reset" value ="Clear Form" class = "btn-primary btn" />
				<input type="submit" name="submit" value="Submit Information" class = "btn-primary btn" />
				<input type ="button"  class = "btn-primary btn" onclick="location.href='homePage.php';" value="Home Page" />
            </div>
			</div>		
			
		</form>
		</br>
		</div>
		   </body>
		   
</html>
BODY;

	$db_connection->close();


	echo $body;
?>