<?php require "../includes/header.php" ?>
<?php require "../config/config.php" ?>

<?php

// TODO: API RESPONSE ARRAY

$response = array();

// TODO: CHECK IF THE REQUEST METHOD IS POST 

if($_SERVER["REQUEST_METHOD"] == "POST"){
	// TODO: EXTRACT THE DATA POST REQUEST 
	$name = $_POST["name"];
	$email = $_POST["email"];
	$username = $_POST["username"];
	$password = $_POST["password"];
	$about = $_POST["about"];
	$avatar = $_FILES["avatar"]["name"];

	$dir = "img/" . basename($avatar);

	// TODO: PERFORM BASIC VALIDATION 
	if(empty($email)||empty($username)||empty($password)||empty($about)){
		$response["status"]= "failed";
		$response["message"]= "All fields are required";
		echo '<script>alert("all fields are required")</script>';
	}else{
		// TODO:CHECK IF THE USER ALREADY EXISTS OR NOT
		$query = "SELECT * FROM users WHERE email= '$email' OR username= '$username'";
		$result = $conn->query($query);

		if($result->num_rows>0){
			$response["status"]= "failed";
			$response["message"]= "Username or email already exists";
			echo '<script>alert("Username or email already exists")</script>';
		}else{
			// TODO: ENCRYPT THE PASSWORD 
			$hashed_password = password_hash($password, PASSWORD_DEFAULT);
			// TODO: INSERT THE NEW USER INTO THE DATABASE
			$query= "INSERT INTO users (email, name, password, username, avatar, about)
			VALUES ('$email', '$name', '$hashed_password', '$username', '$avatar', '$about');
			";

			if($conn->query($query)===TRUE){
				$response["status"]="success";
				$response["message"]= "User registered successfully";
				echo '<script>alert("User registered successfully")</script>';
				
			}else{
				$response["status"]= "failed";
				$response["message"]= "Error" . $conn->error;

			}

		}
	}

}else{
	$response["status"]="failed";
	$response["message"]= "Invalid request method";
}


// TODO: CLOSE THE DATABASE CONNECTION
$conn->close();

// TODO: CONVERT THE RESPONSE ARRAY TO JSON FORMAT AND SEND IT TO THE CLIENT

// header("Content-Type: application/json");
// echo json_encode($response);

?>


    <div class="container">
		<div class="row">
			<div class="col-md-8">
				<div class="main-col">
					<div class="block">
						<h1 class="pull-left">Register</h1>
						<h4 class="pull-right">A Simple Forum</h4>
						<div class="clearfix"></div>
						<hr>
						<form role="form" enctype="multipart/form-data" method="post" action="register.php">
							<div class="form-group">
								<label>Name*</label> <input type="text" class="form-control"
							name="name" placeholder="Enter Your Name">
							</div>
							<div class="form-group">
							<label>Email Address*</label> <input type="email" class="form-control"
							name="email" placeholder="Enter Your Email Address">
							</div>
						<div class="form-group">
					<label>Choose Username*</label> <input type="text"
							class="form-control" name="username" placeholder="Create A Username">
						</div>
					<div class="form-group">
					<label>Password*</label> <input type="password" class="form-control"
				name="password" placeholder="Enter A Password">
				</div>
				<div class="form-group">
		<label>Confirm Password*</label> <input type="password"
			class="form-control" name="password2"
			placeholder="Enter Password Again">
			</div>
				<div class="form-group">
					<label>Upload Avatar</label>
				<input type="file" name="avatar">
				<p class="help-block"></p>
					</div>
					<div class="form-group">
					<label>About Me</label>
					<textarea id="about" rows="6" cols="80" class="form-control"
					name="about" placeholder="Tell us about yourself (Optional)"></textarea>
			</div>
			<input name="submit" type="submit" class="color btn btn-default" value="Register" />
</form>
					</div>
				</div>
			</div>

<?php require "../includes/footer.php" ?>