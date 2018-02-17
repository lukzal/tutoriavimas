<?php
include_once('connect_DB.php');
$database = new connect_DB();
$changed = false;

if($changed == true){
	echo "Password changed";
}else{
if(isset($_GET["token"])){
	$token = $database->getDB()->real_escape_string($_GET["token"]);
	$sql = "SELECT * FROM tokens WHERE token = '$token'";
	$result = $database->getDB()->query($sql);
	if(mysqli_num_rows($result) == 0){
		echo "Token error";
	}else{
		if(isset($_POST["password"]) && isset($_POST["password_repeat"])){
			if($_POST["password"] == $_POST["password_repeat"]){
				$password = $database->getDB()->real_escape_String($_POST["password"]);
				$password_hashed = password_hash($password, PASSWORD_DEFAULT);
				$sql = "SELECT * FROM tokens WHERE token = '$token'";
				$user_id = mysqli_fetch_array($database->getDB()->query($sql))[0];
				$sql = "UPDATE users
				SET password = '$password_hashed'
				WHERE id = '$user_id'";
				$database->getDB()->query($sql);
				$changed = true;
				header("Location: /password_change.php");
			}else{
				echo "Passwords are not equal";
			}
		}
?>
<html>
<head>
<body>
<form name="form" method="post" action="password_change.php/?token=<?php echo $token; ?>" >
<input type="password" name="password" id="password">
<input type="password" name="password_repeat" id="password_repeat">
<button type="submit">Submit</button>
</form>


</body>
</head>
</html>

<?php
	}
}else{
	
	if(isset($_POST["email"])){
		$to = $database->getDB()->real_escape_string($_POST["email"]);
		include("token.php");
		$token = new Token();
		
		$sql_userId = "SELECT id FROM users WHERE username = '$to'";
		$result = $database->getDB()->query($sql_userId);
		
		if(mysqli_num_rows($result) == 0){
			echo "Wrong email";
		}else{
			$token->send_mail($to, mysqli_fetch_array($result)[0]);
		}
		
	}
?>

<html>
<head>
<body>
<form name="form" method="post" action="password_change.php" >
<input type="text" name="email" id="email">
<button type="submit">Submit</button>
</form>


</body>
</head>
</html>

<?php
}
}
?>

