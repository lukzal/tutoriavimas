<?php
// Include config file
require_once 'config.php';
 session_start();


// Define variables and initialize with empty values
 $name_error = $lastname_error = $course_error = $student_error = "";
 $name = $lastname = $course = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
	if (empty(trim($_POST["name"]))){
$name_error = "This field is required";
}
 	 if (preg_match('/[^A-Za-z]/', trim($_POST["name"]))) {
$name_error = "Only letters are allowed";
}

 		if (empty(trim($_POST["lastname"]))){
		$lastname_error = "This field is required";
		}
 	 if (preg_match('/[^A-Za-z]/', trim($_POST["lastname"]))) {
	$lastname_error = "Only letters are allowed";
	}
 	
	if (preg_match('/[^0-9]/', trim($_POST["course"]))) {
		$course_error = "Only digits are allowed";
}
 		if (empty($_POST["student"])){
		$student_error = "This field is required";
}

    // Check input errors before inserting in database
    if(empty($name_error) && empty($lastname_error) && empty($course_error) && empty($student_error)){
        
   $name = trim($_POST["name"]);
      $lastname = trim($_POST["lastname"]);
   $course = trim($_POST["course"]);
   $student = $_POST["student"];
$username =$_SESSION['username'];

        // Prepare an insert statment
        $sql = "UPDATE users SET name = ?, lastname = ?, course = ?, student = ? WHERE username = ?";
         
        if($statment = $connection->prepare($sql)){
            // Bind variables to the prepared statment as parameters
            $statment->bind_param("sssss", $param_name, $param_lastname, $param_course, $param_student, $param_username);
            
            // Set parameters
            $param_name = $name;
			$param_lastname = $lastname;
            $param_course = $course;
            $param_student = $student;
            $param_username = $username;
            echo $username;
            // Attempt to execute the prepared statment
            if($statment->execute()){
                // Redirect to login page
                header("location: login.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }
       
   // Close statment
        $statment->close();
	   }
         
     
    }
    
    // Close connection
    $connection->close();
	
// If session variable is not set it will redirect to login page
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("location: login.php");
  exit;
}
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
    <div class="wrapper">
        <h2>Sign Up</h2>
        <p>Please fill this form to create an account.</p>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            	            <div class="form-group <?php echo (!empty($name_error)) ? 'has-error' : ''; ?>">

		 Vardas
               <p> <input type="text" name="name" ></p>
			                   <span class="help-block"><?php echo $name_error; ?></span>
</div>
            <div class="form-group <?php echo (!empty($lastname_error)) ? 'has-error' : ''; ?>">
			   		 Pavarde
               <p> <input type="text" name="lastname" ></p>
			   			                   <span class="help-block"><?php echo $lastname_error; ?></span>
</div>
            <div class="form-group <?php echo (!empty($course_error)) ? 'has-error' : ''; ?>">
			   		 Kursas
			   <p> <input type="text" name="course" ></p>
			   			                   <span class="help-block"><?php echo $course_error; ?></span>
			                  
		            
    </div>
	            <div class="form-group <?php echo (!empty($student_error)) ? 'has-error' : ''; ?>">

	  <input type="radio" name="student" value="student"> Studentas<br>
  <input type="radio" name="student" value="tutor"> Tutorius<br>
           			   			                   <span class="help-block"><?php echo $student_error; ?></span>
</div>
                <input type="submit" class="btn btn-primary" value="Submit">
                <input type="reset" class="btn btn-default" value="Reset">
            </div>
            <p>Already have an account? <a href="login.php">Login here</a>.</p>
        </form>
    </div>    
</body>
</html>
