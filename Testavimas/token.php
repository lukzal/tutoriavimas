<?php
class Token{
	private function crypto_rand_secure($min, $max)
{
    $range = $max - $min;
    if ($range < 1) return $min; // not so random...
    $log = ceil(log($range, 2));
    $bytes = (int) ($log / 8) + 1; // length in bytes
    $bits = (int) $log + 1; // length in bits
    $filter = (int) (1 << $bits) - 1; // set all lower bits to 1
    do {
        $rnd = hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)));
        $rnd = $rnd & $filter; // discard irrelevant bits
    } while ($rnd > $range);
    return $min + $rnd;
}

 private function getToken($length)
{
    $token = "";
    $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
    $codeAlphabet.= "0123456789";
    $max = strlen($codeAlphabet); // edited

    for ($i=0; $i < $length; $i++) {
        $token .= $codeAlphabet[$this->crypto_rand_secure(0, $max-1)];
    }

    return $token;
}

function send_mail($email, $user_id){
	include_once("connect_DB.php");
	$database = new connect_DB();
	
	$token = $this->getToken(100);
	
	$sql = "SELECT * FROM tokens WHERE user_id = '$user_id'";
	$result = $database->getDB()->query($sql);
	if(mysqli_num_rows($result) == 0){
		$sql = "INSERT INTO tokens (user_id, token) VALUES ('$user_id', '$token')";
		$database->getDB()->query($sql);
	}else{
		echo "Token already generated";
	}
	
	
	/*
	$to = $email;
	$subject = "Password Change";
	$message = "" . '<a href="localhost/password_change.php/?token=' . $token .'">Change</a>';
	$headers = 'From: webmaster@example.com';
	
	mail($to, $subject, $message, $headers);
	*/
}
}
?>