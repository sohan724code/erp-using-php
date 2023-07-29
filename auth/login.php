<?php require "../includes/header.php" ?>
<?php require "../config/config.php" ?>
<?php

// TODO: API RESPONSE ARRAY

$response = array();

// TODO: CHECK IF THE REQUEST METHOD IS POST 

if($_SERVER["REQUEST_METHOD"] == "POST"){
	// TODO: EXTRACT THE DATA POST REQUEST 
	$email = $_POST["email"];
	$password = $_POST["password"];

	// TODO: PERFORM BASIC VALIDATION 
	if(empty($email)||empty($password)){
		$response["status"]= "failed";
		$response["message"]= "All fields are required";
		echo '<script>alert("all fields are required")</script>';
	}else{
	   // TODO:CHECK IF THE USER ALREADY EXISTS OR NOT
        // TODO:Use prepared statements to prevent SQL injection
        $query = "SELECT * FROM users WHERE email = ? AND password = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
          $user = $result->fetch_assoc();

          // TODO:Verify the hashed password using password_verify() function
          if (password_verify($password, $user['password'])) {
              $response["status"] = "success";
              $response["message"] = "Login successful";
              // TODO:You can include additional user data in the response if needed
              $response["user"] = $user;
          } else {
              $response["status"] = "failed";
              $response["message"] = "Incorrect Email or Password";
          }
      } else {
          $response["status"] = "failed";
          $response["message"] = "User not found";
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
echo json_encode($response);

?>

  <div class="container">
    <div class="row">
      <div class="col-md-8">
        <div class="main-col">
          <div class="block">
            <h1 class="pull-left">Login</h1>
            <h4 class="pull-right">A Simple Forum</h4>
            <div class="clearfix"></div>
            <hr>
            <form role="form" enctype="multipart/form-data" method="post" action="login.php">

              <div class="form-group">
                <label>Email Address*</label> <input type="email" class="form-control" name="email"
                  placeholder="Enter Your Email Address">
              </div>

              <div class="form-group">
                <label>Password*</label> <input type="password" class="form-control" name="password"
                  placeholder="Enter A Password">
              </div>

              <input name="submit" type="submit" class="color btn btn-default" value="Login" />
            </form>
          </div>
        </div>
      </div>
<?php require "../includes/footer.php" ?>