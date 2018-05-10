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

	$currUser = $_SESSION['currUser'];
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$gender = $_POST['gender'];
	$major = $_POST['major'];
	$grade = $_POST['grade'];
	$numPartners = $_POST['numPartners'];
	$comments = $_POST['comments'];


//PROFILE PIC UPLOAD

	$imageSrc = basename($_FILES["fileToUpload"]["name"]);
	$uploadMsg = "";

	if ($imageSrc == "") {
		$newProfPic = 0;
	} else {
		$target_dir = "profilePics/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$uploadOk = 1;
		$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
		$newProfPic = 1;
		// Check if image file is a actual image or fake image
		if(isset($_POST["submit"])) {
		    $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
		    if($check !== false) {
		        //$uploadMsg .= "File is an image - " . $check["mime"] . ".\n";
		        $uploadOk = 1;
		    } else {
		        $uploadMsg .= "File is not an image.\n";
		        $uploadOk = 0;
		    }
		}
		// Check if file already exists
		// if (file_exists($target_file)) {
		//     $uploadMsg .= "Sorry, file already exists.\n";
		//     $uploadOk = 0;
		// }
		// Check file size
		if ($_FILES["fileToUpload"]["size"] > 1000000) {
		    $uploadMsg .= "Sorry, your file is too large.\n";
		    $uploadOk = 0;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) {
		    $uploadMsg .= "Sorry, only JPG, JPEG, PNG & GIF files are allowed.\n";
		    $uploadOk = 0;
		}
		// Check if $uploadOk is set to 0 by an error
		if ($uploadOk == 0) {
		    $uploadMsg .= "Sorry, your file was not uploaded.\n";
		// if everything is ok, try to upload file
		} else {
		    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
		        //$uploadMsg .= "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.\n";
		    } else {
		        $uploadMsg .= "Sorry, there was an error uploading your file.\n";
		    }
		}
	}

	$db_connection = new mysqli($host, $user, $dbpassword, $database);
	if ($db_connection->connect_error) {
		die($db_connection->connect_error);
	}

	//UPDATE DATABASE

	if ($newProfPic == 1) {
		$query = "update $table set firstname = \"$firstName\", lastname = \"$lastName\", gender = \"$gender\", major = \"$major\", grade = \"$grade\", numpartners = $numPartners, comments = \"$comments\", image = \"$target_file\" where email = \"$currUser\"";
	} else {
		$query = "update $table set firstname = \"$firstName\", lastname = \"$lastName\", gender = \"$gender\", major = \"$major\", grade = \"$grade\", numpartners = $numPartners, comments = \"$comments\" where email = \"$currUser\"";
	}

	/* Executing query */
	$result = $db_connection->query($query);
	if (!$result) {
		die("Insertion failed: " . $db_connection->error);
	}

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
            
	<body>
	
	<div class="content-wrap nopadding">

				<div class="section nopadding nomargin" style="width: 100%; height: 100%; position: absolute; left: 0; top: 0; background: url('startup-photos.jpg') center center no-repeat; background-size: cover;"></div>

				<div class="section nobg full-screen nopadding nomargin">
					<div class="container vertical-middle divcenter clearfix">

						<div class="row center">
							<a href="main.html"><img src="logo-white.png" alt="Canvas Logo"></a>
						</div>

						<div class="panel panel-default divcenter noradius noborder" style="max-width: 400px; background-color: rgba(255,255,255,0.93);">
							<div class="panel-body" style="padding: 40px;">
								<div class = "row">
								
		<center><strong> Current Profile: </strong></center>
		<center><br> $uploadMsg <img src="$profPic" alt="None" width=100px height=100px> </center> <br> </br> 
		<strong> <u>First Name</u>: $firstName </strong> <br>
		<strong> <u>Last Name</u>: $lastName  </strong> <br>
		<strong> <u>Gender</u>: $gender </strong> <br>
		<strong> <u>Major</u>: $major </strong> <br>
		<strong> <u>Grade</u>: $grade </strong> <br>
		<strong> <u>Number of Partners</u>: $numPartners </strong> <br>
		<strong> <u>Comments</u>: $comments </strong <br>
		
		<div class="col_full">
		</br>
		<h2> We will match you with people who share same interests! </h2>
		</div>
		<center>
		<input type="button" onclick="location.href='profilePage.php';" value="Edit Profile" class = "btn btn-primary"/>
		<input type="button" onclick="location.href='homePage.php';" value="Home Page" class = "btn btn-primary"/>
		</center>
	</div>
		</body>
		</html>

							</div>
						</div>
					</div>
				</div>

			</div>

		</section><!-- #content end -->

	</div><!-- #wrapper end -->

BODY;

	echo $body;
?>